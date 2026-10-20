<header class="header-main">
    <div class="container-fluid">
        <div class="row">
            <div class="col-6 col-sm-4 d-flex align-items-center header-left p-0">
                <span class="header-toggle me-3">
                    <i class="iconoir-view-grid"></i>
                </span>
            </div>

            <div class="col-6 col-sm-8 d-flex align-items-center justify-content-end header-right p-0">

                <ul class="d-flex align-items-center">

                    {{-- <li class="header-cloud">
                        <a aria-controls="cloudoffcanvasTops" class="head-icon" data-bs-target="#cloudoffcanvasTops"
                            data-bs-toggle="offcanvas" href="#" role="button">
                            <i class="iconoir-dew-point text-primary f-s-26 me-1"></i>
                            <span id="current-temp" class="f-w-600">-- <sup class="f-s-10">°C</sup></span>
                        </a>

                        <div aria-labelledby="cloudoffcanvasTops" class="offcanvas offcanvas-end header-cloud-canvas"
                            id="cloudoffcanvasTops" tabindex="-1">
                            <div class="offcanvas-body p-0">
                                <div class="cloud-body">
                                    <div id="forecast-box" class="cloud-content-box">
                                        <!-- isi forecast akan di-generate JS -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li> --}}



                    {{-- <li class="header-dark">
                        <div class="sun-logo head-icon">
                            <i class="iconoir-sun-light"></i>
                        </div>
                        <div class="moon-logo head-icon">
                            <i class="iconoir-half-moon"></i>
                        </div>
                    </li> --}}

                    <li class="header-notification">
                        <a aria-controls="notificationcanvasRight" class="d-block head-icon position-relative"
                            data-bs-target="#notificationcanvasRight" data-bs-toggle="offcanvas" href="#"
                            role="button">
                            <i class="iconoir-bell"></i>
                            {{-- Badge untuk jumlah notifikasi --}}
                            <span id="notification-badge" class="position-absolute top-0 start-100 translate-middle bg-danger" style="display:none; width:22px; height:22px; min-width:22px; padding: 4px; font-size:11px; font-weight:700; line-height: 1; color:#fff; display:flex; align-items:center; justify-content:center; border-radius:50%; border:2px solid #fff;">
                                0
                            </span>
                        </a>
                        <div aria-labelledby="notificationcanvasRightLabel"
                            class="offcanvas offcanvas-end header-notification-canvas" id="notificationcanvasRight"
                            tabindex="-1">
                            <div class="offcanvas-header border-bottom py-3 px-4">
                                <h5 class="offcanvas-title fw-bold" id="notificationcanvasRightLabel"><i class="fa fa-bell text-primary me-2"></i>Notifications</h5>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" id="mark-all-read-btn" class="btn btn-sm btn-light-success text-success p-1 px-2" title="Mark All as Read" style="font-size: 11px; font-weight: 600;">
                                        <i class="fa fa-check-double me-1"></i>Mark All
                                    </button>
                                    <button type="button" id="clear-all-notifications" class="btn btn-sm btn-light-danger text-danger p-1 px-2" title="Clear All" style="font-size: 11px; font-weight: 600;">
                                        <i class="fa fa-trash me-1"></i>Clear
                                    </button>
                                    <button aria-label="Close" class="btn-close ms-1" data-bs-dismiss="offcanvas" type="button"></button>
                                </div>
                            </div>
                            <div class="offcanvas-body notification-offcanvas-body app-scroll p-0" style="position: relative;">
                                {{-- Kontainer untuk daftar notifikasi dinamis --}}
                                <div id="notification-list-container" class="head-container notification-head-container">
                                    {{-- Placeholder saat loading --}}
                                    <div class="p-4 text-center text-muted">Loading notifications...</div>
                                </div>
                                {{-- Template untuk "No Notification" --}}
                                <div id="no-notification-message" class="hidden-massage py-4 px-3 text-center" style="display: none;">
                                    <img alt="No Notification" class="w-25 h-25 mb-3 mt-2" src="{{ asset('assets/images/icons/bell.png') }}">
                                    <div>
                                        <h6 class="mb-1 fw-bold">No Notifications</h6>
                                        <p class="text-secondary small">You don't have any new notifications right now.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Offcanvas Footer: See All Notifications -->
                            <div class="offcanvas-footer p-3 border-top bg-light">
                                <a href="{{ route('notifications.index') }}" class="btn btn-dark w-100 py-2 fw-bold text-center d-flex align-items-center justify-content-center gap-2">
                                    <i class="fa fa-list-alt"></i> {{ __('See All Notifications') }}
                                </a>
                            </div>
                        </div>
                    </li>

                    <li class="header-profile">
                        <a aria-controls="profilecanvasRight" class="d-block head-icon"
                            data-bs-target="#profilecanvasRight" data-bs-toggle="offcanvas" href="#"
                            role="button">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/uploads/user_avatars/' . Auth::user()->avatar) }}" width="35" height="35" class="b-r-50" onerror="this.onerror=null;this.src='{{ asset('assets/images/logo/sinarmeadow.png') }}';" alt="Avatar">
                            @else
                                <img alt="avtar" class="b-r-50 h-35 w-35 bg-dark" src="{{ asset('assets/images/logo/sinarmeadow.png') }}">
                            @endif
                        </a>

                        <div aria-labelledby="profilecanvasRight"
                            class="offcanvas offcanvas-end header-profile-canvas" id="profilecanvasRight"
                            tabindex="-1" style="height: auto !important; max-height: fit-content !important; bottom: auto !important;">
                            <div class="offcanvas-body p-3">
                                <ul class="list-unstyled m-0 p-0">
                                    <li class="d-flex align-items-center gap-3 pb-3 mb-2 border-bottom">
                                        <div class="d-flex-center">
                                            <span class="h-40 w-40 d-flex-center b-r-10 position-relative">
                                                @if(Auth::user()->avatar)
                                                    <img src="{{ asset('storage/uploads/user_avatars/' . Auth::user()->avatar) }}" width="40" height="40" class="b-r-10" onerror="this.onerror=null;this.src='{{ asset('assets/images/logo/sinarmeadow.png') }}';" alt="Avatar">
                                                @else
                                                    <img alt="" class="img-fluid b-r-10" src="{{ asset('assets/images/logo/sinarmeadow.png') }}">
                                                @endif
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 text-start f-w-600">{{ Auth::user()->name }}</h6>
                                            <p class="f-s-12 mb-0 text-secondary text-start">{{ Auth::user()->email }}</p>
                                        </div>
                                    </li>

                                    <li class="py-1">
                                        <a class="f-w-500 text-body d-block py-1" href="{{ route('profile.edit') }}" target="_blank">
                                            <i class="iconoir-user-love pe-2 f-s-18"></i> Profile Details
                                        </a>
                                    </li>
                                    <!-- Authentication -->
                                    <li class="py-1">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item f-w-500 bg-transparent border-0 p-0 text-danger d-block py-1">
                                                <i class="ph-duotone ph-sign-out pe-2 f-s-18"></i> {{ __('Log Out') }}
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>

