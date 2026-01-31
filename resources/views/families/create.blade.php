@php $header = __('Tạo Gia đình Mới'); @endphp
<x-app-layout>
   <div class="row justify-content-center">
      <div class="col-md-6">
         <div class="card card-primary">
            <div class="card-header">
               <h3 class="card-title"><i class="fas fa-home mr-2"></i>{{ __('Thông tin Gia đình') }}</h3>
            </div>
            <form action="{{ route('families.store') }}" method="POST">
               @csrf
               <div class="card-body">
                  <div class="form-group">
                     <label for="name">{{ __('Tên gia đình') }}</label>
                     <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" placeholder="{{ __('VD: Gia đình Nguyễn Văn A') }}" required>
                     @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                     @enderror
                  </div>

                  <div class="alert alert-info">
                     <i class="fas fa-info-circle mr-2"></i>
                     {{ __('Sau khi tạo, bạn sẽ nhận được mã mời để chia sẻ với các thành viên khác.') }}
                  </div>
               </div>
               <div class="card-footer">
                  <button type="submit" class="btn btn-primary">
                     <i class="fas fa-check mr-1"></i> {{ __('Tạo gia đình') }}
                  </button>
                  <a href="{{ route('families.index') }}" class="btn btn-default float-right">
                     {{ __('Hủy') }}
                  </a>
               </div>
            </form>
         </div>
      </div>
   </div>
</x-app-layout>