import { Head, Link, router } from '@inertiajs/react';
import { PageProps } from '@/Types';
import AdminLayout from '@/Layouts/AdminLayout';
import { ArrowLeftIcon, TrashIcon } from '@heroicons/react/24/outline';
import { useState } from 'react';
import Reveal from '@/Components/Shared/Reveal';

interface OrderItem {
    id: number;
    product_name: string;
    unit_price: number;
    quantity: number;
    subtotal: number;
    status: string;
    store_name: string;
    image: string | null;
}

interface OrderData {
    id: number;
    status: string;
    total: number;
    notes: string | null;
    payment_method: string | null;
    payment_details: string | null;
    customer: string;
    customer_email: string;
    created_at: string;
    address: {
        line1: string;
        line2: string | null;
        city: string;
        province: string;
        postal_code: string;
        country: string;
    } | null;
    items: OrderItem[];
}

interface Props extends PageProps {
    orderData: OrderData;
}

const statusColors: Record<string, string> = {
    pending: 'bg-amber-100 text-amber-800',
    confirmed: 'bg-blue-100 text-blue-800',
    shipped: 'bg-sky-100 text-sky-800',
    delivered: 'bg-green-100 text-green-800',
    cancelled: 'bg-red-100 text-red-800',
};

const statusOptions = [
    { value: 'pending', label: 'Pending' },
    { value: 'confirmed', label: 'Confirmed' },
    { value: 'shipped', label: 'Shipped' },
    { value: 'delivered', label: 'Delivered' },
    { value: 'cancelled', label: 'Cancelled' },
];

export default function AdminOrdersShow({ orderData }: Props) {
    const [status, setStatus] = useState(orderData.status);

    const updateStatus = () => {
        router.patch(route('admin.orders.status', orderData.id), { status }, { preserveScroll: true });
    };

    const handleDelete = () => {
        if (!confirm(`Delete order #${orderData.id}? This cannot be undone.`)) return;
        router.delete(route('admin.orders.destroy', orderData.id));
    };

    return (
        <>
            <Head title={`Order #${orderData.id}`} />
            <AdminLayout header={`Order #${orderData.id}`}>
                <div className="page-container py-6 sm:py-8 space-y-6">
                    <Link href={route('admin.orders.index')} className="inline-flex items-center gap-1.5 text-sm text-content-muted hover:text-content transition-colors">
                        <ArrowLeftIcon className="w-4 h-4" />
                        Back to orders
                    </Link>

                    <div className="grid lg:grid-cols-3 gap-6">
                        {/* Main column */}
                        <Reveal className="lg:col-span-2 space-y-4">
                            {/* Order header */}
                            <div className="card p-5 sm:p-6">
                                <div className="flex items-start justify-between gap-4">
                                    <div>
                                        <h2 className="text-xl font-semibold text-content">Order #{orderData.id}</h2>
                                        <p className="text-sm text-content-muted">{orderData.created_at}</p>
                                        <p className="text-sm text-content-muted">{orderData.customer} &middot; {orderData.customer_email}</p>
                                    </div>
                                    <div className="flex items-center gap-2">
                                        <select value={status} onChange={(e) => setStatus(e.target.value)} className="input text-sm py-1.5 w-auto">
                                            {statusOptions.map((opt) => (<option key={opt.value} value={opt.value}>{opt.label}</option>))}
                                        </select>
                                        <button type="button" onClick={updateStatus} className="btn-primary text-xs px-3 py-1.5">Update</button>
                                        <button type="button" onClick={handleDelete} className="btn-ghost p-2 text-red-500 hover:bg-red-50" aria-label="Delete order">
                                            <TrashIcon className="w-4 h-4" />
                                        </button>
                                    </div>
                                </div>
                                <div className="flex items-center gap-2 mt-3">
                                    <span className={`px-2.5 py-0.5 rounded-full text-xs font-medium capitalize ${statusColors[orderData.status] ?? ''}`}>
                                        {orderData.status}
                                    </span>
                                    <span className="text-xl font-bold text-content">PHP {orderData.total.toLocaleString()}</span>
                                </div>
                            </div>

                            {/* Items */}
                            <div className="card !p-0 overflow-hidden">
                                <div className="px-5 py-3 border-b border-border">
                                    <h3 className="text-sm font-semibold text-content">Items ({orderData.items.length})</h3>
                                </div>
                                <div className="divide-y divide-border">
                                    {orderData.items.map((item) => (
                                        <div key={item.id} className="flex items-center gap-3 px-4 sm:px-5 py-3">
                                            <div className="size-10 rounded-lg bg-surface-raised flex-center shrink-0 overflow-hidden">
                                                {item.image ? (
                                                    <img src={item.image} alt={item.product_name} className="size-full object-cover" />
                                                ) : (
                                                    <span className="text-xs text-content-muted">?</span>
                                                )}
                                            </div>
                                            <div className="flex-1 min-w-0">
                                                <p className="text-sm font-medium text-content">{item.product_name}</p>
                                                <p className="text-xs text-content-muted">{item.store_name} &middot; ₱{item.unit_price.toLocaleString()} × {item.quantity}</p>
                                            </div>
                                            <div className="text-right">
                                                <p className="text-sm font-semibold text-content">PHP {item.subtotal.toLocaleString()}</p>
                                                <span className={`px-1.5 py-0.5 rounded text-[10px] font-medium capitalize ${statusColors[item.status] ?? ''}`}>
                                                    {item.status}
                                                </span>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </Reveal>

                        {/* Sidebar */}
                        <Reveal delay={100} className="space-y-4">
                            {/* Payment */}
                            <div className="card p-5 sm:p-6">
                                <h3 className="text-sm font-semibold text-content mb-3">Payment</h3>
                                <dl className="space-y-2 text-sm">
                                    <div className="flex justify-between">
                                        <dt className="text-content-muted">Method</dt>
                                        <dd className="text-content font-medium capitalize">{orderData.payment_method ?? 'N/A'}</dd>
                                    </div>
                                    <div className="flex justify-between">
                                        <dt className="text-content-muted">Total</dt>
                                        <dd className="text-content font-semibold">PHP {orderData.total.toLocaleString()}</dd>
                                    </div>
                                </dl>
                            </div>

                            {/* Address */}
                            {orderData.address && (
                                <div className="card p-5 sm:p-6">
                                    <h3 className="text-sm font-semibold text-content mb-2">Shipping Address</h3>
                                    <p className="text-sm text-content-secondary whitespace-pre-line">
                                        {orderData.address.line1}
                                        {orderData.address.line2 ? `\n${orderData.address.line2}` : ''}
                                        {`\n${orderData.address.city}, ${orderData.address.province} ${orderData.address.postal_code}`}
                                    </p>
                                </div>
                            )}

                            {/* Notes */}
                            {orderData.notes && (
                                <div className="card p-5 sm:p-6">
                                    <h3 className="text-sm font-semibold text-content mb-2">Notes</h3>
                                    <p className="text-sm text-content-secondary">{orderData.notes}</p>
                                </div>
                            )}
                        </Reveal>
                    </div>
                </div>
            </AdminLayout>
        </>
    );
}
