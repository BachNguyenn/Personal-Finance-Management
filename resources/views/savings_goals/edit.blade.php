<x-app-layout>
   <x-slot name="header">
      {{ __('Sửa Mục tiêu') }}
   </x-slot>

   <div class="row justify-content-center">
      <div class="col-md-8">
         <div class="card card-primary">
            <div class="card-header">
               <h3 class="card-title">{{ __('Thông tin Mục tiêu') }}</h3>
            </div>
            <form action="{{ route('savings_goals.update', $savings_goal) }}" method="POST">
               @csrf @method('PUT')
               <div class="card-body">
                  <div class="form-group">
                     <label>{{ __('Tên Mục tiêu') }}</label>
                     <input type="text" name="name" class="form-control" required
                        value="{{ old('name', $savings_goal->name) }}">
                  </div>

                  <div class="form-group">
                     <label>{{ __('Số tiền Mục tiêu') }}</label>
                     <div class="input-group">
                        <input type="number" name="target_amount" class="form-control font-weight-bold" placeholder="0"
                           min="0" required value="{{ old('target_amount', $savings_goal->target_amount) }}">
                        <div class="input-group-append">
                           <span class="input-group-text">VND</span>
                        </div>
                     </div>
                  </div>

                  <div class="form-group">
                     <label>{{ __('Ngày dự kiến hoàn thành') }}</label>
                     <input type="date" name="target_date" class="form-control"
                        value="{{ old('target_date', $savings_goal->target_date ? $savings_goal->target_date->format('Y-m-d') : '') }}">
                  </div>

                  <div class="form-group">
                     <label>{{ __('Trạng thái') }}</label>
                     <select name="status" class="form-control">
                        <option value="active" {{ $savings_goal->status == 'active' ? 'selected' : '' }}>
                           {{ __('Đang thực hiện') }}</option>
                        <option value="completed" {{ $savings_goal->status == 'completed' ? 'selected' : '' }}>
                           {{ __('Hoàn thành') }}</option>
                        <option value="cancelled" {{ $savings_goal->status == 'cancelled' ? 'selected' : '' }}>
                           {{ __('Đã hủy') }}</option>
                     </select>
                  </div>

                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label>{{ __('Màu sắc') }}</label>
                           <input type="color" name="color" class="form-control"
                              value="{{ old('color', $savings_goal->color) }}" style="height: 40px">
                        </div>
                     </div>
                  </div>

                  <div class="form-group">
                     <label>{{ __('Mô tả') }}</label>
                     <textarea name="description" class="form-control"
                        rows="3">{{ old('description', $savings_goal->description) }}</textarea>
                  </div>
               </div>

               <div class="card-footer">
                  <button type="submit" class="btn btn-primary">{{ __('Cập nhật') }}</button>
                  <a href="{{ route('savings_goals.index') }}" class="btn btn-default float-right">{{ __('Hủy') }}</a>
               </div>
            </form>
         </div>
      </div>
   </div>
</x-app-layout>