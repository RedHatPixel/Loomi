import { Head, Link } from '@inertiajs/react';
import { PageProps, Order, PaginatedData } from '@/Types';
import OrderStatusBadge from '@/Components/Customer/OrderStatusBadge';
import ClientLayout from '@/Layouts/ClientLayout';
import Reveal from '@/Components/Shared/Reveal';

interface Props extends PageProps {
    orders: PaginatedData<Order>;
}

const STATUS_ORDER: Record<string, number> = {
    pending: 0,
    confirmed: 1,
    shipped: 2,
    delivered: 3,
    cancelled: 4,
};

export default function OrdersIndex({ auth, orders }: Props) {
    const sorted = [...orders.data].sort(
        (a, b) => (STATUS_ORDER[a.status] ?? 99) - (STATUS_ORDER[b.status] ?? 99)
    );

    if (orders.data.length === 0) {
        return (
            <>
                <Head title="My Orders" />
                <ClientLayout>
                    <div className="flex-1 flex items-center justify-center py-24">
                        <div className="text-center px-4">
                            <div className="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-surface-raised">
                                <svg className="h-10 w-10 text-content-disabled" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z" />
                                </svg>
                            </div>
                            <h2 className="text-xl font-semibold text-content mb-2">No orders yet</h2>
                            <p className="text-content-muted text-sm mb-6 max-w-xs mx-auto">
                                You haven't placed any orders yet. Start browsing and find something you love.
                            </p>
                            <Link href={route('products.index')} className="btn-primary inline-flex items-center gap-2">
                                <svg className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                                Start shopping
                            </Link>
                        </div>
                    </div>
                </ClientLayout>
            </>
        );
    }

    return (
        <>
            <Head title="My Orders" />
            <ClientLayout>
                <div className="flex-1 page-container py-6 lg:py-10">
                    {/* Header */}
                    <Reveal>
                    <div className="mb-6 lg:mb-8">
                        <h1 className="text-2xl font-bold text-content">My Orders</h1>
                        <p className="text-sm text-content-muted mt-1">
                            {orders.meta.total} {orders.meta.total === 1 ? 'order' : 'orders'} total
                        </p>
                    </div>

                    {/* Orders list */}
                    <div className="space-y-3">
                        {sorted.map((order) => (
                            <Link
                                key={order.id}
                                href={route('orders.show', order.id)}
                                className="card flex items-start gap-4 p-4 sm:p-5 hover:shadow-md transition-all duration-200 group"
                            >
                                {/* Order number icon */}
                                <div className="hidden sm:flex size-12 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700 font-bold text-sm">
                                    #{order.id}
                                </div>

                                <div className="flex-1 min-w-0">
                                    <div className="flex items-center gap-3 mb-1">
                                        <span className="sm:hidden text-sm font-semibold text-content">Order #{order.id}</span>
                                        <OrderStatusBadge status={order.status} />
                                    </div>
                                    <div className="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-4 text-xs sm:text-sm">
                                        <span className="text-content-muted">
                                            {new Date(order.created_at).toLocaleDateString('en-PH', {
                                                year: 'numeric', month: 'long', day: 'numeric',
                                            })}
                                        </span>
                                        <span className="hidden sm:inline text-content-disabled">·</span>
                                        <span className="text-content-secondary">
                                            {order.items.length} {order.items.length === 1 ? 'item' : 'items'}
                                        </span>
                                        {order.items.length > 0 && (
                                            <>
                                                <span className="hidden sm:inline text-content-disabled">·</span>
                                                <span className="text-content-muted truncate max-w-[200px]">
                                                    {order.items.map(i => i.product_name).join(', ')}
                                                </span>
                                            </>
                                        )}
                                    </div>
                                </div>

                                <div className="text-right shrink-0">
                                    <p className="text-base font-bold text-content tabular-nums">
                                        ₱{Number(order.total).toLocaleString('en-PH', { minimumFractionDigits: 2 })}
                                    </p>
                                    <p className="text-xs text-content-link mt-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        View details →
                                    </p>
                                </div>
                            </Link>
                        ))}
                    </div>
                    </Reveal>

                    {/* Pagination */}
                    {orders.meta.last_page > 1 && (
                        <Reveal delay={200}>
                        <div className="flex items-center justify-center gap-3 mt-10">
                            {Array.from({ length: orders.meta.last_page }, (_, i) => i + 1).map((page) => (
                                <Link
                                    key={page}
                                    href={route('orders.index', { page })}
                                    preserveScroll
                                    className={`w-9 h-9 rounded-lg flex items-center justify-center text-sm font-medium transition-colors ${
                                        page === orders.meta.current_page
                                            ? 'bg-brand-600 text-white'
                                            : 'text-content-secondary hover:bg-surface-raised hover:text-content'
                                    }`}
                                >
                                    {page}
                                </Link>
                            ))}
                        </div>
                        </Reveal>
                    )}
                </div>
            </ClientLayout>
        </>
    );
}
