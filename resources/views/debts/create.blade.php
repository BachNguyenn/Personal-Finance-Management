@php 
    $header = $type === 'lend' ? __('Thêm khoản cho vay') : __('Thêm khoản đi vay'); 
@endphp
<x-app-layout>
   <div class="row justify-content-center">
      <div class="col-md-8">
         <div class="card">
            <div class="card-header">
               <h3 class="card-title">
                  @if($type === 'lend')
                     <i class="fas fa-arrow-up text-success mr-2"></i>{{ __('Cho vay tiền') }}
                  @else
                     <i class="fas fa-arrow-down text-danger mr-2"></i>{{ __('Đi vay tiền') }}
                  @endif
               </h3>
            </div>
            <form action="{{ route('debts.store') }}" method="POST">
               @csrf
               <input type="hidden" name="type" value="{{ $type }}">

               <div class="card-body">
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="person_name">
                              {{ $type === 'lend' ? __('Người vay') : __('Người cho vay') }} <span
                                 class="text-danger">*</span>
                           </label>
                           <input type="text" class="form-control @error('person_name') is-invalid @enderror"
                              id="person_name" name="person_name" value="{{ old('person_name') }}"
                              placeholder="{{ __('Nhập tên...') }}" required>
                           @error('person_name')
                              <div class="invalid-feedback">{{ $message }}</div>
                           @enderror
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="phone">{{ __('Số điện thoại') }}</label>
                           <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                              name="phone" value="{{ old('phone') }}" placeholder="0912 345 678">
                           @error('phone')
                              <div class="invalid-feedback">{{ $message }}</div>
                           @enderror
                        </div>
                     </div>
                  </div>

                  <div class="form-group">
                     <label for="amount">{{ __('Số tiền') }} <span class="text-danger">*</span></label>
                     <div class="input-group">
                        <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount"
                           name="amount" value="{{ old('amount') }}" min="1" step="1000" placeholder="0" required>
                        <div class="input-group-append">
                           <span class="input-group-text">₫</span>
                        </div>
                        @error('amount')
                           <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                     </div>
                  </div>

                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="debt_date">{{ __('Ngày vay') }} <span class="text-danger">*</span></label>
                           <input type="date" class="form-control @error('debt_date') is-invalid @enderror"
                              id="debt_date" name="debt_date" value="{{ old('debt_date', date('Y-m-d')) }}" required>
                           @error('debt_date')
                              <div class="invalid-feedback">{{ $message }}</div>
                           @enderror
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="due_date">{{ __('Hạn trả') }} <small
                                 class="text-muted">({{ __('tuỳ chọn') }})</small></label>
                           <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date"
                              name="due_date" value="{{ old('due_date') }}">
                           @error('due_date')
                              <div class="invalid-feedback">{{ $message }}</div>
                           @enderror
                        </div>
                     </div>
                  </div>

                  <div class="form-group">
                     <label for="description">{{ __('Lý do / Ghi chú') }}</label>
                     <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                        name="description" rows="2"
                        placeholder="{{ __('Mô tả lý do vay/cho vay...') }}">{{ old('description') }}</textarea>
                     @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>
               </div>

               <div class="card-footer">
                  <a href="{{ route('debts.index', ['type' => $type]) }}" class="btn btn-secondary">
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