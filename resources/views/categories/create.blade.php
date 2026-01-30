<x-app-layout>
   <x-slot name="header">{{ __('Create New Category') }}</x-slot>

   <div class="row justify-content-center">
      <div class="col-md-6">
         <div class="card card-secondary">
            <div class="card-header">
               <h3 class="card-title">{{ __('General Information') }}</h3>
            </div>
            <form action="{{ route('categories.store') }}" method="POST">
               @csrf
               <div class="card-body">
                  <div class="form-group">
                     <label>{{ __('Category Name') }} <span class="text-danger">*</span></label>
                     <input type="text" name="name" class="form-control" placeholder="VD: Ăn uống, Lương..." required
                        value="{{ old('name') }}">
                  </div>
                  <div class="form-group">
                     <label>{{ __('Transaction Type') }}</label>
                     <div class="d-flex">
                        <div class="custom-control custom-radio mr-4">
                           <input class="custom-control-input" type="radio" id="typeIncome" name="type" value="income"
                              {{ old('type') == 'income' ? 'checked' : '' }}>
                           <label for="typeIncome" class="custom-control-label text-success">{{ __('Income') }}</label>
                        </div>
                        <div class="custom-control custom-radio">
                           <input class="custom-control-input" type="radio" id="typeExpense" name="type" value="expense"
                              {{ old('type', 'expense') == 'expense' ? 'checked' : '' }}>
                           <label for="typeExpense" class="custom-control-label text-danger">{{ __('Expense') }}</label>
                        </div>
                     </div>
                  </div>

                  @if($parentCategories->count() > 0)
                     <div class="form-group">
                        <label>{{ __('Parent Category') }}</label>
                        <select name="parent_id" class="form-control">
                           <option value="">{{ __('None (Top Level)') }}</option>
                           @foreach($parentCategories as $parent)
                              <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                 {{ $parent->name }} ({{ $parent->type == 'income' ? __('Income') : __('Expense') }})
                              </option>
                           @endforeach
                        </select>
                     </div>
                  @endif

                  <div class="form-group">
                     <label>{{ __('Keywords') }} <small
                           class="text-muted">({{ __('Separated by commas') }})</small></label>
                     <input type="text" name="keywords" class="form-control" value="{{ old('keywords') }}"
                        placeholder="e.g. Grab, Uber, Salary, Rent">
                     <small
                        class="form-text text-muted">{{ __('Used for auto-categorization of transactions.') }}</small>
                  </div>

                  <div class="form-group">
                     <label>{{ __('Color') }}</label>
                     <input type="color" name="color" class="form-control" value="{{ old('color', '#6c757d') }}"
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
                              <input type="radio" id="icon_{{ $loop->index }}" name="icon" value="{{ $icon }}" {{ old('icon', 'fas fa-tag') == $icon ? 'checked' : '' }}>
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