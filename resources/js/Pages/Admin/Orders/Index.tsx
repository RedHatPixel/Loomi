import { Head, Link, router } from '@inertiajs/react';
import { PageProps, PaginatedData } from '@/Types';
import AdminLayout from '@/Layouts/AdminLayout';
import { useState } from 'react';
import Reveal from '@/Components/Shared/Reveal';

interface OrderRow {
    id: number;
    status: string;
    total: number;
    customer: string;
    items_count: number;
    created_at: string;
}

interface Props extends PageProps {
    orders: PaginatedData<OrderRow>;
    filters: { status: string; search: string };
}

const statusColors: Record<string, string> = {
    pending: 'bg-amber-100 text-amber-800',
    confirmed: 'bg-blue-100 text-blue-800',
    shipped: 'bg-sky-100 text-sky-800',
    delivered: 'bg-green-100 text-green-800',
    cancelled: 'bg-red-100 text-red-800',
};

export default function AdminOrdersIndex({ orders, filters }: Props) {
    const [search, setSearch] = useState(filters.search);

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        router.get(route('admin.orders.index'), { search, status: filters.status || undefined }, { preserveState: true, replace: true });
    };

    const handleFilter = (value: string) => {
        router.get(route('admin.orders.index'), { search, status: value || undefined }, { preserveState: true, replace: true });
    };

    const handleDelete = (order: OrderRow) => {
        if (!confirm(`Delete order #${order.id}? This cannot be undone.`)) return;
        router.delete(route('admin.orders.destroy', order.id), { preserveScroll: true });
    };

    const statusOptions = [
        { value: '', label: 'All statuses' },
        { value: 'pending', label: 'Pending' },
        { value: 'confirmed', label: 'Confirmed' },
        { value: 'shipped', label: 'Shipped' },
        { value: 'delivered', label: 'Delivered' },
        { value: 'cancelled', label: 'Cancelled' },
    ];

    return (
        <>
            <Head title="Orders" />
            <AdminLayout header="Orders">
                <div className="page-container py-6 sm:py-8 space-y-6">
                    {/* Filters */}
                    <Reveal>
                        <div className="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                            <form onSubmit={handleSearch} className="relative flex-1 sm:max-w-xs">
                                <input type="text" placeholder="Search by ID or customer..." value={search} onChange={(e) => setSearch(e.target.value)}
                                    className="input pl-3 text-sm w-full" />
                            </form>
                            <select value={filters.status} onChange={(e) => handleFilter(e.target.value)} className="input w-auto text-sm">
                                {statusOptions.map((opt) => (<option key={opt.value} value={opt.value}>{opt.label}</option>))}
                            </select>
                        </div>
                    </Reveal>

                    {/* Table */}
                    <Reveal delay={100}>
                        {orders.data.length === 0 ? (
                            <div className="card text-center py-16"><p className="text-sm text-content-muted">No orders found.</p></div>
                        ) : (
                            <div className="card !p-0 overflow-hidden">
                                <div className="overflow-x-auto">
                                    <table className="w-full text-sm">
                                        <thead>
                                            <tr className="border-b border-border bg-surface-raised text-left">
                                                <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider">Order</th>
                                                <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider">Status</th>
                                                <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider hidden sm:table-cell">Customer</th>
                                                <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider">Total</th>
                                                <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider hidden md:table-cell">Items</th>
                                                <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider hidden lg:table-cell">Date</th>
                                                <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider text-right">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody className="divide-y divide-border">
                                            {orders.data.map((order) => (
                                                <tr key={order.id} className="hover:bg-surface-page transition-colors">
                                                    <td className="px-4 sm:px-6 py-3">
                                                        <Link href={route('admin.orders.show', order.id)} className="font-medium text-content hover:text-brand-700">
                                                            #{order.id}
                                                        </Link>
                                                    </td>
                                                    <td className="px-4 sm:px-6 py-3">
                                                        <span className={`px-2 py-0.5 rounded-full text-[10px] font-medium capitalize ${statusColors[order.status] ?? ''}`}>
                                                            {order.status}
                                                        </span>
                                                    </td>
                                                    <td className="px-4 sm:px-6 py-3 text-content-secondary hidden sm:table-cell">{order.customer}</td>
                                                    <td className="px-4 sm:px-6 py-3 text-content font-medium">PHP {order.total.toLocaleString()}</td>
                                                    <td className="px-4 sm:px-6 py-3 text-content-secondary hidden md:table-cell">{order.items_count}</td>
                                                    <td className="px-4 sm:px-6 py-3 text-content-muted text-xs hidden lg:table-cell">{order.created_at}</td>
                                                    <td className="px-4 sm:px-6 py-3 text-right">
                                                        <div className="flex items-center justify-end gap-1">
                                                            <Link href={route('admin.orders.show', order.id)} className="btn-ghost text-xs px-2 py-1">View</Link>
                                                            <button type="button" onClick={() => handleDelete(order)} className="btn-ghost text-xs px-2 py-1 text-red-500 hover:bg-red-50">Delete</button>
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

                    {orders.meta && orders.meta.last_page > 1 && (
                        <Reveal delay={200}>
                            <div className="flex items-center justify-between text-sm text-content-muted">
                                <span>Page {orders.meta.current_page} of {orders.meta.last_page}</span>
                                <div className="flex gap-1">
                                    {Array.from({ length: orders.meta.last_page }, (_, i) => i + 1).map((page) => (
                                        <Link key={page} href={route('admin.orders.index', { page, ...filters })} preserveState
                                            className={`px-3 py-1.5 rounded-md text-sm font-medium transition-colors ${
                                                page === orders.meta.current_page ? 'bg-brand-50 text-brand-700' : 'text-content-secondary hover:bg-surface-raised'
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
