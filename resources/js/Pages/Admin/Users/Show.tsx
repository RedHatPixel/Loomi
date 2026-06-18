import { Head, Link, router } from '@inertiajs/react';
import { PageProps } from '@/Types';
import AdminLayout from '@/Layouts/AdminLayout';
import { ArrowLeftIcon, TrashIcon } from '@heroicons/react/24/outline';
import { useState } from 'react';
import Reveal from '@/Components/Shared/Reveal';

interface UserData {
    id: number;
    name: string;
    email: string;
    roles: string[];
    created_at: string;
}

interface StoreRow {
    id: number;
    name: string;
    slug: string;
    is_active: boolean;
    products_count: number;
    created_at: string;
}

interface OrderRow {
    id: number;
    status: string;
    total: number;
    created_at: string;
}

interface Props extends PageProps {
    userData: UserData;
    stores: StoreRow[];
    orders: OrderRow[];
}

const roleColors: Record<string, string> = {
    admin: 'bg-purple-100 text-purple-800',
    seller: 'bg-brand-100 text-brand-800',
    customer: 'bg-gray-100 text-gray-700',
};

const statusColors: Record<string, string> = {
    pending: 'bg-amber-100 text-amber-800',
    confirmed: 'bg-blue-100 text-blue-800',
    shipped: 'bg-sky-100 text-sky-800',
    delivered: 'bg-green-100 text-green-800',
    cancelled: 'bg-red-100 text-red-800',
};

export default function AdminUsersShow({ userData, stores, orders }: Props) {
    const [role, setRole] = useState(userData.roles[0] ?? 'customer');

    const updateRole = () => {
        router.patch(route('admin.users.role', userData.id), { role }, { preserveScroll: true });
    };

    const handleDelete = () => {
        if (!confirm(`Delete user "${userData.name}"? This cannot be undone.`)) return;
        router.delete(route('admin.users.destroy', userData.id));
    };

    return (
        <>
            <Head title={userData.name} />
            <AdminLayout header={userData.name}>
                <div className="page-container py-6 sm:py-8 space-y-6">
                    {/* Back link */}
                    <Link href={route('admin.users.index')} className="inline-flex items-center gap-1.5 text-sm text-content-muted hover:text-content transition-colors">
                        <ArrowLeftIcon className="w-4 h-4" />
                        Back to users
                    </Link>

                    {/* User info card */}
                    <Reveal>
                        <div className="card p-6">
                            <div className="flex items-start gap-4">
                                <div className="size-14 rounded-full bg-brand-100 flex-center text-xl font-bold text-brand-700 shrink-0">
                                    {userData.name.charAt(0).toUpperCase()}
                                </div>
                                <div className="flex-1 min-w-0">
                                    <h2 className="text-xl font-semibold text-content">{userData.name}</h2>
                                    <p className="text-sm text-content-muted">{userData.email}</p>
                                    <div className="flex gap-2 mt-2">
                                        {userData.roles.map((r) => (
                                            <span key={r} className={`px-2.5 py-0.5 rounded-full text-xs font-medium capitalize ${roleColors[r] ?? ''}`}>
                                                {r}
                                            </span>
                                        ))}
                                    </div>
                                    <p className="text-xs text-content-muted mt-2">Joined {userData.created_at}</p>
                                </div>
                                <div className="flex items-center gap-2">
                                    <div className="flex items-center gap-2">
                                        <select value={role} onChange={(e) => setRole(e.target.value)} className="input text-sm py-1.5 w-auto">
                                            <option value="customer">Customer</option>
                                            <option value="seller">Seller</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                        <button type="button" onClick={updateRole} className="btn-primary text-xs px-3 py-1.5">
                                            Update
                                        </button>
                                    </div>
                                    {!userData.roles.includes('admin') && (
                                        <button type="button" onClick={handleDelete} className="btn-ghost p-2 text-red-500 hover:bg-red-50" aria-label="Delete user">
                                            <TrashIcon className="w-4 h-4" />
                                        </button>
                                    )}
                                </div>
                            </div>
                        </div>
                    </Reveal>

                    <div className="grid lg:grid-cols-2 gap-6">
                        {/* Stores */}
                        <Reveal delay={100}>
                            <section>
                                <h3 className="text-base font-semibold text-content mb-3">Stores ({stores.length})</h3>
                                <div className="card !p-0 overflow-hidden">
                                    {stores.length === 0 ? (
                                        <div className="p-6 text-center text-sm text-content-muted">No stores.</div>
                                    ) : (
                                        <div className="divide-y divide-border">
                                            {stores.map((store) => (
                                                <div key={store.id} className="flex items-center justify-between px-4 sm:px-5 py-3">
                                                    <div>
                                                        <Link href={route('admin.stores.show', store.id)} className="text-sm font-medium text-content hover:text-brand-700">
                                                            {store.name}
                                                        </Link>
                                                        <p className="text-xs text-content-muted">{store.products_count} products</p>
                                                    </div>
                                                    <span className={`px-2 py-0.5 rounded-full text-[10px] font-medium ${store.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`}>
                                                        {store.is_active ? 'Active' : 'Inactive'}
                                                    </span>
                                                </div>
                                            ))}
                                        </div>
                                    )}
                                </div>
                            </section>
                        </Reveal>

                        {/* Orders */}
                        <Reveal delay={150}>
                            <section>
                                <h3 className="text-base font-semibold text-content mb-3">Orders ({orders.length})</h3>
                                <div className="card !p-0 overflow-hidden">
                                    {orders.length === 0 ? (
                                        <div className="p-6 text-center text-sm text-content-muted">No orders.</div>
                                    ) : (
                                        <div className="divide-y divide-border">
                                            {orders.map((order) => (
                                                <div key={order.id} className="flex items-center justify-between px-4 sm:px-5 py-3">
                                                    <Link href={route('admin.orders.show', order.id)} className="flex items-center gap-3">
                                                        <span className="text-sm font-medium text-content">#{order.id}</span>
                                                        <span className={`px-2 py-0.5 rounded-full text-[10px] font-medium capitalize ${statusColors[order.status] ?? ''}`}>
                                                            {order.status}
                                                        </span>
                                                    </Link>
                                                    <div className="flex items-center gap-3 text-sm">
                                                        <span className="font-semibold text-content">PHP {order.total.toLocaleString()}</span>
                                                        <span className="text-[10px] text-content-muted">{order.created_at}</span>
                                                    </div>
                                                </div>
                                            ))}
                                        </div>
                                    )}
                                </div>
                            </section>
                        </Reveal>
                    </div>
                </div>
            </AdminLayout>
        </>
    );
}
