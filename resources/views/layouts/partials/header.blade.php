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
                            <span id="notification-badge" class="position-absolute top-0 start-100 translate-middle bg-success" style="display:none; width:25px; height:25px; min-width:25px; padding: 5px; font-size:12px; line-height: 1; color:#fff; display:flex; align-items:center; justify-content:center; border-radius:50%; border:2px solid #fff;">
                                0
                            </span>
                        </a>
                        <div aria-labelledby="notificationcanvasRightLabel"
                            class="offcanvas offcanvas-end header-notification-canvas" id="notificationcanvasRight"
                            tabindex="-1">
                            <div class="offcanvas-header">
                                <h5 class="offcanvas-title" id="notificationcanvasRightLabel">Notification</h5>
                                <div class="d-flex align-items-center gap-2">
                                    <a href="#" id="clear-all-notifications" class="btn btn-sm btn-outline-danger">Clear All</a>
                                    <button aria-label="Close" class="btn-close" data-bs-dismiss="offcanvas" type="button"></button>
                                </div>
                            </div>
                            <div class="offcanvas-body notification-offcanvas-body app-scroll p-0">
                                {{-- Kontainer untuk daftar notifikasi dinamis --}}
                                <div id="notification-list-container" class="head-container notification-head-container">
                                    {{-- Placeholder saat loading --}}
                                    <div class="p-4 text-center text-muted">Loading notifications...</div>
                                </div>
                                {{-- Template untuk "No Notification" --}}
                                <div id="no-notification-message" class="hidden-massage py-4 px-3" style="display: none;">
                                    <img alt="No Notification" class="w-50 h-50 mb-3 mt-2"
                                        src="{{ asset('assets/images/icons/bell.png') }}">
                                    <div>
                                        <h6 class="mb-0">No Notifications</h6>
                                        <p class="text-secondary">When you have any notifications, they will appear here.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="header-profile">
                        <a aria-controls="profilecanvasRight" class="d-block head-icon"
                            data-bs-target="#profilecanvasRight" data-bs-toggle="offcanvas" href="#"
                            role="button">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/uploads/user_avatars/' . Auth::user()->avatar) }}" width="35" height="35" class="b-r-50">
                            @else
                                <img alt="avtar" class="b-r-50 h-35 w-35 bg-dark" src="{{ asset('assets/images/logo/sinarmeadow.png') }}">
                            @endif
                        </a>

                        <div aria-labelledby="profilecanvasRight"
                            class="offcanvas offcanvas-end header-profile-canvas" id="profilecanvasRight"
                            tabindex="-1">
                            <div class="offcanvas-body app-scroll">
                                <ul class="">
                                    <li class="d-flex gap-3 mb-3">
                                        <div class="d-flex-center">
                                            <span class="h-45 w-45 d-flex-center b-r-10 position-relative">
                                                @if(Auth::user()->avatar)
                                                    <img src="{{ asset('storage/uploads/user_avatars/' . Auth::user()->avatar) }}" width="45" height="45" class="b-r-10">
                                                @else
                                                    <img alt="" class="img-fluid b-r-10" src="{{ asset('assets/images/logo/sinarmeadow.png') }}">
                                                @endif
                                            </span>
                                        </div>
                                        <div class="text-center mt-2">
                                            <h6 class="mb-0"> {{ Auth::user()->name }}
                                                </h6>
                                            <p class="f-s-12 mb-0 text-secondary">{{ Auth::user()->email  }}</p>
                                        </div>
                                    </li>

                                    <li>
                                        <a class="f-w-500" href="{{ route('profile.edit') }}" target="_blank">
                                            <i class="iconoir-user-love pe-1 f-s-20"></i> Profile
                                            Details
                                        </a>
                                    </li>
                                    <!-- Authentication -->
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item f-w-500 bg-transparent border-0 p-0">
                                                <i class="ph-duotone ph-sign-out pe-1 f-s-20"></i> {{ __('Log Out') }}
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
    document.addEventListener('DOMContentLoaded', function() {
        const notificationBadge = document.getElementById('notification-badge');
        const notificationListContainer = document.getElementById('notification-list-container');
        const noNotificationMessage = document.getElementById('no-notification-message');
        const clearAllButton = document.getElementById('clear-all-notifications');

        function createNotificationHtml(notification) {
            let iconClass = 'iconoir-document';
            let bgClass = 'bg-secondary';
            let messageHtml = notification.message;

            switch (notification.notification_type) {
                case 'approval_request':
                    iconClass = 'iconoir-clock';
                    bgClass = 'bg-warning';
                    messageHtml = `<span class="f-w-500">${notification.title}</span> is <span class="text-warning">waiting for your approval</span>.`;
                    break;
                case 'approved':
                    iconClass = 'iconoir-check-circle';
                    bgClass = 'bg-success';
                    messageHtml = `<span class="f-w-500">${notification.title}</span> has been <span class="text-success">approved</span>.`;
                    break;
                case 'rejected':
                    iconClass = 'iconoir-cancel';
                    bgClass = 'bg-danger';
                    messageHtml = `<span class="f-w-500">${notification.title}</span> has been <span class="text-danger">rejected</span>.`;
                    break;
                case 'updates':
                    iconClass = 'iconoir-bell-notification';
                    bgClass = 'bg-info';
                    messageHtml = `${notification.message}`;
                    break;
            }

            const readClass = notification.read_at ? '' : 'notification-unread';

            return `
                <div class="notification-message head-box ${readClass}" data-id="${notification.id}">
                    <div class="message-images">
                        <span class="${bgClass} h-35 w-35 d-flex-center b-r-10 position-relative">
                            <i class="${iconClass}"></i>
                        </span>
                    </div>
                    <div class="message-content-box flex-grow-1 ps-2">
                        <a class="f-s-15 text-secondary mb-0" href="#">
                            ${messageHtml}
                        </a>
                        <span class="badge text-light-secondary mt-2">${notification.created_at_human}</span>
                    </div>
                </div>
            `;
        }

        function fetchAndDisplayNotifications() {
            // Send AJAX headers so Laravel returns 401 JSON for unauthenticated requests
            // instead of redirecting to the login page (which can set the "intended" URL).
            fetch('{{ route("notifications.fetch") }}', {
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(response => {
                    // If user is not authenticated, avoid following redirects to login
                    if (response.status === 401) {
                        // Clear UI and show 'no notifications' state without changing location
                        notificationBadge.style.display = 'none';
                        notificationListContainer.innerHTML = '';
                        noNotificationMessage.style.display = 'block';
                        return null;
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data) return;

                    if (data.unread_count > 0) {
                        notificationBadge.textContent = data.unread_count > 9 ? '9+' : data.unread_count;
                        notificationBadge.style.display = 'block';
                    } else {
                        notificationBadge.style.display = 'none';
                    }

                    notificationListContainer.innerHTML = '';

                    if (data.notifications.length > 0) {
                        noNotificationMessage.style.display = 'none';
                        data.notifications.forEach(notification => {
                            const notificationHtml = createNotificationHtml(notification);
                            notificationListContainer.insertAdjacentHTML('beforeend', notificationHtml);
                        });
                    } else {
                        noNotificationMessage.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error fetching notifications:', error);
                    notificationListContainer.innerHTML = '<div class="p-4 text-center text-danger">Failed to load notifications.</div>';
                });
        }

        function clearAllNotifications() {
            // **PERUBAHAN DI SINI: Konfirmasi dihapus**
            fetch('{{ route("notifications.clear") }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    notificationListContainer.innerHTML = '';
                    noNotificationMessage.style.display = 'block';
                    notificationBadge.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error clearing notifications:', error);
                alert('An error occurred while clearing notifications.');
            });
        }

        // Panggil fungsi saat halaman dimuat
        fetchAndDisplayNotifications();

        // Refresh notifikasi setiap 1 menit
        setInterval(fetchAndDisplayNotifications, 60000);

        // Tambahkan event listener untuk tombol "Clear All"
        clearAllButton.addEventListener('click', function(e) {
            e.preventDefault(); // Mencegah link default
            clearAllNotifications();
        });
    });
</script>
