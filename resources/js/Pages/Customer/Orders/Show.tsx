import { Head, Link, router } from '@inertiajs/react';
import { PageProps, Order, OrderItem, OrderStatus } from '@/Types';
import OrderStatusBadge from '@/Components/Customer/OrderStatusBadge';
import ConfirmDialog from '@/Components/UI/ConfirmDialog';
import { storageUrl } from '@/Utils/storage';
import { useState } from 'react';
import ClientLayout from '@/Layouts/ClientLayout';
import Reveal from '@/Components/Shared/Reveal';

interface Props extends PageProps {
    order: Order;
}

const statusTimeline = ['pending', 'confirmed', 'shipped', 'delivered'] as const;
const statusPriority: Record<string, number> = { cancelled: 0, pending: 1, confirmed: 2, shipped: 3, delivered: 4 };

function storeStatus(items: OrderItem[]): OrderStatus {
    const nonCancelled = items.filter((i) => i.status !== 'cancelled');
    if (nonCancelled.length === 0) return 'cancelled';
    const lowest = nonCancelled.sort(
        (a, b) => (statusPriority[a.status] ?? 0) - (statusPriority[b.status] ?? 0)
    )[0];
    return lowest.status;
}

function groupByStore(items: OrderItem[]): { storeName: string; storeId: number; items: OrderItem[]; status: OrderStatus }[] {
    const map = new Map<string, { storeName: string; storeId: number; items: OrderItem[] }>();
    for (const item of items) {
        const key = `store-${item.store_id}`;
        if (!map.has(key)) {
            map.set(key, { storeName: item.store_name, storeId: item.store_id, items: [] });
        }
        map.get(key)!.items.push(item);
    }
    return Array.from(map.values()).map((g) => ({
        ...g,
        status: storeStatus(g.items),
    }));
}

function Dots({ count, current }: { count: number; current: number }) {
    return (
        <div className="flex items-center gap-1">
            {Array.from({ length: count }, (_, i) => (
                <div
                    key={i}
                    className={`h-1.5 rounded-full transition-all duration-300 ${
                        i <= current
                            ? 'bg-brand-600'
                            : 'bg-border'
                    } ${i === current ? 'w-3' : 'w-1.5'}`}
                />
            ))}
        </div>
    );
}

function StoreTimeline({ status }: { status: string }) {
    const timelineIndex = statusTimeline.indexOf(status as typeof statusTimeline[number]);
    if (timelineIndex === -1) return null; // cancelled

    return (
        <>
            {/* Mobile: compact dots */}
            <div className="flex sm:hidden flex-col gap-1">
                <Dots count={statusTimeline.length} current={timelineIndex} />
                <span className="text-[11px] font-medium capitalize text-brand-700">
                    {status}
                </span>
            </div>

            {/* Desktop: full timeline */}
            <div className="hidden sm:flex items-center gap-1.5 overflow-x-auto pb-1">
                {statusTimeline.map((s, idx) => {
                    const isComplete = idx <= timelineIndex;
                    const isCurrent = idx === timelineIndex;
                    return (
                        <div key={s} className="flex items-center gap-1.5 shrink-0">
                            {/* Dot */}
                            <div className={`size-[18px] rounded-full flex items-center justify-center shrink-0 ${
                                isComplete
                                    ? 'bg-brand-600 text-white'
                                    : 'bg-surface-raised text-content-disabled border border-border'
                            }`}>
                                {isComplete ? (
                                    <svg className="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={3}>
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                ) : (
                                    <span className="w-1 h-1 rounded-full bg-current" />
                                )}
                            </div>
                            {/* Label */}
                            <span className={`text-[11px] font-medium capitalize ${
                                isComplete ? 'text-content' : 'text-content-muted'
                            } ${isCurrent ? 'text-brand-700 font-semibold' : ''}`}>
                                {s}
                            </span>
                            {/* Connector */}
                            {idx < statusTimeline.length - 1 && (
                                <div className={`w-5 h-px mx-0.5 ${
                                    idx < timelineIndex ? 'bg-brand-600' : 'bg-border'
                                }`} />
                            )}
                        </div>
                    );
                })}
            </div>
        </>
    );
}

