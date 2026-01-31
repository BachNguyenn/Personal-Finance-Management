<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container-fluid">
            <div class="row">
                <!-- Left Column: User Card -->
                <div class="col-md-3">
                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random"
                                    alt="User profile picture">
                            </div>

                            <h3 class="profile-username text-center">{{ Auth::user()->name }}</h3>

                            <p class="text-muted text-center">{{ Auth::user()->bio ?? 'Software Engineer' }}</p>

                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>{{ __('Joined') }}</b> <a
                                        class="float-right">{{ Auth::user()->created_at->format('M Y') }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>{{ __('Email') }}</b> <a class="float-right"
                                        style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ Auth::user()->email }}</a>
                                </li>
                            </ul>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                    <!-- About Me Box -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('About Me') }}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            @if(Auth::user()->education)
                                <strong><i class="fas fa-book mr-1"></i> {{ __('Education') }}</strong>
                                <p class="text-muted">
                                    {{ Auth::user()->education }}
                                </p>
                                <hr>
                            @endif

                            <strong><i class="fas fa-map-marker-alt mr-1"></i> {{ __('Location') }}</strong>
                            <p class="text-muted">{{ Auth::user()->address ?? __('Not set') }}</p>

                            <hr>

                            <strong><i class="far fa-file-alt mr-1"></i> {{ __('Bio') }}</strong>
                            <p class="text-muted">{{ Auth::user()->bio ?? __('No bio available.') }}</p>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->

                <!-- Right Column: Settings -->
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link active" href="#settings"
                                        data-toggle="tab">{{ __('Settings') }}</a></li>
                                <li class="nav-item"><a class="nav-link" href="#password"
                                        data-toggle="tab">{{ __('Change Password') }}</a></li>
                                <li class="nav-item"><a class="nav-link text-danger" href="#delete"
                                        data-toggle="tab">{{ __('Delete Account') }}</a></li>
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="active tab-pane" id="settings">
                                    @include('profile.partials.update-profile-information-form')
                                </div>
                                <!-- /.tab-pane -->

                                <div class="tab-pane" id="password">
                                    @include('profile.partials.update-password-form')
                                </div>
                                <!-- /.tab-pane -->

                                <div class="tab-pane" id="delete">
                                    @include('profile.partials.delete-user-form')
                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
</x-app-layout>