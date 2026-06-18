import { OrderStatus } from '@/Types';

const config: Record<OrderStatus, { label: string; className: string }> = {
    pending:   { label: 'Pending',   className: 'badge-warning' },
    confirmed: { label: 'Confirmed', className: 'badge-info' },
    shipped:   { label: 'Shipped',   className: 'badge-info' },
    delivered: { label: 'Delivered', className: 'badge-success' },
    cancelled: { label: 'Cancelled', className: 'badge-danger' },
};

export default function OrderStatusBadge({ status }: { status: OrderStatus }) {
    const { label, className } = config[status] ?? config.pending;
    return <span className={`badge ${className}`}>{label}</span>;
}
