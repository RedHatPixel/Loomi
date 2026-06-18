import { ArrowTrendingUpIcon } from '@heroicons/react/24/outline';

interface SalesDay {
    date: string;
    total: number;
}

export default function SalesChart({ data }: { data: SalesDay[] }) {
    const max = Math.max(...data.map((d) => d.total), 1);
    const total = data.reduce((sum, d) => sum + d.total, 0);

    return (
        <div className="card !p-0 overflow-hidden">
            {/* Header */}
            <div className="px-6 pt-5 pb-4 border-b border-border">
                <div className="flex items-center justify-between">
                    <div>
                        <h3 className="text-sm font-semibold text-content flex items-center gap-2">
                            <ArrowTrendingUpIcon className="w-4 h-4 text-brand-600" />
                            Sales Trend
                        </h3>
                        <p className="text-xs text-content-muted mt-0.5">Last 7 days</p>
                    </div>
                    <p className="text-2xl font-bold text-content tabular-nums">
                        ₱{total.toLocaleString()}
                    </p>
                </div>
            </div>

            {/* Chart area */}
            <div className="px-6 py-6">
                {/* Y-axis labels + bars */}
                <div className="flex gap-3 h-48 sm:h-56">
                    {/* Y-axis */}
                    <div className="flex flex-col justify-between text-[10px] text-content-muted font-medium pr-2 pb-7">
                        <span>₱{max.toLocaleString()}</span>
                        <span>₱{Math.round(max / 2).toLocaleString()}</span>
                        <span>₱0</span>
                    </div>

                    {/* Bars container with subtle grid lines */}
                    <div className="flex-1 relative">
                        {/* Horizontal grid lines */}
                        <div className="absolute inset-0 flex flex-col justify-between pb-7">
                            <div className="border-t border-border/40" />
                            <div className="border-t border-border/40" />
                            <div className="border-t border-border/40" />
                        </div>

                        {/* Bars */}
                        <div className="relative h-full flex items-end gap-3 sm:gap-4 pb-7">
                            {data.map((day) => {
                                const pct = (day.total / max) * 100;
                                return (
                            <div key={day.date} className="flex-1 flex flex-col items-center h-full justify-end group relative">
                                <div
                                    className="w-full rounded-sm bg-gradient-to-t from-brand-600 to-brand-400 transition-all duration-300 group-hover:opacity-90 group-hover:shadow-sm min-h-[4px] cursor-pointer relative"
                                    style={{ height: `${Math.max(pct, 2)}%` }}
                                >
                                    {/* Value tooltip on hover — absolute to avoid layout shift */}
                                    <span className="absolute -top-5 left-1/2 -translate-x-1/2 text-[11px] font-semibold text-content opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                        ₱{day.total.toLocaleString()}
                                    </span>
                                </div>
                                <span className="text-[11px] text-content-muted font-medium mt-1.5">{day.date}</span>
                            </div>
                                );
                            })}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