export default function OrderShow({ auth, order }: Props) {
    const [cancelling, setCancelling] = useState(false);
    const [cancellingStore, setCancellingStore] = useState<number | null>(null);
    const [confirmCancelAll, setConfirmCancelAll] = useState(false);
    const [confirmCancelStore, setConfirmCancelStore] = useState<number | null>(null);

    const confirmAndCancelStore = (storeId: number) => {
        setConfirmCancelStore(storeId);
    };

    const doCancelStore = () => {
        if (confirmCancelStore === null) return;
        setCancellingStore(confirmCancelStore);
        setConfirmCancelStore(null);
        router.patch(route('orders.cancelStore', [order.id, confirmCancelStore]), {}, {
            onFinish: () => {
                setCancellingStore(null);
                router.reload();
            },
        });
    };

    const doCancelAll = () => {
        setCancelling(true);
        setConfirmCancelAll(false);
        router.patch(route('orders.cancel', order.id), {}, {
            onFinish: () => {
                setCancelling(false);
                router.reload();
            },
        });
    };

    const timelineIndex = statusTimeline.indexOf(order.status as typeof statusTimeline[number]);
    const isCancelled = order.status === 'cancelled';
    const storeGroups = groupByStore(order.items);

    return (
        <>
            <Head title={`Order #${order.id}`} />
            <ClientLayout>
                <div className="flex-1 page-container py-6 lg:py-10">
                    {/* Back link + header */}
                    <Reveal>
                    <div className="mb-6 lg:mb-8">
                        <Link
                            href={route('orders.index')}
                            className="inline-flex items-center gap-1.5 text-sm text-content-muted hover:text-content transition-colors mb-3"
                        >
                            <svg className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                                <path strokeLinecap="round" strokeLinejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                            </svg>
                            Back to orders
                        </Link>
                        <div className="flex items-center gap-3">
                            <h1 className="text-2xl font-bold text-content">Order #{order.id}</h1>
                            <OrderStatusBadge status={order.status} />
                        </div>
                        <p className="text-sm text-content-muted mt-1">
                            Placed on{' '}
                            {new Date(order.created_at).toLocaleDateString('en-PH', {
                                year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit',
                            })}
                        </p>
                    </div>
                    </Reveal>

                    <div className="grid lg:grid-cols-3 gap-6 lg:gap-8">

                        {/* ── Main column: per-store items + overall timeline ── */}
                        <Reveal delay={100} className="lg:col-span-2 space-y-6">
                        <div className="lg:col-span-2 space-y-6">

                            {/* Per-store sections */}
                            {storeGroups.map((group) => {
                                const groupCancelled = group.status === 'cancelled';
                                return (
                                    <div key={group.storeId} className="card p-5 sm:p-6">
                                        {/* Store header */}
                                        <div className="flex items-center justify-between mb-4">
                                            <div className="flex items-center gap-2.5">
                                                <div className="size-8 rounded-lg bg-brand-50 flex items-center justify-center text-brand-700 font-bold text-xs">
                                                    {group.storeName.charAt(0).toUpperCase()}
                                                </div>
                                                <div>
                                                    <h3 className="text-sm font-semibold text-content">{group.storeName}</h3>
                                                    <p className="text-xs text-content-muted">{group.items.length} {group.items.length === 1 ? 'item' : 'items'}</p>
                                                </div>
                                            </div>
                                            <OrderStatusBadge status={group.status} />
                                        </div>

                                        {/* Items in this store */}
                                        <div className="divide-y divide-border -mx-5 sm:-mx-6">
                                            {group.items.map((item, idx) => (
                                                <div key={item.id} className={`flex gap-4 ${idx === 0 ? 'pb-3' : 'py-3'} ${idx === group.items.length - 1 ? 'pb-0' : ''} px-5 sm:px-6`}>
                                                    <div className="size-14 sm:size-16 rounded-lg bg-surface-raised overflow-hidden shrink-0">
                                                        {item.image ? (
                                                            <img src={storageUrl(item.image)} alt={item.product_name} className="w-full h-full object-cover" />
                                                        ) : (
                                                            <div className="w-full h-full flex items-center justify-center text-content-disabled">
                                                                <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
                                                                    <path strokeLinecap="round" strokeLinejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                </svg>
                                                            </div>
                                                        )}
                                                    </div>
                                                    <div className="flex-1 min-w-0">
                                                        <div className="flex items-start justify-between gap-2">
                                                            <div className="min-w-0 flex-1">
                                                                {item.slug ? (
                                                                    <Link href={route('products.show', item.slug)} className="text-sm font-medium text-content hover:text-brand-700 transition-colors line-clamp-1">
                                                                        {item.product_name}
                                                                    </Link>
                                                                ) : (
                                                                    <p className="text-sm font-medium text-content line-clamp-1">{item.product_name}</p>
                                                                )}
                                                            </div>
                                                            <p className="text-sm font-semibold text-content tabular-nums shrink-0">
                                                                ₱{Number(item.subtotal).toLocaleString('en-PH', { minimumFractionDigits: 2 })}
                                                            </p>
                                                        </div>
                                                        <div className="flex items-center gap-2 mt-1">
                                                            <p className="text-xs text-content-muted">
                                                                ₱{Number(item.unit_price).toLocaleString('en-PH', { minimumFractionDigits: 2 })} × {item.quantity}
                                                            </p>
                                                            <OrderStatusBadge status={item.status} />
                                                        </div>
                                                    </div>
                                                </div>
                                            ))}
                                        </div>

                                        {/* Per-store progress timeline + cancel button */}
                                        {!groupCancelled && (
                                            <div className="mt-4 pt-3 border-t border-border">
                                                <div className="sm:flex sm:items-center sm:justify-between gap-3">
                                                    <StoreTimeline status={group.status} />
                                                    {group.status === 'pending' && (
                                                        <button
                                                            onClick={() => confirmAndCancelStore(group.storeId)}
                                                            disabled={cancellingStore === group.storeId}
                                                            className="flex items-center gap-1.5 text-xs text-status-danger hover:text-red-700 transition-colors font-medium shrink-0 disabled:opacity-50 mt-2 sm:mt-0"
                                                        >
                                                            {cancellingStore === group.storeId ? (
                                                                <>
                                                                    <svg className="animate-spin h-3 w-3" fill="none" viewBox="0 0 24 24">
                                                                        <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                                                                        <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                                                    </svg>
                                                                    Cancelling…
                                                                </>
                                                            ) : (
                                                                <>
                                                                    <svg className="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                                                                        <path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                                    </svg>
                                                                    Cancel from this store
                                                                </>
                                                            )}
                                                        </button>
                                                    )}
                                                </div>
                                            </div>
                                        )}

                                        {/* Cancelled notice for this store */}
                                        {groupCancelled && (
                                            <div className="mt-4 pt-3 border-t border-border">
                                                <div className="flex items-center gap-2 text-xs text-status-danger">
                                                    <svg className="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                                                        <path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    Items from this store have been cancelled
                                                </div>
                                            </div>
                                        )}
                                    </div>
                                );
                            })}

                            {/* Overall Order Progress */}
                            {!isCancelled && (
                                <div className="card p-5 sm:p-6">
                                    <h2 className="text-base font-semibold text-content mb-5 flex items-center gap-2">
                                        <svg className="w-5 h-5 text-content-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Overall Order Progress
                                    </h2>

                                    {/* Store summary chips */}
                                    {storeGroups.length > 1 && (
                                        <div className="flex flex-wrap gap-2 mb-5">
                                            {storeGroups.map((g) => (
                                                <div key={g.storeId} className="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-surface-raised text-xs">
                                                    <span className="font-medium text-content">{g.storeName}</span>
                                                    <span className={`w-1.5 h-1.5 rounded-full ${
                                                        g.status === 'delivered' ? 'bg-status-success' :
                                                        g.status === 'cancelled' ? 'bg-status-danger' :
                                                        g.status === 'shipped' ? 'bg-brand-500' :
                                                        g.status === 'confirmed' ? 'bg-blue-400' :
                                                        'bg-amber-400'
                                                    }`} />
                                                    <span className="text-content-muted capitalize">{g.status}</span>
                                                </div>
                                            ))}
                                        </div>
                                    )}

                                    <div className="relative">
                                        {/* Connecting line */}
                                        <div className="absolute left-[11px] top-3 bottom-3 w-0.5 bg-border" />
                                        <div className="space-y-6">
                                            {statusTimeline.map((status, idx) => {
                                                const isComplete = idx <= timelineIndex;
                                                const isCurrent = idx === timelineIndex;
                                                return (
                                                    <div key={status} className="flex items-start gap-4">
                                                        <div className={`relative z-10 mt-0.5 size-[22px] rounded-full flex items-center justify-center shrink-0 ${
                                                            isComplete
                                                                ? 'bg-brand-600 text-white'
                                                                : 'bg-surface-raised text-content-disabled border border-border'
                                                        }`}>
                                                            {isComplete ? (
                                                                <svg className="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={3}>
                                                                    <path strokeLinecap="round" strokeLinejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                </svg>
                                                            ) : (
                                                                <span className="w-1.5 h-1.5 rounded-full bg-current" />
                                                            )}
                                                        </div>
                                                        <div>
                                                            <p className={`text-sm font-medium capitalize ${
                                                                isComplete ? 'text-content' : 'text-content-muted'
                                                            }`}>
                                                                {status}
                                                                {isCurrent && (
                                                                    <span className="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-brand-50 text-brand-700">
                                                                        Current
                                                                    </span>
                                                                )}
                                                            </p>
                                                            <p className="text-xs text-content-muted mt-0.5">
                                                                {isComplete ? 'Completed' : 'Pending'}
                                                            </p>
                                                        </div>
                                                    </div>
                                                );
                                            })}
                                        </div>
                                    </div>
                                </div>
                            )}

                            {/* Cancelled notice */}
                            {isCancelled && (
                                <div className="card p-5 sm:p-6 border-status-danger/20">
                                    <div className="flex items-start gap-3">
                                        <div className="size-10 shrink-0 rounded-full bg-red-50 flex items-center justify-center">
                                            <svg className="w-5 h-5 text-status-danger" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                                                <path strokeLinecap="round" strokeLinejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 className="text-sm font-semibold text-content">Order Cancelled</h3>
                                            <p className="text-xs text-content-muted mt-1">
                                                This order has been cancelled and no further action is needed.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            )}
                        </div>
                        </Reveal>

                        {/* ── Sidebar: details + actions ── */}
                        <Reveal delay={200} className="space-y-4">
                        <div className="space-y-4">
                            {/* Details */}
                            <div className="card p-5 sm:p-6">
                                <h2 className="text-base font-semibold text-content mb-4 flex items-center gap-2">
                                    <svg className="w-5 h-5 text-content-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" />
                                    </svg>
                                    Order Details
                                </h2>
                                <dl className="space-y-3 text-sm">
                                    <div className="flex justify-between">
                                        <dt className="text-content-muted">Status</dt>
                                        <dd><OrderStatusBadge status={order.status} /></dd>
                                    </div>
                                    <div className="flex justify-between">
                                        <dt className="text-content-muted">Date</dt>
                                        <dd className="text-content">
                                            {new Date(order.created_at).toLocaleDateString('en-PH', {
                                                month: 'short', day: 'numeric', year: 'numeric',
                                            })}
                                        </dd>
                                    </div>
                                    <div className="flex justify-between">
                                        <dt className="text-content-muted">Stores</dt>
                                        <dd className="text-content">{storeGroups.length}</dd>
                                    </div>
                                    <div className="flex justify-between">
                                        <dt className="text-content-muted">Items</dt>
                                        <dd className="text-content">{order.items.length}</dd>
                                    </div>
                                    <div className="flex justify-between">
                                        <dt className="text-content-muted">Total</dt>
                                        <dd className="text-content font-semibold tabular-nums">
                                            ₱{Number(order.total).toLocaleString('en-PH', { minimumFractionDigits: 2 })}
                                        </dd>
                                    </div>
                                </dl>
                            </div>

                            {/* Payment */}
                            {order.payment_method && (
                                <div className="card p-5 sm:p-6">
                                    <h3 className="text-sm font-semibold text-content mb-3 flex items-center gap-2">
                                        <svg className="w-4 h-4 text-content-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                        </svg>
                                        Payment
                                    </h3>
                                    <dl className="space-y-2 text-sm">
                                        <div className="flex justify-between">
                                            <dt className="text-content-muted">Method</dt>
                                            <dd className="text-content font-medium capitalize">
                                                {order.payment_method === 'cod' ? 'Cash on Delivery' : 'Online Payment'}
                                            </dd>
                                        </div>
                                        {order.payment_method === 'prepaid' && order.payment_details && (() => {
                                            try {
                                                const details = JSON.parse(order.payment_details);
                                                return (
                                                    <>
                                                        <div className="flex justify-between">
                                                            <dt className="text-content-muted">Card</dt>
                                                            <dd className="text-content capitalize">{details.card_type}</dd>
                                                        </div>
                                                        <div className="flex justify-between">
                                                            <dt className="text-content-muted">Number</dt>
                                                            <dd className="text-content tabular-nums">{details.card_number}</dd>
                                                        </div>
                                                    </>
                                                );
                                            } catch {
                                                return null;
                                            }
                                        })()}
                                    </dl>
                                </div>
                            )}

                            {/* Notes */}
                            {order.notes && (
                                <div className="card p-5 sm:p-6">
                                    <h3 className="text-sm font-semibold text-content mb-2">Order Notes</h3>
                                    <p className="text-sm text-content-secondary">{order.notes}</p>
                                </div>
                            )}

                            {/* Address */}
                            {order.address && (
                                <div className="card p-5 sm:p-6">
                                    <h3 className="text-sm font-semibold text-content mb-2">Shipping Address</h3>
                                    <p className="text-sm text-content-secondary whitespace-pre-line">
                                        {order.address.line1}
                                        {order.address.line2 ? `\n${order.address.line2}` : ''}
                                        {`\n${order.address.city}, ${order.address.province} ${order.address.postal_code}`}
                                        {order.address.country ? `\n${order.address.country}` : ''}
                                    </p>
                                </div>
                            )}

                            {/* Cancel entire order — only if ALL items are still pending */}
                            {storeGroups.every((g) => g.status === 'pending') && (
                                <button
                                    onClick={() => setConfirmCancelAll(true)}
                                    disabled={cancelling}
                                    className="w-full py-2.5 text-sm flex items-center justify-center gap-2 rounded-lg border border-status-danger/30 text-status-danger hover:bg-red-50 hover:border-status-danger transition-colors font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    {cancelling ? (
                                        <>
                                            <svg className="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                                <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                                                <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                            </svg>
                                            Cancelling…
                                        </>
                                    ) : (
                                        <>
                                            <svg className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                                                <path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Cancel entire order
                                        </>
                                    )}
                                </button>
                            )}
                        </div>
                        </Reveal>

                    </div>
                </div>

                {/* Confirm cancel all */}
                <ConfirmDialog
                    open={confirmCancelAll}
                    onClose={() => setConfirmCancelAll(false)}
                    onConfirm={doCancelAll}
                    title="Cancel entire order?"
                    message="Are you sure you want to cancel this entire order? This action cannot be undone."
                    confirmText="Cancel order"
                    variant="danger"
                    loading={cancelling}
                />

                {/* Confirm cancel store */}
                <ConfirmDialog
                    open={confirmCancelStore !== null}
                    onClose={() => setConfirmCancelStore(null)}
                    onConfirm={doCancelStore}
                    title="Cancel items from this store?"
                    message="Are you sure you want to cancel items from this store? This action cannot be undone."
                    confirmText="Cancel items"
                    variant="danger"
                    loading={cancellingStore !== null}
                />
            </ClientLayout>
        </>
    );
}
