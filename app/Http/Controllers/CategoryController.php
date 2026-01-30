<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
   /**
    * Display a listing of categories.
    */
   /**
    * Display a listing of categories.
    */
   public function index(Request $request)
   {
      $type = $request->query('type', 'all');

      $query = Category::where('user_id', Auth::id())
         ->whereNull('parent_id')
         ->with('children')
         ->orderBy('position') // Added position sort
         ->orderBy('name'); // Fallback

      if ($type !== 'all') {
         $query->where('type', $type);
      }

      $categories = $query->get();

      return view('categories.index', compact('categories', 'type'));
   }

   /**
    * Show the form for creating a new category.
    */
   public function create()
   {
      $parentCategories = Category::where('user_id', Auth::id())
         ->whereNull('parent_id')
         ->get();

      return view('categories.create', compact('parentCategories'));
   }

   /**
    * Store a newly created category.
    */
   public function store(Request $request)
   {
      $validated = $request->validate([
         'name' => 'required|string|max:255',
         'type' => 'required|in:income,expense',
         'icon' => 'nullable|string|max:50',
         'color' => 'nullable|string|max:7',
         'parent_id' => 'nullable|exists:categories,id',
         'keywords' => 'nullable|string', // Comma separated string
      ]);

      $validated['user_id'] = Auth::id();

      // Process keywords
      if (!empty($validated['keywords'])) {
         $keywords = array_map('trim', explode(',', $validated['keywords']));
         $validated['keywords'] = array_filter($keywords);
      } else {
         $validated['keywords'] = [];
      }

      // Validate parent category belongs to same user and same type
      if (!empty($validated['parent_id'])) {
         $parent = Category::where('id', $validated['parent_id'])
            ->where('user_id', Auth::id())
            ->where('type', $validated['type'])
            ->firstOrFail();
      }

      Category::create($validated);

      return redirect()->route('categories.index')
         ->with('success', 'Danh mục đã được tạo thành công!');
   }

   /**
    * Show the form for editing the category.
    */
   public function edit(Category $category)
   {
      $this->authorize('update', $category);

      $parentCategories = Category::where('user_id', Auth::id())
         ->whereNull('parent_id')
         ->where('id', '!=', $category->id)
         ->where('type', $category->type)
         ->get();

      return view('categories.edit', compact('category', 'parentCategories'));
   }

   /**
    * Update the specified category.
    */
   public function update(Request $request, Category $category)
   {
      $this->authorize('update', $category);

      $validated = $request->validate([
         'name' => 'required|string|max:255',
         'icon' => 'nullable|string|max:50',
         'color' => 'nullable|string|max:7',
         'parent_id' => 'nullable|exists:categories,id',
         'keywords' => 'nullable|string',
      ]);

      // Process keywords
      if (isset($validated['keywords'])) {
         $keywords = array_map('trim', explode(',', $validated['keywords']));
         $validated['keywords'] = array_filter($keywords);
      }

      // Cannot change parent to self or children
      if (!empty($validated['parent_id'])) {
         if ($validated['parent_id'] == $category->id) {
            return back()->withErrors(['parent_id' => 'Không thể chọn chính nó làm danh mục cha']);
         }

         $childIds = $category->children->pluck('id')->toArray();
         if (in_array($validated['parent_id'], $childIds)) {
            return back()->withErrors(['parent_id' => 'Không thể chọn danh mục con làm danh mục cha']);
         }
      }

      $category->update($validated);

      return redirect()->route('categories.index')
         ->with('success', 'Danh mục đã được cập nhật!');
   }

   /**
    * Remove the specified category.
    */
   public function destroy(Category $category)
   {
      $this->authorize('delete', $category);

      // Check if category has transactions
      if ($category->transactions()->exists()) {
         return redirect()->route('categories.index')
            ->with('error', 'Không thể xóa danh mục vì còn giao dịch liên quan!');
      }

      // Move child categories to parent
      if ($category->children->count() > 0) {
         Category::where('parent_id', $category->id)
            ->update(['parent_id' => $category->parent_id]);
      }

      $category->delete();

      return redirect()->route('categories.index')
         ->with('success', 'Danh mục đã được xóa!');
   }

   public function reorder(Request $request)
   {
      $request->validate([
         'order' => 'required|array',
      ]);

      $order = $request->input('order');

      foreach ($order as $index => $item) {
         Category::where('id', $item['id'])
            ->where('user_id', Auth::id())
            ->update([
               'position' => $index,
               'parent_id' => $item['parent_id'] ?? null
            ]);
      }

      return response()->json(['success' => true]);
   }
}
