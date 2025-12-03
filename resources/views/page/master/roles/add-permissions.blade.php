<x-app-layout>
    @section('title')
        Give Permissions to Role
    @endsection

    <div class="container mx-auto mt-5">
        <div class="flex justify-center">
            <div class="w-full">

                @if (session('status'))
                    <div class="alert alert-success bg-green-500 text-white p-4 rounded mb-4">{{ session('status') }}
                    </div>
                @endif

                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="bg-primary text-white p-1 rounded-lg d-flex justify-content-between align-items-center w-100">
                        <h4 class="mb-0">Role: {{ $role->name }}</h4>
                        <a href="{{ url('roles') }}" class="btn btn-success">Back</a>
                    </div>
                    <div class="p-6 py-10">

                        <form id="givePermissionForm" method="POST"
                            action="{{ url('roles/'.$role->id.'/give-permissions') }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                @php
                                    $permissionsByLastWord = [];
                                    foreach ($permissions as $permission) {
                                        $words = explode(' ', $permission->name);
                                        $lastWord = end($words);
                                        if (!isset($permissionsByLastWord[$lastWord])) {
                                            $permissionsByLastWord[$lastWord] = [];
                                        }
                                        $permissionsByLastWord[$lastWord][] = $permission;
                                    }
                                @endphp
                                @foreach ($permissionsByLastWord as $lastWord => $permissionsGroup)
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div
                                                class="card-header bg-light-primary d-flex align-items-center justify-content-between">
                                                <h6 class="mb-0">Permission: {{ ucfirst($lastWord) }}</h6>
                                                <div class="form-check ms-2">
                                                    <input class="form-check-input check-all-group" type="checkbox"
                                                        id="check-all-{{ Str::slug($lastWord) }}"
                                                        data-group="group-{{ Str::slug($lastWord) }}">
                                                    <label class="form-check-label"
                                                        for="check-all-{{ Str::slug($lastWord) }}">All</label>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                @foreach ($permissionsGroup as $permission)
                                                    <h5 class="form-check mb-2">
                                                        <input class="form-check-input group-{{ Str::slug($lastWord) }}"
                                                            type="checkbox" name="permissions[]"
                                                            id="perm-{{ $permission->id }}"
                                                            value="{{ $permission->name }}"
                                                            {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="perm-{{ $permission->id }}">
                                                            {{ $permission->name }}
                                                        </label>
                                                    </h5>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="row">
                                <div class="col-12 d-flex justify-content-center" style="margin-bottom: 50px">
                                    <button type="submit" class="btn btn-primary text-2xl fw-bold text-white">Update
                                        Permissions</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function toggleAllCheckboxes(source) {
                checkboxes = document.querySelectorAll('.permission-item input[type="checkbox"]');
                for (var i = 0; i < checkboxes.length; i++) {
                    if (checkboxes[i] != source) {
                        checkboxes[i].checked = source.checked;
                    }
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                var form = document.getElementById('givePermissionForm');
                if (!form) return;

                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    var btn = form.querySelector('button[type="submit"]');
                    var originalText = btn.innerHTML;
                    btn.disabled = true;
                    btn.innerHTML = 'Saving...';

                    var data = new FormData(form);
                    data.set('_method', 'PUT');

                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: data,
                        credentials: 'same-origin'
                    }).then(function(response) {
                        return response.json().then(function(json) {
                            return {
                                status: response.status,
                                json: json
                            };
                        }).catch(function() {
                            return {
                                status: response.status,
                                json: null
                            };
                        });
                    }).then(function(resp) {
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                        if (resp.status >= 200 && resp.status < 300 && resp.json && resp.json.status ===
                            'success') {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: resp.json.message || 'Permissions updated',
                                showConfirmButton: false,
                                timer: 2000
                            });
                        } else if (resp.status === 422 && resp.json && resp.json.errors) {
                            var msg = Object.values(resp.json.errors).map(function(v) {
                                return v.join('\n');
                            }).join('\n');
                            Swal.fire('Validation Error', msg, 'error');
                        } else {
                            var msg = (resp.json && resp.json.message) ? resp.json.message :
                                'Server error';
                            Swal.fire('Error', msg, 'error');
                        }
                    }).catch(function(err) {
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                        console.error('Permission form submit error', err);
                        Swal.fire('Error', 'Failed to update permissions. See console.', 'error');
                    });
                });
            });

            // Group "All" checkbox behavior: toggle group items and keep master in sync
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.check-all-group').forEach(function(master) {
                    var groupClass = master.dataset.group; // e.g. 'group-something'
                    if (!groupClass) return;
                    var items = document.querySelectorAll('.' + groupClass);

                    var updateMaster = function() {
                        if (!items || items.length === 0) {
                            master.checked = false;
                            return;
                        }
                        var allChecked = Array.prototype.slice.call(items).every(function(cb) {
                            return cb.checked;
                        });
                        master.checked = allChecked;
                    };

                    // When master changed, set all items
                    master.addEventListener('change', function() {
                        items.forEach(function(cb) {
                            cb.checked = master.checked;
                        });
                    });

                    // When any item changed, update master state
                    items.forEach(function(cb) {
                        cb.addEventListener('change', function() {
                            updateMaster();
                        });
                    });

                    // initialize master state on load
                    updateMaster();
                });
            });
        </script>
    @endpush
</x-app-layout>
