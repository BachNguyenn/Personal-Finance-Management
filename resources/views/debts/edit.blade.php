@php $header = __('Chỉnh sửa khoản nợ'); @endphp
<x-app-layout>
   <div class="row justify-content-center">
      <div class="col-md-8">
         <div class="card">
            <div class="card-header">
               <h3 class="card-title">
                  <i class="fas fa-edit mr-2"></i>{{ __('Chỉnh sửa thông tin') }}
               </h3>
            </div>
            <form action="{{ route('debts.update', $debt) }}" method="POST">
               @csrf
               @method('PUT')

               <div class="card-body">
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="person_name">{{ __('Người liên quan') }} <span
                                 class="text-danger">*</span></label>
                           <input type="text" class="form-control @error('person_name') is-invalid @enderror"
                              id="person_name" name="person_name" value="{{ old('person_name', $debt->person_name) }}"
                              required>
                           @error('person_name')
                              <div class="invalid-feedback">{{ $message }}</div>
                           @enderror
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="phone">{{ __('Số điện thoại') }}</label>
                           <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                              name="phone" value="{{ old('phone', $debt->phone) }}">
                           @error('phone')
                              <div class="invalid-feedback">{{ $message }}</div>
                           @enderror
                        </div>
                     </div>
                  </div>

                  <div class="form-group">
                     <label for="due_date">{{ __('Hạn trả') }}</label>
                     <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date"
                        name="due_date" value="{{ old('due_date', $debt->due_date?->format('Y-m-d')) }}">
                     @error('due_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>

                  <div class="form-group">
                     <label for="description">{{ __('Ghi chú') }}</label>
                     <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                        name="description" rows="2">{{ old('description', $debt->description) }}</textarea>
                     @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>

                  <div class="alert alert-info">
                     <i class="fas fa-info-circle mr-1"></i>
                     {{ __('Số tiền gốc và ngày vay không thể thay đổi sau khi tạo.') }}
                  </div>
               </div>

               <div class="card-footer">
                  <a href="{{ route('debts.show', $debt) }}" class="btn btn-secondary">
                     <i class="fas fa-arrow-left"></i> {{ __('Quay lại') }}
                  </a>
                  <button type="submit" class="btn btn-primary float-right">
                     <i class="fas fa-save"></i> {{ __('Lưu') }}
                  </button>
               </div>
            </form>
         </div>
      </div>
   </div>
</x-app-layout>