<script>
    window.fetchNotifications = function() {
        const notificationBadge = document.getElementById('notification-badge');
        const notificationListContainer = document.getElementById('notification-list-container');
        const noNotificationMessage = document.getElementById('no-notification-message');

        if (!notificationBadge || !notificationListContainer) return;

        fetch('{{ route("notifications.fetch") }}', {
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response.status === 401) {
                notificationBadge.style.display = 'none';
                notificationListContainer.innerHTML = '';
                if (noNotificationMessage) noNotificationMessage.style.display = 'block';
                return null;
            }
            return response.json();
        })
        .then(data => {
            if (!data) return;

            if (data.unread_count > 0) {
                notificationBadge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                notificationBadge.style.display = 'flex';
            } else {
                notificationBadge.style.display = 'none';
            }

            notificationListContainer.innerHTML = '';

            if (data.notifications && data.notifications.length > 0) {
                if (noNotificationMessage) noNotificationMessage.style.display = 'none';
                data.notifications.forEach(notification => {
                    let iconClass = 'iconoir-bell';
                    let bgClass = 'bg-primary';
                    let title = notification.title || 'Notification';
                    let message = notification.message || '';

                    switch (notification.notification_type) {
                        case 'approval_request':
                            iconClass = 'iconoir-clock';
                            bgClass = 'bg-warning text-dark';
                            break;
                        case 'approved':
                            iconClass = 'iconoir-check-circle';
                            bgClass = 'bg-success text-white';
                            break;
                        case 'rejected':
                            iconClass = 'iconoir-cancel';
                            bgClass = 'bg-danger text-white';
                            break;
                        case 'updates':
                            iconClass = 'iconoir-bell-notification';
                            bgClass = 'bg-info text-white';
                            break;
                    }

                    const isUnread = !notification.read_at;
                    const unreadStyle = isUnread ? 'background-color: #f8fafc; border-left: 3px solid #3b82f6;' : '';

                    const itemHtml = `
                        <div class="p-3 border-bottom d-flex align-items-start gap-3 position-relative" style="${unreadStyle}">
                            <div class="flex-shrink-0">
                                <span class="${bgClass} h-35 w-35 d-flex-center b-r-10">
                                    <i class="${iconClass}"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 pe-2">
                                <div class="fw-bold small text-dark mb-1">${title}</div>
                                <div class="small text-secondary mb-1" style="font-size: 12px; line-height: 1.4;">${message}</div>
                                <div class="text-muted" style="font-size: 11px;"><i class="fa fa-clock me-1"></i>${notification.created_at_human}</div>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                ${isUnread ? `
                                    <button type="button" class="btn btn-sm btn-light-success text-success mark-single-header-read p-1 px-2" data-id="${notification.id}" title="Mark as Read" style="font-size: 11px;">
                                        <i class="fa fa-check"></i>
                                    </button>
                                ` : ''}
                            </div>
                        </div>
                    `;
                    notificationListContainer.insertAdjacentHTML('beforeend', itemHtml);
                });
            } else {
                if (noNotificationMessage) noNotificationMessage.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error fetching notifications:', error);
        });
    };

    document.addEventListener('DOMContentLoaded', function() {
        window.fetchNotifications();
        setInterval(window.fetchNotifications, 60000);

        // Mark all as read button in header
        document.getElementById('mark-all-read-btn')?.addEventListener('click', function(e) {
            e.preventDefault();
            fetch('{{ route("notifications.markAllAsRead") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(() => window.fetchNotifications());
        });

        // Clear all button in header
        document.getElementById('clear-all-notifications')?.addEventListener('click', function(e) {
            e.preventDefault();
            fetch('{{ route("notifications.clear") }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(() => window.fetchNotifications());
        });

        // Mark single notification as read in header
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.mark-single-header-read');
            if (btn) {
                const id = btn.getAttribute('data-id');
                fetch('{{ route("notifications.markAsRead") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ notification_id: id })
                }).then(() => window.fetchNotifications());
            }
        });
    });
</script>
