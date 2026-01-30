<x-app-layout>
    <x-slot name="header">
        {{ __('Wallets') }}
    </x-slot>

    <div class="row mb-3">
        <div class="col-12 text-right">
            <a href="{{ route('wallets.create') }}" class="btn btn-primary">
                <i class="fas fa-wallet mr-1"></i> {{ __('Create New Wallet') }}
            </a>
        </div>
    </div>

    <div class="row">
        @forelse($wallets as $wallet)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="small-box" style="background-color: {{ $wallet->color }}20; color: #333">
                    <div class="inner">
                        <h3>{{ number_format($wallet->balance, 0, ',', '.') }} <small
                                style="font-size: 0.5em">{{ $wallet->currency }}</small></h3>
                        <p class="font-weight-bold">{{ $wallet->name }}</p>
                        <p class="mb-0 text-muted">{{ ucfirst($wallet->type) }}</p>
                    </div>
                    <div class="icon">
                        <i class="{{ $wallet->icon }}" style="color: {{ $wallet->color }}; opacity: 0.3"></i>
                    </div>
                    <div class="small-box-footer d-flex justify-content-between align-items-center px-3 py-2"
                        style="background-color: rgba(0,0,0,0.05)">
                        @if($wallet->is_default)
                            <span class="badge badge-primary">Default</span>
                        @else
                            <span></span>
                        @endif

                        <div>
                            <a href="{{ route('wallets.edit', $wallet) }}" class="text-dark mr-2" title="{{ __('Edit') }}"><i
                                    class="fas fa-edit"></i></a>
                            <a href="#" class="text-danger" title="{{ __('Delete') }}"
                                onclick="if(confirm('Xóa ví này?')) document.getElementById('del-wallet-{{ $wallet->id }}').submit()">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                        <form id="del-wallet-{{ $wallet->id }}" action="{{ route('wallets.destroy', $wallet) }}"
                            method="POST" class="d-none">
                            @csrf @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Chưa có ví nào. Hãy tạo ví để bắt đầu.</p>
            </div>
        @endforelse
    </div>
</x-app-layout>