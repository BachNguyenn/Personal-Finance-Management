<x-app-layout>
   <x-slot name="header">{{ __('Edit Category') }}</x-slot>

   <div class="row justify-content-center">
      <div class="col-md-6">
         <div class="card card-secondary">
            <div class="card-header">
               <h3 class="card-title">{{ __('Edit Category') }}: {{ $category->name }}</h3>
            </div>
            <form action="{{ route('categories.update', $category) }}" method="POST">
               @csrf @method('PUT')

               <div class="card-body">
                  <div class="form-group">
                     <label>{{ __('Category Name') }} <span class="text-danger">*</span></label>
                     <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}"
                        required>
                  </div>
                  <div class="form-group">
                     <label>{{ __('Transaction Type') }} ({{ __('Cannot be changed') }})</label>
                     <div>
                        @if($category->type == 'income')
                           <span class="badge badge-success">{{ __('Income') }}</span>
                        @else
                           <span class="badge badge-danger">{{ __('Expense') }}</span>
                        @endif
                     </div>
                  </div>

                  @if($parentCategories->count() > 0)
                     <div class="form-group">
                        <label>{{ __('Parent Category') }}</label>
                        <select name="parent_id" class="form-control">
                           <option value="">{{ __('None (Top Level)') }}</option>
                           @foreach($parentCategories as $parent)
                              <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                 {{ $parent->name }}
                              </option>
                           @endforeach
                        </select>
                     </div>
                  @endif

                  <div class="form-group">
                     <label>{{ __('Keywords') }} <small
                           class="text-muted">({{ __('Separated by commas') }})</small></label>
                     <input type="text" name="keywords" class="form-control"
                        value="{{ old('keywords', implode(', ', $category->keywords ?? [])) }}"
                        placeholder="e.g. Grab, Uber, Salary, Rent">
                  </div>

                  <div class="form-group">
                     <label>{{ __('Color') }}</label>
                     <input type="color" name="color" class="form-control" value="{{ old('color', $category->color) }}"
                        style="height: 40px">
                  </div>

                  <div class="form-group">
                     <label>{{ __('Select Icon') }}</label>
                     <div class="d-flex flex-wrap" style="gap: 10px">
                        @php
                           $icons = [
                              'fas fa-utensils',
                              'fas fa-shopping-cart',
                              'fas fa-car',
                              'fas fa-home',
                              'fas fa-heartbeat',
                              'fas fa-graduation-cap',
                              'fas fa-gamepad',
                              'fas fa-gift',
                              'fas fa-money-bill-wave',
                              'fas fa-briefcase',
                              'fas fa-plane',
                              'fas fa-mobile-alt',
                              'fas fa-bolt',
                              'fas fa-tint',
                              'fas fa-tshirt',
                              'fas fa-dog'
                           ];
                          @endphp
                        @foreach($icons as $icon)
                           <div class="icheck-primary d-inline">
                              <input type="radio" id="icon_{{ $loop->index }}" name="icon" value="{{ $icon }}" {{ old('icon', $category->icon) == $icon ? 'checked' : '' }}>
                              <label for="icon_{{ $loop->index }}" class="border rounded p-2 text-center text-muted">
                                 <i class="{{ $icon }} fa-lg"></i>
                              </label>
                           </div>
                        @endforeach
                     </div>
                  </div>
               </div>
               <div class="card-footer">
                  <button type="submit" class="btn btn-secondary">{{ __('Save') }}</button>
                  <a href="{{ route('categories.index') }}" class="btn btn-default float-right">{{ __('Cancel') }}</a>
               </div>
            </form>
         </div>
      </div>
   </div>
</x-app-layout>