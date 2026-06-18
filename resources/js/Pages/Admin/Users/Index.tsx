import { Head, Link, router } from '@inertiajs/react';
import { PageProps, PaginatedData } from '@/Types';
import AdminLayout from '@/Layouts/AdminLayout';
import { MagnifyingGlassIcon } from '@heroicons/react/24/outline';
import { useState } from 'react';
import Reveal from '@/Components/Shared/Reveal';

interface UserRow {
    id: number;
    name: string;
    email: string;
    roles: string[];
    stores_count: number;
    orders_count: number;
    created_at: string;
}

interface Props extends PageProps {
    users: PaginatedData<UserRow>;
    filters: { search: string; role: string };
}

const roleColors: Record<string, string> = {
    admin: 'bg-purple-100 text-purple-800',
    seller: 'bg-brand-100 text-brand-800',
    customer: 'bg-gray-100 text-gray-700',
};

export default function AdminUsersIndex({ users, filters }: Props) {
    const [search, setSearch] = useState(filters.search);

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        router.get(route('admin.users.index'), { search, role: filters.role || undefined }, { preserveState: true, replace: true });
    };

    const handleRoleFilter = (role: string) => {
        router.get(route('admin.users.index'), { search, role: role || undefined }, { preserveState: true, replace: true });
    };

    const handleDelete = (user: UserRow) => {
        if (!confirm(`Delete user "${user.name}"? This cannot be undone.`)) return;
        router.delete(route('admin.users.destroy', user.id), { preserveScroll: true });
    };

    return (
        <>
            <Head title="Users" />
            <AdminLayout header="Users">
                <div className="page-container py-6 sm:py-8 space-y-6">
                    {/* Filters */}
                    <Reveal>
                        <div className="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                            <form onSubmit={handleSearch} className="relative flex-1 sm:max-w-xs">
                                <MagnifyingGlassIcon className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-content-muted" />
                                <input
                                    type="text"
                                    placeholder="Search users..."
                                    value={search}
                                    onChange={(e) => setSearch(e.target.value)}
                                    className="input pl-9 text-sm w-full"
                                />
                            </form>
                            <div className="flex items-center gap-2 flex-wrap">
                                {['', 'admin', 'seller', 'customer'].map((role) => (
                                    <button
                                        key={role}
                                        onClick={() => handleRoleFilter(role)}
                                        className={`px-3 py-1.5 rounded-full text-xs font-medium border transition-all ${
                                            filters.role === role || (!filters.role && role === '')
                                                ? 'bg-brand-700 text-white border-brand-700'
                                                : 'bg-surface text-content border-border hover:border-brand-300'
                                        }`}
                                    >
                                        {role ? role.charAt(0).toUpperCase() + role.slice(1) + 's' : 'All'}
                                    </button>
                                ))}
                            </div>
                        </div>
                    </Reveal>

                    {/* Users table */}
                    <Reveal delay={100}>
                        {users.data.length === 0 ? (
                            <div className="card text-center py-16">
                                <p className="text-sm text-content-muted">No users found.</p>
                            </div>
                        ) : (
                            <div className="card !p-0 overflow-hidden">
                                <div className="overflow-x-auto">
                                    <table className="w-full text-sm">
                                        <thead>
                                            <tr className="border-b border-border bg-surface-raised text-left">
                                                <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider">User</th>
                                                <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider hidden sm:table-cell">Role</th>
                                                <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider hidden md:table-cell">Stores</th>
                                                <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider hidden md:table-cell">Orders</th>
                                                <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider hidden lg:table-cell">Joined</th>
                                                <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider text-right">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody className="divide-y divide-border">
                                            {users.data.map((user) => (
                                                <tr key={user.id} className="hover:bg-surface-page transition-colors">
                                                    <td className="px-4 sm:px-6 py-3">
                                                        <Link href={route('admin.users.show', user.id)} className="flex items-center gap-3">
                                                            <div className="size-8 rounded-full bg-brand-100 flex-center text-xs font-bold text-brand-700 shrink-0">
                                                                {user.name.charAt(0).toUpperCase()}
                                                            </div>
                                                            <div className="min-w-0">
                                                                <p className="font-medium text-content truncate max-w-[180px]">{user.name}</p>
                                                                <p className="text-xs text-content-muted truncate">{user.email}</p>
                                                            </div>
                                                        </Link>
                                                    </td>
                                                    <td className="px-4 sm:px-6 py-3 hidden sm:table-cell">
                                                        <div className="flex gap-1 flex-wrap">
                                                            {user.roles.map((role) => (
                                                                <span key={role} className={`px-2 py-0.5 rounded-full text-[10px] font-medium capitalize ${roleColors[role] ?? 'bg-gray-100 text-gray-700'}`}>
                                                                    {role}
                                                                </span>
                                                            ))}
                                                        </div>
                                                    </td>
                                                    <td className="px-4 sm:px-6 py-3 text-content-secondary hidden md:table-cell">{user.stores_count}</td>
                                                    <td className="px-4 sm:px-6 py-3 text-content-secondary hidden md:table-cell">{user.orders_count}</td>
                                                    <td className="px-4 sm:px-6 py-3 text-content-muted text-xs hidden lg:table-cell">{user.created_at}</td>
                                                    <td className="px-4 sm:px-6 py-3 text-right">
                                                        <div className="flex items-center justify-end gap-1">
                                                            <Link
                                                                href={route('admin.users.show', user.id)}
                                                                className="btn-ghost text-xs px-2 py-1"
                                                            >
                                                                View
                                                            </Link>
                                                            {!user.roles.includes('admin') && (
                                                                <button
                                                                    type="button"
                                                                    onClick={() => handleDelete(user)}
                                                                    className="btn-ghost text-xs px-2 py-1 text-red-500 hover:bg-red-50"
                                                                >
                                                                    Delete
                                                                </button>
                                                            )}
                                                        </div>
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        )}
                    </Reveal>

                    {/* Pagination */}
                    {users.meta && users.meta.last_page > 1 && (
                        <Reveal delay={200}>
                            <div className="flex items-center justify-between text-sm text-content-muted">
                                <span>Page {users.meta.current_page} of {users.meta.last_page}</span>
                                <div className="flex gap-1">
                                    {Array.from({ length: users.meta.last_page }, (_, i) => i + 1).map((page) => (
                                        <Link
                                            key={page}
                                            href={route('admin.users.index', { page, ...filters })}
                                            preserveState
                                            className={`px-3 py-1.5 rounded-md text-sm font-medium transition-colors ${
                                                page === users.meta.current_page
                                                    ? 'bg-brand-50 text-brand-700'
                                                    : 'text-content-secondary hover:bg-surface-raised'
                                            }`}
                                        >
                                            {page}
                                        </Link>
                                    ))}
                                </div>
                            </div>
                        </Reveal>
                    )}
                </div>
            </AdminLayout>
        </>
    );
}
