@php $header = __('Tham gia Gia đình'); @endphp
<x-app-layout>
   <div class="row justify-content-center">
      <div class="col-md-5">
         <div class="card card-success">
            <div class="card-header">
               <h3 class="card-title"><i class="fas fa-sign-in-alt mr-2"></i>{{ __('Nhập mã mời') }}</h3>
            </div>
            <form action="{{ route('families.join') }}" method="POST">
               @csrf
               <div class="card-body">
                  <div class="form-group">
                     <label for="invite_code">{{ __('Mã mời (8 ký tự)') }}</label>
                     <input type="text" name="invite_code" id="invite_code"
                        class="form-control form-control-lg text-center text-uppercase @error('invite_code') is-invalid @enderror"
                        value="{{ old('invite_code') }}" maxlength="8" placeholder="ABCD1234"
                        style="letter-spacing: 3px; font-weight: bold;" required>
                     @error('invite_code')
                        <span class="invalid-feedback">{{ $message }}</span>
                     @enderror
                  </div>

                  <p class="text-muted text-center">
                     <small>{{ __('Liên hệ admin của gia đình để nhận mã mời') }}</small>
                  </p>
               </div>
               <div class="card-footer">
                  <button type="submit" class="btn btn-success btn-block">
                     <i class="fas fa-sign-in-alt mr-1"></i> {{ __('Tham gia') }}
                  </button>
               </div>
            </form>
         </div>

         <div class="text-center mt-3">
            <a href="{{ route('families.index') }}" class="text-muted">
               <i class="fas fa-arrow-left mr-1"></i> {{ __('Quay lại') }}
            </a>
         </div>
      </div>
   </div>
</x-app-layout>