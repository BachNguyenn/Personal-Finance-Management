@php $header = $family->name; @endphp
<x-app-layout>
    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <!-- Family Info Card -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-home mr-2"></i>{{ $family->name }}</h3>
                    @if($currentMember->isAdmin())
                        <div class="card-tools">
                            <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#inviteModal">
                                <i class="fas fa-share-alt"></i> {{ __('Mã mời') }}
                            </button>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-crown text-warning mr-1"></i> {{ __('Người tạo') }}:</strong> {{ $family->creator->name }}</p>
                            <p><strong><i class="fas fa-users mr-1"></i> {{ __('Thành viên') }}:</strong> {{ $family->members->count() }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-wallet mr-1"></i> {{ __('Ví chung') }}:</strong> {{ $sharedWallets->count() }}</p>
                            <p><strong><i class="fas fa-user-tag mr-1"></i> {{ __('Vai trò của bạn') }}:</strong> 
                                <span class="badge badge-{{ $currentMember->role === 'admin' ? 'primary' : ($currentMember->role === 'member' ? 'success' : 'secondary') }}">
                                    {{ ucfirst($currentMember->role) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Members Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-users mr-2"></i>{{ __('Thành viên') }}</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>{{ __('Tên') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Vai trò') }}</th>
                                @if($currentMember->isAdmin())
                                    <th class="text-center">{{ __('Thao tác') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($family->members as $member)
                                <tr>
                                    <td>
                                        <i class="fas fa-user-circle mr-1 text-muted"></i>
                                        {{ $member->user->name }}
                                        @if($member->user_id === $family->created_by)
                                            <i class="fas fa-crown text-warning ml-1" title="{{ __('Creator') }}"></i>
                                        @endif
                                    </td>
                                    <td>{{ $member->user->email }}</td>
                                    <td>
                                        @if($currentMember->isAdmin() && $member->user_id !== Auth::id())
                                            <form action="{{ route('families.update-role', [$family, $member]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <select name="role" class="form-control form-control-sm" onchange="this.form.submit()" style="width: auto;">
                                                    <option value="admin" {{ $member->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                                    <option value="member" {{ $member->role === 'member' ? 'selected' : '' }}>Member</option>
                                                    <option value="viewer" {{ $member->role === 'viewer' ? 'selected' : '' }}>Viewer</option>
                                                </select>
                                            </form>
                                        @else
                                            <span class="badge badge-{{ $member->role === 'admin' ? 'primary' : ($member->role === 'member' ? 'success' : 'secondary') }}">
                                                {{ ucfirst($member->role) }}
                                            </span>
                                        @endif
                                    </td>
                                    @if($currentMember->isAdmin())
                                        <td class="text-center">
                                            @if($member->user_id !== Auth::id())
                                                <form action="{{ route('families.remove-member', [$family, $member]) }}" method="POST" 
                                                    onsubmit="return confirm('{{ __('Xác nhận xóa thành viên này?') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-user-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Shared Wallets -->
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-wallet mr-2"></i>{{ __('Ví chung') }}</h3>
                </div>
                <div class="card-body">
                    @if($sharedWallets->isEmpty())
                        <p class="text-muted text-center">{{ __('Chưa có ví chung') }}</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($sharedWallets as $wallet)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <i class="fas fa-wallet mr-1 text-success"></i>
                                        {{ $wallet->name }}
                                        <br>
                                        <small class="text-muted">{{ __('Chủ') }}: {{ $wallet->user->name }}</small>
                                    </div>
                                    @if($wallet->user_id === Auth::id() || $currentMember->isAdmin())
                                        <form action="{{ route('families.unshare-wallet', [$family, $wallet]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-secondary" title="{{ __('Hủy chia sẻ') }}">
                                                <i class="fas fa-unlink"></i>
                                            </button>
                                        </form>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                
                @if($personalWallets->isNotEmpty() && $currentMember->canAddTransactions())
                    <div class="card-footer">
                        <form action="{{ route('families.share-wallet', $family) }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <select name="wallet_id" class="form-control form-control-sm">
                                    @foreach($personalWallets as $wallet)
                                        <option value="{{ $wallet->id }}">{{ $wallet->name }}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fas fa-share"></i> {{ __('Chia sẻ') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('families.report', $family) }}" class="btn btn-info btn-block mb-2">
                        <i class="fas fa-chart-bar mr-1"></i> {{ __('Báo cáo chi tiêu') }}
                    </a>
                    
                    @if(!($currentMember->isAdmin() && $family->members()->where('role', 'admin')->count() <= 1))
                        <form action="{{ route('families.leave', $family) }}" method="POST" 
                            onsubmit="return confirm('{{ __('Bạn có chắc muốn rời khỏi gia đình này?') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-block">
                                <i class="fas fa-sign-out-alt mr-1"></i> {{ __('Rời khỏi gia đình') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Invite Code Modal -->
    @if($currentMember->isAdmin())
        <div class="modal fade" id="inviteModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-share-alt mr-2"></i>{{ __('Mã mời gia đình') }}</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body text-center">
                        <p>{{ __('Chia sẻ mã này để mời thành viên mới:') }}</p>
                        <h2 class="display-4 text-primary font-weight-bold" style="letter-spacing: 5px;">
                            {{ $family->invite_code }}
                        </h2>
                        <button type="button" class="btn btn-outline-primary mt-3" onclick="copyInviteCode()">
                            <i class="fas fa-copy mr-1"></i> {{ __('Sao chép') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @push('scripts')
        <script>
            function copyInviteCode() {
                navigator.clipboard.writeText('{{ $family->invite_code }}');
                alert('{{ __("Đã sao chép mã mời!") }}');
            }
        </script>
    @endpush
</x-app-layout>
