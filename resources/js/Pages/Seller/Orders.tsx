import { Head, Link, router } from '@inertiajs/react';
import { PageProps, Order, PaginatedData } from '@/Types';
import SellerLayout from '@/Layouts/SellerLayout';
import {
    FunnelIcon,
} from '@heroicons/react/24/outline';

interface StoreOption {
    id: number;
    name: string;
    slug: string;
}

interface OtherStoreStatus {
    store_id: number;
    store_name: string;
    status: string;
}

interface Props extends PageProps {
    orders: PaginatedData<Order & {
        my_store_status: string;
        other_stores: OtherStoreStatus[];
    }>;
    stores: StoreOption[];
    filters: {
        store_id: number | null;
        status: string;
    };
}

const statusColors: Record<string, string> = {
    pending: 'badge-warning',
    confirmed: 'badge-info',
    shipped: 'badge-info',
    delivered: 'badge-success',
    cancelled: 'badge-danger',
};

const statusDotColors: Record<string, string> = {
    pending: 'bg-amber-400',
    confirmed: 'bg-blue-500',
    shipped: 'bg-blue-600',
    delivered: 'bg-green-500',
    cancelled: 'bg-red-500',
};

export default function SellerOrders({ orders, stores, filters }: Props) {
    const statusOptions = [
        { value: '', label: 'All statuses' },
        { value: 'pending', label: 'Pending' },
        { value: 'confirmed', label: 'Confirmed' },
        { value: 'shipped', label: 'Shipped' },
        { value: 'delivered', label: 'Delivered' },
        { value: 'cancelled', label: 'Cancelled' },
    ];

    const handleFilter = (key: string, value: string) => {
        router.get(route('seller.orders.index'), {
            ...filters,
            [key]: value || undefined,
        }, { preserveState: true, replace: true });
    };

    const handleStatusUpdate = (orderId: number, status: string) => {
        router.patch(route('seller.orders.status', orderId), { status }, {
            preserveScroll: true,
        });
    };

    const nextStatuses: Record<string, { label: string; value: string }[]> = {
        pending: [
            { label: 'Confirm', value: 'confirmed' },
            { label: 'Cancel', value: 'cancelled' },
        ],
        confirmed: [
            { label: 'Ship', value: 'shipped' },
            { label: 'Cancel', value: 'cancelled' },
        ],
        shipped: [
            { label: 'Deliver', value: 'delivered' },
        ],
        delivered: [],
        cancelled: [],
    };

    return (
        <>
            <Head title="Orders" />
            <SellerLayout header="Orders">
                <div className="page-container py-6 sm:py-8 space-y-6">
                    {/* Filters */}
                    <div className="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                        {stores.length > 1 && (
                            <select
                                value={filters.store_id ?? ''}
                                onChange={(e) => handleFilter('store_id', e.target.value)}
                                className="input w-auto text-sm"
                            >
                                <option value="">All stores</option>
                                {stores.map((s) => (
                                    <option key={s.id} value={s.id}>{s.name}</option>
                                ))}
                            </select>
                        )}
                        <select
                            value={filters.status}
                            onChange={(e) => handleFilter('status', e.target.value)}
                            className="input w-auto text-sm"
                        >
                            {statusOptions.map((opt) => (
                                <option key={opt.value} value={opt.value}>{opt.label}</option>
                            ))}
                        </select>
                    </div>

                    {/* Orders list */}
                    {orders.data.length === 0 ? (
                        <div className="card text-center py-16">
                            <div className="size-16 rounded-full bg-brand-50 flex-center mx-auto mb-4">
                                <FunnelIcon className="w-8 h-8 text-brand-400" />
                            </div>
                            <h2 className="text-lg font-semibold text-content mb-1">No orders found</h2>
                            <p className="text-sm text-content-secondary">
                                {filters.status ? 'Try changing your filters.' : 'Orders will appear here once customers start buying.'}
                            </p>
                        </div>
                    ) : (
                        <div className="space-y-4">
                            {orders.data.map((order) => {
                                const actions = nextStatuses[order.my_store_status] ?? [];

                                return (
                                    <div key={order.id} className="card !p-0">
                                        <div className="px-4 sm:px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                            <div className="flex items-center gap-3">
                                                <div>
                                                    <p className="text-sm font-medium text-content">
                                                        Order #{order.id}
                                                    </p>
                                                    <p className="text-xs text-content-muted">
                                                        {order.customer ?? 'Guest'} &middot; {order.created_at}
                                                    </p>
                                                </div>
                                                <span className={`badge ${statusColors[order.my_store_status] ?? ''}`}>
                                                    {order.my_store_status.charAt(0).toUpperCase() + order.my_store_status.slice(1)}
                                                </span>
                                            </div>
                                            <div className="flex items-center gap-2">
                                                <span className="text-sm font-semibold text-content">
                                                    PHP {(order.total ?? 0).toLocaleString()}
                                                </span>
                                                {actions.length > 0 && (
                                                    <div className="flex items-center gap-1 ml-2">
                                                        {actions.map((action) => (
                                                            <button
                                                                key={action.value}
                                                                type="button"
                                                                onClick={() => handleStatusUpdate(order.id, action.value)}
                                                                className={`text-xs px-2.5 py-1 rounded-md font-medium transition-colors ${
                                                                    action.value === 'cancelled'
                                                                        ? 'text-red-600 hover:bg-red-50'
                                                                        : 'text-brand-700 hover:bg-brand-50'
                                                                }`}
                                                            >
                                                                {action.label}
                                                            </button>
                                                        ))}
                                                    </div>
                                                )}
                                            </div>
                                        </div>

                                        {/* Other stores status bar */}
                                        {order.other_stores && order.other_stores.length > 0 && (
                                            <div className="border-t border-border bg-amber-50/50 px-4 sm:px-6 py-2.5">
                                                <div className="flex items-center gap-2 text-xs text-amber-800 flex-wrap">
                                                    <span className="font-medium">Other stores in this order:</span>
                                                    {order.other_stores.map((os) => (
                                                        <span
                                                            key={os.store_id}
                                                            className={`inline-flex items-center gap-1 px-2 py-0.5 rounded-full ${
                                                                statusColors[os.status] ?? 'bg-gray-100 text-gray-700'
                                                            }`}
                                                        >
                                                            <span className={`w-1.5 h-1.5 rounded-full ${statusDotColors[os.status] ?? 'bg-gray-400'}`} />
                                                            {os.store_name}
                                                            <span className="capitalize opacity-75">({os.status})</span>
                                                        </span>
                                                    ))}
                                                </div>
                                            </div>
                                        )}

                                        {/* Items */}
                                        {order.items && order.items.length > 0 && (
                                            <div className="border-t border-border bg-surface-page/50 px-4 sm:px-6 py-3">
                                                <div className="space-y-2">
                                                    {order.items.map((item: any) => (
                                                        <div key={item.id} className="flex items-center justify-between text-sm">
                                                            <div className="flex items-center gap-2 min-w-0">
                                                                <div className="size-8 rounded bg-surface-raised flex-center shrink-0 overflow-hidden">
                                                                    {item.product?.images?.[0]?.path ? (
                                                                        <img
                                                                            src={item.product.images[0].path}
                                                                            alt={item.product_name}
                                                                            className="size-full object-cover"
                                                                        />
                                                                    ) : (
                                                                        <span className="text-xs text-content-muted">?</span>
                                                                    )}
                                                                </div>
                                                                <span className="text-content-secondary truncate">
                                                                    {item.product_name} &times; {item.quantity}
                                                                </span>
                                                            </div>
                                                            <span className="text-content font-medium">
                                                                PHP {Number(item.subtotal).toLocaleString()}
                                                            </span>
                                                        </div>
                                                    ))}
                                                </div>
                                            </div>
                                        )}
                                    </div>
                                );
                            })}
                        </div>
                    )}

                    {/* Pagination */}
                    {orders.meta && orders.meta.last_page > 1 && (
                        <div className="flex items-center justify-between text-sm text-content-muted pt-4">
                            <span>
                                Page {orders.meta.current_page} of {orders.meta.last_page}
                            </span>
                            <div className="flex gap-1">
                                {Array.from({ length: orders.meta.last_page }, (_, i) => i + 1).map((page) => (
                                    <Link
                                        key={page}
                                        href={route('seller.orders.index', { page })}
                                        preserveState
                                        className={`px-3 py-1.5 rounded-md text-sm font-medium transition-colors ${
                                            page === orders.meta.current_page
                                                ? 'bg-brand-50 text-brand-700'
                                                : 'text-content-secondary hover:bg-surface-raised'
                                        }`}
                                    >
                                        {page}
                                    </Link>
                                ))}
                            </div>
                        </div>
                    )}
                </div>
            </SellerLayout>
        </>
    );
}
