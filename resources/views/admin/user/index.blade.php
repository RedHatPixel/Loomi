@extends('admin.components.default')
@section('title', 'Manage Users')

@section('content')
<div class="container-fluid py-4">
    <h1 class="h3 mb-3">Users</h1>

    {{-- 🔎 Search + Filters --}}
    <div class="row g-2 mb-3 align-items-center">
        <form method="GET" action="{{ route('admin.users') }}" class="col-md-10">
            <div class="row g-2">
                <div class="col-12 col-md-4">
                    <input type="text" name="search" class="form-control"
                        placeholder="Search by name or email..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-6 col-md-3">
                    <select name="role" class="form-select">
                        <option value="">All Roles</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Users</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admins</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select name="ban" class="form-select">
                        <option value="">All Status</option>
                        <option value="1" {{ request('ban') === '1' ? 'selected' : '' }}>Banned</option>
                        <option value="0" {{ request('ban') === '0' ? 'selected' : '' }}>Active</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </div>
        </form>

        <form method="GET" action="{{ route('admin.users') }}" class="col-6 col-md-2">
            <button type="submit" class="btn btn-outline-secondary w-100">Reset</button>
        </form>
    </div>

    
    <div class="table-responsive">
        <table class="table table-hover shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td style="min-width: 200px;" class="fw-semibold">{{ $user->name }}</td>
                        <td style="min-width: 250px;">{{ $user->email }}</td>
                        <td style="min-width: 80px;">
                            <span class="badge {{ $user->isAdmin() ? 'bg-primary' : 'bg-secondary' }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td style="min-width: 120px;">{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                @if($user->isUser() && $user->id !== Auth::id())
                                    <form action="{{ route('admin.user.ban', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm {{ $user->ban ? 'btn-success' : 'btn-danger' }}">
                                            @if($user->ban)
                                                <i class="bi bi-check-circle"></i>
                                            @else
                                                <i class="bi bi-ban"></i>
                                            @endif
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.user.delete', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" 
                                            onclick="return confirm('Are you sure you want to delete this?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($users->hasPages() && $users->count())
        <div class="py-4">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
