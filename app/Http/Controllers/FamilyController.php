<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\FamilyMember;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FamilyController extends Controller
{
   /**
    * Display a listing of families the user belongs to.
    */
   public function index()
   {
      $userId = Auth::id();

      $families = Family::whereHas('members', function ($query) use ($userId) {
         $query->where('user_id', $userId);
      })->with(['members.user', 'creator'])->get();

      return view('families.index', compact('families'));
   }

   /**
    * Show the form for creating a new family.
    */
   public function create()
   {
      return view('families.create');
   }

   /**
    * Store a newly created family.
    */
   public function store(Request $request)
   {
      $validated = $request->validate([
         'name' => 'required|string|max:255',
      ]);

      $family = Family::create([
         'name' => $validated['name'],
         'created_by' => Auth::id(),
      ]);

      // Add creator as admin
      FamilyMember::create([
         'family_id' => $family->id,
         'user_id' => Auth::id(),
         'role' => 'admin',
      ]);

      return redirect()->route('families.show', $family)
         ->with('success', __('Đã tạo gia đình thành công! Mã mời: ') . $family->invite_code);
   }

   /**
    * Display the specified family.
    */
   public function show(Family $family)
   {
      if (!$family->hasMember(Auth::id())) {
         abort(403);
      }

      $family->load(['members.user', 'wallets', 'creator']);
      $currentMember = $family->members()->where('user_id', Auth::id())->first();

      // Get shared wallets for this family
      $sharedWallets = Wallet::where('family_id', $family->id)->with('user')->get();

      // Get user's personal wallets that can be shared
      $personalWallets = Wallet::where('user_id', Auth::id())
         ->whereNull('family_id')
         ->get();

      return view('families.show', compact('family', 'currentMember', 'sharedWallets', 'personalWallets'));
   }

   /**
    * Show form to join a family.
    */
   public function joinForm()
   {
      return view('families.join');
   }

   /**
    * Join a family using invite code.
    */
   public function join(Request $request)
   {
      $validated = $request->validate([
         'invite_code' => 'required|string|size:8',
      ]);

      $family = Family::where('invite_code', strtoupper($validated['invite_code']))->first();

      if (!$family) {
         return back()->withErrors(['invite_code' => __('Mã mời không hợp lệ!')]);
      }

      if ($family->hasMember(Auth::id())) {
         return back()->withErrors(['invite_code' => __('Bạn đã là thành viên của gia đình này!')]);
      }

      FamilyMember::create([
         'family_id' => $family->id,
         'user_id' => Auth::id(),
         'role' => 'member',
      ]);

      return redirect()->route('families.show', $family)
         ->with('success', __('Đã tham gia gia đình thành công!'));
   }

   /**
    * Leave a family.
    */
   public function leave(Family $family)
   {
      $member = $family->members()->where('user_id', Auth::id())->first();

      if (!$member) {
         abort(403);
      }

      // Cannot leave if you're the only admin
      if ($member->isAdmin()) {
         $adminCount = $family->members()->where('role', 'admin')->count();
         if ($adminCount <= 1) {
            return back()->withErrors(['error' => __('Bạn không thể rời đi vì là admin duy nhất. Hãy chuyển quyền admin trước!')]);
         }
      }

      $member->delete();

      return redirect()->route('families.index')
         ->with('success', __('Đã rời khỏi gia đình!'));
   }

   /**
    * Update member role.
    */
   public function updateRole(Request $request, Family $family, FamilyMember $member)
   {
      if (!$family->isAdmin(Auth::id())) {
         abort(403);
      }

      $validated = $request->validate([
         'role' => 'required|in:admin,member,viewer',
      ]);

      $member->update(['role' => $validated['role']]);

      return back()->with('success', __('Đã cập nhật vai trò!'));
   }

   /**
    * Remove a member from family.
    */
   public function removeMember(Family $family, FamilyMember $member)
   {
      if (!$family->isAdmin(Auth::id())) {
         abort(403);
      }

      if ($member->user_id === Auth::id()) {
         return back()->withErrors(['error' => __('Không thể tự xóa mình!')]);
      }

      $member->delete();

      return back()->with('success', __('Đã xóa thành viên!'));
   }

   /**
    * Share a wallet with family.
    */
   public function shareWallet(Request $request, Family $family)
   {
      if (!$family->hasMember(Auth::id())) {
         abort(403);
      }

      $validated = $request->validate([
         'wallet_id' => 'required|exists:wallets,id',
      ]);

      $wallet = Wallet::where('id', $validated['wallet_id'])
         ->where('user_id', Auth::id())
         ->firstOrFail();

      $wallet->update(['family_id' => $family->id]);

      return back()->with('success', __('Đã chia sẻ ví với gia đình!'));
   }

   /**
    * Unshare a wallet from family.
    */
   public function unshareWallet(Family $family, Wallet $wallet)
   {
      if ($wallet->user_id !== Auth::id() && !$family->isAdmin(Auth::id())) {
         abort(403);
      }

      $wallet->update(['family_id' => null]);

      return back()->with('success', __('Đã hủy chia sẻ ví!'));
   }

   /**
    * Show family spending report.
    */
   public function report(Family $family)
   {
      if (!$family->hasMember(Auth::id())) {
         abort(403);
      }

      $family->load('members.user');

      // Get all shared wallets
      $sharedWallets = Wallet::where('family_id', $family->id)->pluck('id');

      // Get spending by member from shared wallets
      $memberSpending = [];
      foreach ($family->members as $member) {
         $spent = \App\Models\Transaction::whereIn('wallet_id', $sharedWallets)
            ->where('user_id', $member->user_id)
            ->where('type', 'expense')
            ->whereMonth('transaction_date', now()->month)
            ->sum('amount');

         $memberSpending[] = [
            'user' => $member->user,
            'spent' => $spent,
         ];
      }

      // Sort by spending
      usort($memberSpending, fn($a, $b) => $b['spent'] <=> $a['spent']);

      return view('families.report', compact('family', 'memberSpending'));
   }
}
