<x-app-layout>
   <x-slot name="header">
      {{ __('Tạo Ngân sách Mới') }}
   </x-slot>

   <div class="row justify-content-center">
      <div class="col-md-8">
         <div class="card card-primary">
            <div class="card-header">
               <h3 class="card-title">{{ __('Thông tin Ngân sách') }}</h3>
            </div>
            <form action="{{ route('budgets.store') }}" method="POST">
               @csrf
               <div class="card-body">
                  <div class="form-group">
                     <label>{{ __('Danh mục Chi tiêu') }}</label>
                     <select name="category_id" class="form-control" required>
                        <option value="">{{ __('Chọn danh mục') }}</option>
                        @foreach($categories as $category)
                           <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                              {{ $category->name }}
                              @if($category->parent_id) ({{ $category->parent->name }}) @endif
                           </option>
                        @endforeach
                     </select>
                  </div>

                  <div class="form-group">
                     <label>{{ __('Số tiền Hạn mức') }}</label>
                     <div class="input-group">
                        <input type="number" name="amount" class="form-control font-weight-bold" placeholder="0" min="0"
                           required value="{{ old('amount') }}">
                        <div class="input-group-append">
                           <span class="input-group-text">VND</span>
                        </div>
                     </div>
                  </div>

                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label>{{ __('Ngày bắt đầu') }}</label>
                           <input type="date" name="start_date" class="form-control" required
                              value="{{ old('start_date', date('Y-m-01')) }}">
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label>{{ __('Ngày kết thúc') }}</label>
                           <input type="date" name="end_date" class="form-control" required
                              value="{{ old('end_date', date('Y-m-t')) }}">
                        </div>
                     </div>
                  </div>

                  <hr>
                  <h5><i class="fas fa-bell mr-2"></i>{{ __('Cài đặt Cảnh báo') }}</h5>

                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label>{{ __('Ngưỡng cảnh báo') }}</label>
                           <div class="input-group">
                              <input type="number" name="alert_threshold" class="form-control" min="50" max="100"
                                 value="{{ old('alert_threshold', 80) }}">
                              <div class="input-group-append">
                                 <span class="input-group-text">%</span>
                              </div>
                           </div>
                           <small class="text-muted">{{ __('Cảnh báo khi chi tiêu đạt ngưỡng này') }}</small>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label>{{ __('Bật cảnh báo') }}</label>
                           <div class="custom-control custom-switch mt-2">
                              <input type="hidden" name="alert_enabled" value="0">
                              <input type="checkbox" class="custom-control-input" id="alert_enabled"
                                 name="alert_enabled" value="1" {{ old('alert_enabled', true) ? 'checked' : '' }}>
                              <label class="custom-control-label" for="alert_enabled">
                                 {{ __('Nhận thông báo khi vượt ngưỡng') }}
                              </label>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>

               <div class="card-footer">
                  <button type="submit" class="btn btn-primary">{{ __('Lưu') }}</button>
                  <a href="{{ route('budgets.index') }}" class="btn btn-default float-right">{{ __('Hủy') }}</a>
               </div>
            </form>
         </div>
      </div>
   </div>
</x-app-layout>