<x-app-layout>
    @section('title')
        Profile
    @endsection

    <div class="container-fluid profile-wrapper">
        <!-- Banner and Profile Header Card -->
        <div class="profile-banner-card">
            <!-- Cover Banner Box -->
            <div id="profile-cover-box" class="profile-cover-box" style="{{ Auth::user()->banner ? 'background-image: url(' . asset('storage/uploads/user_banners/' . Auth::user()->banner) . ');' : '' }}">
                <!-- Change Cover Button (Triggers preview, saves on Save Profile Information click) -->
                <button type="button" class="profile-change-cover-btn" onclick="document.getElementById('banner-file-input').click()">
                    <i class="fa fa-camera me-1"></i> {{ __('Change Cover') }}
                </button>
            </div>

            <!-- Profile Info Bar (Avatar overlaps cover banner; Name & Roles sit on white background below banner) -->
            <div class="profile-info-bar">
                <div class="profile-avatar-frame">
                    @if(Auth::user()->avatar)
                        <img id="avatar-display" src="{{ asset('storage/uploads/user_avatars/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}">
                    @else
                        <img id="avatar-display" src="{{ asset('assets/images/logo/sinarmeadow.png') }}" alt="{{ Auth::user()->name }}" class="p-2 bg-dark">
                    @endif
                </div>

                <div class="profile-details-content">
                    <div class="profile-name-row">
                        <h3 class="profile-user-name">{{ Auth::user()->name }}</h3>
                        @if(method_exists(Auth::user(), 'getRoleNames') && count(Auth::user()->getRoleNames()) > 0)
                            @foreach(Auth::user()->getRoleNames() as $role)
                                <span class="profile-role-pill"><i class="fa fa-shield-alt"></i> {{ strtoupper($role) }}</span>
                            @endforeach
                        @else
                            <span class="profile-role-pill"><i class="fa fa-shield-alt"></i> USER</span>
                        @endif
                    </div>

                    <div class="profile-meta-text">
                        NIK: <strong>{{ Auth::user()->nik ?? '-' }}</strong> &nbsp;•&nbsp; Username: <strong>{{ Auth::user()->username ? ('@' . Auth::user()->username) : ('@' . strtolower(str_replace(' ', '', Auth::user()->name))) }}</strong>
                    </div>

                    <div class="profile-badges-row">
                        <div class="profile-info-pill">
                            <i class="fa fa-envelope text-danger"></i>
                            <span>{{ Auth::user()->email }}</span>
                        </div>
                        <div class="profile-info-pill">
                            <i class="fa fa-briefcase text-primary"></i>
                            <span>{{ Auth::user()->position->name ?? 'Developer' }}</span>
                        </div>
                        <div class="profile-info-pill">
                            <i class="fa fa-bookmark text-success"></i>
                            <span>{{ Auth::user()->department->name ?? 'Finance Admin' }}</span>
                        </div>
                        <div class="profile-info-pill">
                            <i class="fa fa-user-tie text-warning"></i>
                            <span>Supervisor: {{ Auth::user()->atasan->name ?? (Auth::user()->manager->name ?? 'Andika Suhendar') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Information and Password Update Cards -->
        <div class="row g-4">
            <!-- Profile Information Card -->
            <div class="col-lg-6">
                <div class="profile-section-card">
                    <div class="profile-card-header">
                        <div class="profile-header-icon blue">
                            <i class="fa fa-user-edit"></i>
                        </div>
                        <div>
                            <h5 class="profile-card-title">{{ __('Profile Information') }}</h5>
                            <p class="profile-card-subtitle">{{ __('Update your profile information and account email address.') }}</p>
                        </div>
                    </div>

                    <form id="profile-main-form" method="post" action="{{ route('profile.updates') }}" enctype="multipart/form-data">
                        @csrf
                        @method('patch')

                        <!-- Hidden Banner File Input (Triggered by Change Cover button) -->
                        <input type="file" id="banner-file-input" name="banner" class="d-none" accept="image/*" onchange="previewBannerCover(event)">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small text-dark">{{ __('NIK') }}</label>
                                <input type="text" name="nik" class="form-control" value="{{ old('nik', $user->nik) }}" readonly style="background-color: #f8fafc; border-color: #cbd5e1; border-radius: 8px; height: 42px; font-weight: 500;">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small text-dark">{{ __('Name') }}</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required style="border-color: #cbd5e1; border-radius: 8px; height: 42px; font-weight: 500;">
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold small text-dark">{{ __('Email') }}</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required style="border-color: #cbd5e1; border-radius: 8px; height: 42px; font-weight: 500;">
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-4">
                                <label class="form-label fw-bold small text-dark">{{ __('Profile Photo / Avatar') }}</label>
                                <div class="d-flex align-items-center gap-3 mt-1">
                                    <img id="avatar-preview-small" src="{{ Auth::user()->avatar ? asset('storage/uploads/user_avatars/' . Auth::user()->avatar) : asset('assets/images/logo/sinarmeadow.png') }}" class="rounded-circle" style="width: 36px; height: 36px; object-fit: cover; border: 1px solid #cbd5e1;">
                                    <label for="avatar-file-input" class="profile-btn-dark mb-0 py-2 px-3" style="cursor: pointer;">
                                        {{ __('Choose File') }}
                                    </label>
                                    <input type="file" id="avatar-file-input" name="avatar" class="d-none" accept="image/*" onchange="updateAvatarName(event)">
                                    <span id="avatar-file-name" class="text-muted small">No file chosen</span>
                                </div>
                                <div class="text-muted mt-2" style="font-size: 11px;">
                                    Format: JPEG, PNG, JPG, GIF, SVG, WEBP (Max: 2MB)
                                </div>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="profile-btn-dark">
                                    {{ __('Save Profile Information') }}
                                </button>
                                @if (session('status') === 'profile-updated')
                                    <div class="alert alert-success alert-dismissible fade show mt-3 mb-0">
                                        {{ __('Profile updated successfully.') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Password Update Card -->
            <div class="col-lg-6">
                <div class="profile-section-card">
                    <div class="profile-card-header">
                        <div class="profile-header-icon pink">
                            <i class="fa fa-lock"></i>
                        </div>
                        <div>
                            <h5 class="profile-card-title">{{ __('Update Password') }}</h5>
                            <p class="profile-card-subtitle">{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>
                        </div>
                    </div>

                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold small text-dark">{{ __('Current Password') }}</label>
                                <div class="position-relative">
                                    <input type="password" id="current_password" name="current_password" class="form-control pe-5" placeholder="" style="border-color: #cbd5e1; border-radius: 8px; height: 42px; font-weight: 500;">
                                    <span class="position-absolute top-50 end-0 translate-middle-y me-3 text-secondary" style="cursor: pointer;" onclick="togglePasswordVisibility('current_password', 'current-eye-icon')">
                                        <i id="current-eye-icon" class="fa fa-eye-slash"></i>
                                    </span>
                                </div>
                                @error('current_password', 'updatePassword')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small text-dark">{{ __('New Password') }}</label>
                                <div class="position-relative">
                                    <input type="password" id="new_password" name="password" class="form-control pe-5" placeholder="" oninput="checkPasswordMatch()" style="border-color: #cbd5e1; border-radius: 8px; height: 42px; font-weight: 500;">
                                    <span class="position-absolute top-50 end-0 translate-middle-y me-3 text-secondary" style="cursor: pointer;" onclick="togglePasswordVisibility('new_password', 'new-eye-icon')">
                                        <i id="new-eye-icon" class="fa fa-eye-slash"></i>
                                    </span>
                                </div>
                                @error('password', 'updatePassword')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small text-dark">{{ __('Confirm Password') }}</label>
                                <div class="position-relative">
                                    <input type="password" id="confirm_password" name="password_confirmation" class="form-control pe-5" placeholder="" oninput="checkPasswordMatch()" style="border-color: #cbd5e1; border-radius: 8px; height: 42px; font-weight: 500;">
                                    <span class="position-absolute top-50 end-0 translate-middle-y me-3 text-secondary" style="cursor: pointer;" onclick="togglePasswordVisibility('confirm_password', 'confirm-eye-icon')">
                                        <i id="confirm-eye-icon" class="fa fa-eye-slash"></i>
                                    </span>
                                </div>
                                <div id="password-match-feedback" class="small mt-1" style="font-weight: 600; min-height: 20px;"></div>
                                @error('password_confirmation', 'updatePassword')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mt-2">
                                <button type="submit" class="profile-btn-dark">
                                    {{ __('Update Password') }}
                                </button>
                                @if (session('status') === 'password-updated')
                                    <div class="alert alert-success alert-dismissible fade show mt-3 mb-0">
                                        {{ __('Password updated successfully.') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewBannerCover(event) {
            const input = event.target;
            const coverBox = document.getElementById('profile-cover-box');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (coverBox) {
                        coverBox.style.backgroundImage = 'url(' + e.target.result + ')';
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function updateAvatarName(event) {
            const input = event.target;
            const fileNameSpan = document.getElementById('avatar-file-name');
            const previewSmall = document.getElementById('avatar-preview-small');
            const avatarDisplay = document.getElementById('avatar-display');

            if (input.files && input.files[0]) {
                fileNameSpan.textContent = input.files[0].name;
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (previewSmall) previewSmall.src = e.target.result;
                    if (avatarDisplay) avatarDisplay.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                fileNameSpan.textContent = 'No file chosen';
            }
        }

        function togglePasswordVisibility(fieldId, iconId) {
            const passwordInput = document.getElementById(fieldId);
            const eyeIcon = document.getElementById(iconId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            }
        }

        function checkPasswordMatch() {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const confirmInput = document.getElementById('confirm_password');
            const feedback = document.getElementById('password-match-feedback');

            if (!confirmPassword) {
                feedback.innerHTML = '';
                confirmInput.style.borderColor = '#cbd5e1';
                return;
            }

            if (newPassword === confirmPassword) {
                feedback.innerHTML = '<span class="text-success" style="color: #16a34a !important; font-size: 12px;"><i class="fa fa-check-circle me-1"></i> Password cocok</span>';
                confirmInput.style.borderColor = '#16a34a';
            } else {
                feedback.innerHTML = '                <span class="text-danger" style="color: #dc2626 !important; font-size: 12px;"><i class="fa fa-times-circle me-1"></i> Passwords do not match</span>';
                confirmInput.style.borderColor = '#dc2626';
            }
        }
    </script>
</x-app-layout>
