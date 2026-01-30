<x-app-layout>
   <x-slot name="header">
      {{ __('Tạo Mục tiêu Tiết kiệm') }}
   </x-slot>

   <div class="row justify-content-center">
      <div class="col-md-8">
         <div class="card card-primary">
            <div class="card-header">
               <h3 class="card-title">{{ __('Thông tin Mục tiêu') }}</h3>
            </div>
            <form action="{{ route('savings_goals.store') }}" method="POST">
               @csrf
               <div class="card-body">
                  <div class="form-group">
                     <label>{{ __('Tên Mục tiêu') }}</label>
                     <input type="text" name="name" class="form-control" placeholder="VD: Mua xe máy, Du lịch Nhật Bản"
                        required value="{{ old('name') }}">
                  </div>

                  <div class="form-group">
                     <label>{{ __('Số tiền Mục tiêu') }}</label>
                     <div class="input-group">
                        <input type="number" name="target_amount" class="form-control font-weight-bold" placeholder="0"
                           min="0" required value="{{ old('target_amount') }}">
                        <div class="input-group-append">
                           <span class="input-group-text">VND</span>
                        </div>
                     </div>
                  </div>

                  <div class="form-group">
                     <label>{{ __('Ngày dự kiến hoàn thành') }} <small
                           class="text-muted">({{ __('Không bắt buộc') }})</small></label>
                     <input type="date" name="target_date" class="form-control" value="{{ old('target_date') }}">
                  </div>

                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label>{{ __('Màu sắc') }}</label>
                           <input type="color" name="color" class="form-control" value="{{ old('color', '#007bff') }}"
                              style="height: 40px">
                        </div>
                     </div>
                     <!-- Icon can be added later or simplified -->
                  </div>

                  <div class="form-group">
                     <label>{{ __('Mô tả') }}</label>
                     <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                  </div>
               </div>

               <div class="card-footer">
                  <button type="submit" class="btn btn-primary">{{ __('Lưu') }}</button>
                  <a href="{{ route('savings_goals.index') }}" class="btn btn-default float-right">{{ __('Hủy') }}</a>
               </div>
            </form>
         </div>
      </div>
   </div>
</x-app-layout>