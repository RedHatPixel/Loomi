import { ShoppingBagIcon } from '@heroicons/react/24/outline';

interface StatusItem {
    status: string;
    count: number;
}

const statusConfig: Record<string, { label: string; barColor: string; dotColor: string }> = {
    pending:   { label: 'Pending',   barColor: 'bg-amber-400', dotColor: 'bg-amber-400' },
    confirmed: { label: 'Confirmed', barColor: 'bg-blue-500',  dotColor: 'bg-blue-500' },
    shipped:   { label: 'Shipped',   barColor: 'bg-sky-500',   dotColor: 'bg-sky-500' },
    delivered: { label: 'Delivered', barColor: 'bg-green-500', dotColor: 'bg-green-500' },
    cancelled: { label: 'Cancelled', barColor: 'bg-red-400',   dotColor: 'bg-red-400' },
};

export default function OrderStatusChart({ data }: { data: StatusItem[] }) {
    const total = data.reduce((sum, d) => sum + d.count, 0);
    const maxCount = Math.max(...data.map((d) => d.count), 1);

    return (
        <div className="card !p-0 overflow-hidden">
            {/* Header */}
            <div className="px-6 pt-5 pb-4 border-b border-border">
                <div className="flex items-center justify-between">
                    <div>
                        <h3 className="text-sm font-semibold text-content flex items-center gap-2">
                            <ShoppingBagIcon className="w-4 h-4 text-brand-600" />
                            Order Status
                        </h3>
                        <p className="text-xs text-content-muted mt-0.5">All time breakdown</p>
                    </div>
                    <p className="text-2xl font-bold text-content tabular-nums">{total}</p>
                </div>
            </div>

            {/* Horizontal bar rows */}
            <div className="px-6 py-5 space-y-4">
                {data.map((item) => {
                    const config = statusConfig[item.status];
                    const pct = (item.count / maxCount) * 100;

                    return (
                        <div key={item.status} className="space-y-1.5">
                            <div className="flex items-center justify-between text-sm">
                                <div className="flex items-center gap-2">
                                    <span className={`w-2.5 h-2.5 rounded-full shrink-0 ${config?.dotColor ?? 'bg-gray-300'}`} />
                                    <span className="font-medium text-content">{config?.label ?? item.status}</span>
                                </div>
                                <div className="flex items-center gap-3">
                                    <span className="text-xs text-content-muted">
                                        {total > 0 ? `${Math.round((item.count / total) * 100)}%` : '0%'}
                                    </span>
                                    <span className="font-semibold text-content tabular-nums w-8 text-right">
                                        {item.count}
                                    </span>
                                </div>
                            </div>
                            <div className="h-2.5 rounded-full bg-gray-100 overflow-hidden">
                                <div
                                    className={`h-full rounded-full transition-all duration-500 ${config?.barColor ?? 'bg-gray-300'}`}
                                    style={{ width: `${Math.max(pct, item.count > 0 ? 4 : 0)}%` }}
                                />
                            </div>
                        </div>
                    );
                })}
            </div>
        </div>
    );
}
