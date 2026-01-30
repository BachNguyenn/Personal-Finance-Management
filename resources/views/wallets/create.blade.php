<x-app-layout>
   <x-slot name="header">{{ __('Create New Wallet') }}</x-slot>

   <div class="row justify-content-center">
      <div class="col-md-6">
         <div class="card card-primary">
            <div class="card-header">
               <h3 class="card-title">{{ __('General Information') }}</h3>
            </div>
            <form action="{{ route('wallets.store') }}" method="POST">
               @csrf
               <div class="card-body">
                  <div class="form-group">
                     <label>{{ __('Wallet Name') }} <span class="text-danger">*</span></label>
                     <input type="text" name="name" class="form-control" placeholder="VD: Tiền mặt, VCB..." required>
                  </div>
                  <div class="form-group">
                     <label>{{ __('Wallet Type') }}</label>
                     <select name="type" class="form-control">
                        <option value="cash">{{ __('Cash') }}</option>
                        <option value="bank">{{ __('Bank Account') }}</option>
                        <option value="e-wallet">{{ __('E-Wallet') }}</option>
                        <option value="other">{{ __('Other') }}</option>
                     </select>
                  </div>
                  <div class="form-group">
                     <label>{{ __('Initial Balance') }}</label>
                     <div class="input-group">
                        <input type="number" name="balance" class="form-control" value="0">
                        <div class="input-group-append">
                           <span class="input-group-text">VND</span>
                        </div>
                     </div>
                  </div>
                  <div class="form-group">
                     <label>{{ __('Color') }}</label>
                     <input type="color" name="color" class="form-control" value="#007bff" style="height: 40px">
                  </div>
                  <div class="form-group mb-0">
                     <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="is_default" class="custom-control-input" id="is_default" value="1">
                        <label class="custom-control-label" for="is_default">{{ __('Set as Default Wallet') }}</label>
                     </div>
                  </div>
               </div>
               <div class="card-footer">
                  <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                  <a href="{{ route('wallets.index') }}" class="btn btn-default float-right">{{ __('Cancel') }}</a>
               </div>
            </form>
         </div>
      </div>
   </div>
</x-app-layout>