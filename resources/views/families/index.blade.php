@php $header = __('Quản lý Gia đình'); @endphp
<x-app-layout>
   <div class="row">
      <div class="col-md-8">
         <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
               <h3 class="card-title"><i class="fas fa-users mr-2"></i>{{ __('Gia đình của tôi') }}</h3>
               <div>
                  <a href="{{ route('families.create') }}" class="btn btn-primary btn-sm">
                     <i class="fas fa-plus"></i> {{ __('Tạo gia đình') }}
                  </a>
                  <a href="{{ route('families.join-form') }}" class="btn btn-outline-success btn-sm">
                     <i class="fas fa-sign-in-alt"></i> {{ __('Tham gia') }}
                  </a>
               </div>
            </div>
            <div class="card-body">
               @if($families->isEmpty())
                  <div class="text-center py-5 text-muted">
                     <i class="fas fa-home fa-4x mb-3"></i>
                     <h5>{{ __('Chưa có gia đình nào') }}</h5>
                     <p>{{ __('Tạo gia đình mới hoặc tham gia bằng mã mời') }}</p>
                     <a href="{{ route('families.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> {{ __('Tạo gia đình đầu tiên') }}
                     </a>
                  </div>
               @else
                  <div class="row">
                     @foreach($families as $family)
                        <div class="col-md-6 mb-3">
                           <div class="card card-outline card-primary h-100">
                              <div class="card-header">
                                 <h5 class="card-title mb-0">
                                    <i class="fas fa-home mr-2"></i>{{ $family->name }}
                                 </h5>
                              </div>
                              <div class="card-body">
                                 <p class="mb-2">
                                    <i class="fas fa-users mr-1"></i>
                                    <strong>{{ $family->members->count() }}</strong> {{ __('thành viên') }}
                                 </p>
                                 <p class="mb-2">
                                    <i class="fas fa-crown mr-1 text-warning"></i>
                                    {{ $family->creator->name }}
                                 </p>
                                 <p class="mb-0">
                                    <small class="text-muted">
                                       {{ __('Vai trò của bạn') }}:
                                       <span
                                          class="badge badge-{{ $family->getMemberRole(Auth::id()) === 'admin' ? 'primary' : ($family->getMemberRole(Auth::id()) === 'member' ? 'success' : 'secondary') }}">
                                          {{ ucfirst($family->getMemberRole(Auth::id())) }}
                                       </span>
                                    </small>
                                 </p>
                              </div>
                              <div class="card-footer">
                                 <a href="{{ route('families.show', $family) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> {{ __('Xem chi tiết') }}
                                 </a>
                                 <a href="{{ route('families.report', $family) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-chart-bar"></i> {{ __('Báo cáo') }}
                                 </a>
                              </div>
                           </div>
                        </div>
                     @endforeach
                  </div>
               @endif
            </div>
         </div>
      </div>

      <div class="col-md-4">
         <div class="card card-outline card-success">
            <div class="card-header">
               <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i>{{ __('Hướng dẫn') }}</h3>
            </div>
            <div class="card-body">
               <h6><i class="fas fa-home mr-1"></i> {{ __('Gia đình là gì?') }}</h6>
               <p class="text-muted small">
                  {{ __('Gia đình cho phép nhiều người cùng quản lý tài chính chung. Chia sẻ ví và theo dõi chi tiêu của từng thành viên.') }}
               </p>

               <h6><i class="fas fa-user-shield mr-1"></i> {{ __('Vai trò') }}</h6>
               <ul class="list-unstyled small">
                  <li><span class="badge badge-primary">Admin</span> - {{ __('Quản lý toàn bộ') }}</li>
                  <li><span class="badge badge-success">Member</span> - {{ __('Xem & thêm giao dịch') }}</li>
                  <li><span class="badge badge-secondary">Viewer</span> - {{ __('Chỉ xem') }}</li>
               </ul>
            </div>
         </div>
      </div>
   </div>
</x-app-layout>