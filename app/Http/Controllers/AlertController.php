<?php

namespace App\Http\Controllers;

use App\Models\BudgetAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlertController extends Controller
{
   /**
    * Get unread alerts count for navbar.
    */
   public function unreadCount()
   {
      $count = BudgetAlert::forUser(Auth::id())
         ->unread()
         ->count();

      return response()->json(['count' => $count]);
   }

   /**
    * Get recent alerts for dropdown.
    */
   public function recent()
   {
      $alerts = BudgetAlert::forUser(Auth::id())
         ->with('budget.category')
         ->orderBy('created_at', 'desc')
         ->take(10)
         ->get();

      return response()->json($alerts);
   }

   /**
    * Mark an alert as read.
    */
   public function markAsRead(BudgetAlert $alert)
   {
      if ($alert->user_id !== Auth::id()) {
         abort(403);
      }

      $alert->update(['is_read' => true]);

      return response()->json(['success' => true]);
   }

   /**
    * Mark all alerts as read.
    */
   public function markAllAsRead()
   {
      BudgetAlert::forUser(Auth::id())
         ->unread()
         ->update(['is_read' => true]);

      return response()->json(['success' => true]);
   }

   /**
    * Get all alerts page.
    */
   public function index()
   {
      $alerts = BudgetAlert::forUser(Auth::id())
         ->with('budget.category')
         ->orderBy('created_at', 'desc')
         ->paginate(20);

      return view('alerts.index', compact('alerts'));
   }
}
