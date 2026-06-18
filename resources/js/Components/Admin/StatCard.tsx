import { ChartBarIcon } from '@heroicons/react/24/outline';

interface StatCardProps {
    icon: React.ComponentType<{ className?: string }>;
    label: string;
    value: string | number;
    color?: string;
    subtext?: string;
}

export default function StatCard({ icon: Icon, label, value, color, subtext }: StatCardProps) {
    return (
        <div className="card flex items-start gap-3">
            <span className={`flex-center size-10 rounded-lg shrink-0 ${color ?? 'bg-brand-50 text-brand-700'}`}>
                <Icon className="w-5 h-5" />
            </span>
            <div className="min-w-0 flex-1">
                <p className="text-xs text-content-muted">{label}</p>
                <p className="text-lg font-semibold text-content mt-0.5">{value}</p>
                {subtext && <p className="text-[10px] text-content-muted mt-0.5">{subtext}</p>}
            </div>
        </div>
    );
}
