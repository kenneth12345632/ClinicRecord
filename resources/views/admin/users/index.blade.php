@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">User Management</h1>
            <p class="text-sm text-slate-500">Admin-only account access control center.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" style="background-color: #16a34a !important; color: #fff !important;" class="px-5 py-2.5 rounded-full font-bold text-sm hover:opacity-90 transition shadow-sm">+ Add User</a>
    </div>

    <div class="flex flex-wrap items-end gap-3">
        <form method="GET" class="max-w-md flex-1 min-w-[240px]">
            <input name="search" value="{{ $search }}" placeholder="Search users..." class="w-full px-4 py-2 border rounded-lg">
        </form>
        <div>
            <label for="roleFilter" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Role</label>
            <select id="roleFilter" class="px-3 py-2 border rounded-lg bg-white text-sm">
                <option value="all">All Roles</option>
                <option value="admin">Admin</option>
                <option value="doctor">Doctor</option>
                <option value="nurse">Nurse</option>
                <option value="bhw">BHW</option>
            </select>
        </div>
        <button type="button" id="toggleUsersBtn"
            style="background-color: #dcfce7 !important; border: 2px solid #86efac !important; color: #16a34a !important;"
            class="px-5 py-2.5 rounded-full text-sm font-bold hover:opacity-90 transition shadow-sm">
            Show Users
        </button>
    </div>
    <div class="bg-white rounded-xl border overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left">Email</th>
                    <th class="px-4 py-3 text-left">Role</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody id="usersTableBody">
                @forelse($users as $user)
                <tr class="border-t user-row" data-role="{{ strtolower((string) $user->role) }}">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            @if(!empty($user->profile_photo_path))
                                <img src="{{ asset('storage/'.$user->profile_photo_path) }}" alt="{{ $user->full_name }}" class="w-8 h-8 rounded-full object-cover border border-slate-200">
                            @else
                                <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-[10px] font-bold">
                                    {{ strtoupper(substr((string) ($user->first_name ?? 'U'), 0, 1) . substr((string) ($user->last_name ?? ''), 0, 1)) }}
                                </div>
                            @endif
                            <span class="font-medium">{{ $user->full_name }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3">{{ $user->email }}</td>
                    <td class="px-4 py-3 uppercase">{{ $user->role }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded text-xs {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="px-3 py-1.5 bg-slate-100 rounded">Edit</a>
                            <form method="POST" action="{{ route('admin.users.status', $user) }}">
                                @csrf
                                @method('PATCH')
                                <button class="px-3 py-1.5 {{ $user->is_active ? 'bg-amber-100 text-amber-800' : 'bg-green-100 text-green-800' }} rounded">
                                    {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-6 text-center text-slate-500">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $users->links() }}</div>
</div>
<script>
    const userRows = Array.from(document.querySelectorAll('#usersTableBody .user-row'));
    let usersVisible = localStorage.getItem('toggle_admin_users') === 'true';

    function updateUsersToggleLabel() {
        const btn = document.getElementById('toggleUsersBtn');
        if (!btn) return;
        btn.textContent = usersVisible ? 'Hide Users' : 'Show Users';
    }

    function applyUserRoleFilter() {
        const roleFilter = document.getElementById('roleFilter');
        const selectedRole = roleFilter ? roleFilter.value : 'all';

        userRows.forEach((row) => {
            if (!usersVisible) {
                row.style.display = 'none';
                return;
            }

            const rowRole = (row.getAttribute('data-role') || '').toLowerCase();
            const matchesRole = selectedRole === 'all' || rowRole === selectedRole;
            row.style.display = matchesRole ? '' : 'none';
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('toggleUsersBtn');
        const roleFilter = document.getElementById('roleFilter');
        updateUsersToggleLabel();

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function () {
                usersVisible = !usersVisible;
                localStorage.setItem('toggle_admin_users', usersVisible);
                updateUsersToggleLabel();
                applyUserRoleFilter();
            });
        }

        if (roleFilter) {
            roleFilter.addEventListener('change', function () {
                usersVisible = true;
                localStorage.setItem('toggle_admin_users', true);
                updateUsersToggleLabel();
                applyUserRoleFilter();
            });
        }

        applyUserRoleFilter();
    });
</script>
@endsection
