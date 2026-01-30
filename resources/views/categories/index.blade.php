<x-app-layout>
   <x-slot name="header">
      {{ __('Categories') }}
   </x-slot>

   @push('styles')
      <style>
         .sortable-ghost {
            opacity: 0.4;
            background-color: #f4f6f9;
         }

         .category-item {
            cursor: grab;
         }

         .category-item:active {
            cursor: grabbing;
         }

         .category-handle {
            cursor: move;
         }

         .list-group-item {
            border-left: 4px solid transparent;
         }
      </style>
   @endpush

   <div class="row mb-3">
      <div class="col-md-6">
         <div class="btn-group">
            <a href="{{ route('categories.index', ['type' => 'all']) }}"
               class="btn {{ $type === 'all' ? 'btn-primary' : 'btn-default' }}">
               {{ __('All Types') }}
            </a>
            <a href="{{ route('categories.index', ['type' => 'income']) }}"
               class="btn {{ $type === 'income' ? 'btn-success' : 'btn-default' }}">
               <i class="fas fa-arrow-down mr-1"></i> {{ __('Income') }}
            </a>
            <a href="{{ route('categories.index', ['type' => 'expense']) }}"
               class="btn {{ $type === 'expense' ? 'btn-danger' : 'btn-default' }}">
               <i class="fas fa-arrow-up mr-1"></i> {{ __('Expense') }}
            </a>
         </div>
      </div>
      <div class="col-md-6 text-md-right mt-2 mt-md-0">
         <a href="{{ route('categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> {{ __('Create New Category') }}
         </a>
      </div>
   </div>

   <div class="row">
      <div class="col-12">
         <div class="card">
            <div class="card-header">
               <h3 class="card-title">{{ __('Category Structure') }}</h3>
               <div class="card-tools">
                  <button type="button" class="btn btn-tool" id="save-order-btn" style="display: none;">
                     <i class="fas fa-save"></i> {{ __('Save Order') }}
                  </button>
               </div>
            </div>
            <div class="card-body p-0">
               <div id="category-tree" class="list-group list-group-flush">
                  @forelse($categories as $category)
                     <div class="list-group-item category-item" data-id="{{ $category->id }}"
                        style="border-left-color: {{ $category->color }};">
                        <div class="d-flex align-items-center justify-content-between">
                           <div class="d-flex align-items-center">
                              <i class="fas fa-grip-vertical text-muted mr-3 category-handle"></i>
                              <div class="mr-3" style="width: 30px; text-align: center;">
                                 <i class="{{ $category->icon ?? 'fas fa-tag' }}"
                                    style="color: {{ $category->color }}; font-size: 1.2rem;"></i>
                              </div>
                              <div>
                                 <span class="font-weight-bold">{{ $category->name }}</span>
                                 <span
                                    class="badge {{ $category->type === 'income' ? 'badge-success' : 'badge-danger' }} ml-2">
                                    {{ $category->type === 'income' ? __('Income') : __('Expense') }}
                                 </span>
                                 @if(!empty($category->keywords))
                                    <small class="text-muted ml-2">
                                       <i class="fas fa-tags fa-xs"></i>
                                       {{ implode(', ', $category->keywords) }}
                                    </small>
                                 @endif
                              </div>
                           </div>

                           <div class="btn-group">
                              <a href="{{ route('categories.edit', $category) }}" class="btn btn-default btn-sm">
                                 <i class="fas fa-edit"></i>
                              </a>
                              <button type="button" class="btn btn-default btn-sm text-danger"
                                 onclick="if(confirm('{{ __('Deleting this category will also delete its transaction history. Continue?') }}')) document.getElementById('delete-cat-{{ $category->id }}').submit()">
                                 <i class="fas fa-trash"></i>
                              </button>
                           </div>
                           <form id="delete-cat-{{ $category->id }}" action="{{ route('categories.destroy', $category) }}"
                              method="POST" style="display: none;">
                              @csrf @method('DELETE')
                           </form>
                        </div>

                        <!-- Nested Children -->
                        <div class="nested-sortable mt-2 ml-5" style="min-height: 10px;">
                           @foreach($category->children as $child)
                              <div class="list-group-item category-item bg-light border rounded mb-1"
                                 data-id="{{ $child->id }}" style="border-left: 3px solid {{ $child->color }};">
                                 <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                       <i class="fas fa-grip-vertical text-muted mr-3 category-handle"></i>
                                       <i class="{{ $child->icon ?? 'fas fa-tag' }} mr-2"
                                          style="color: {{ $child->color }}"></i>
                                       <span>{{ $child->name }}</span>
                                       @if(!empty($child->keywords))
                                          <small class="text-muted ml-2">({{ implode(', ', $child->keywords) }})</small>
                                       @endif
                                    </div>
                                    <div class="btn-group">
                                       <a href="{{ route('categories.edit', $child) }}" class="btn btn-default btn-xs">
                                          <i class="fas fa-edit"></i>
                                       </a>
                                       <button type="button" class="btn btn-default btn-xs text-danger"
                                          onclick="if(confirm('{{ __('Delete this category?') }}')) document.getElementById('delete-cat-{{ $child->id }}').submit()">
                                          <i class="fas fa-trash"></i>
                                       </button>
                                    </div>
                                    <form id="delete-cat-{{ $child->id }}" action="{{ route('categories.destroy', $child) }}"
                                       method="POST" style="display: none;">
                                       @csrf @method('DELETE')
                                    </form>
                                 </div>
                              </div>
                           @endforeach
                        </div>
                     </div>
                  @empty
                     <div class="text-center py-5">
                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                        <p class="text-muted">{{ __('No categories found') }}</p>
                     </div>
                  @endforelse
               </div>
            </div>
         </div>
      </div>
   </div>

   @push('scripts')
      <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
      <script>
         document.addEventListener('DOMContentLoaded', function () {
            const nestedSortables = [].slice.call(document.querySelectorAll('.nested-sortable'));
            const rootSortable = document.getElementById('category-tree');

            // Root list
            new Sortable(rootSortable, {
               group: 'nested',
               animation: 150,
               fallbackOnBody: true,
               swapThreshold: 0.65,
               handle: '.category-handle',
               onEnd: function (evt) {
                  saveOrder();
               }
            });

            // Nested lists
            nestedSortables.forEach(function (el) {
               new Sortable(el, {
                  group: 'nested',
                  animation: 150,
                  fallbackOnBody: true,
                  swapThreshold: 0.65,
                  handle: '.category-handle',
                  onEnd: function (evt) {
                     saveOrder();
                  }
               });
            });

            function saveOrder() {
               const order = [];
               const items = document.querySelectorAll('.category-item');

               // Simple traversal to build flat list with parent mapping
               // This assumes the DOM structure reflects the new hierarchy
               // Note: SortableJS moves DOM elements, so we just walk the DOM

               document.querySelectorAll('#category-tree > .category-item').forEach((parentEl, index) => {
                  const parentId = parentEl.getAttribute('data-id');
                  order.push({ id: parentId, parent_id: null, index: index });

                  // Check children
                  parentEl.querySelectorAll('.nested-sortable > .category-item').forEach((childEl, childIndex) => {
                     const childId = childEl.getAttribute('data-id');
                     order.push({ id: childId, parent_id: parentId, index: childIndex });
                  });
               });

               // Send to server
               fetch('{{ route("categories.reorder") }}', {
                  method: 'PATCH',
                  headers: {
                     'Content-Type': 'application/json',
                     'X-CSRF-TOKEN': '{{ csrf_token() }}'
                  },
                  body: JSON.stringify({ order: order })
               })
                  .then(response => response.json())
                  .then(data => {
                     if (data.success) {
                        // Optional: Show toast
                        console.log('Order saved');
                     }
                  });
            }
         });
      </script>
   @endpush
</x-app-layout>