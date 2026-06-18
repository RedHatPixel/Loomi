import { Link } from '@inertiajs/react';
import { Store } from '@/Types';
import { nameHue } from '@/Utils/color';

interface Props {
    store: Store;
}

export default function StoreCard({ store }: Props) {
    const hasLogo = !!store.logo;
    const hue = nameHue(store.name);

    return (
        <Link
            href={route('stores.show', store.slug)}
            className="flex items-center gap-3 px-4 py-3 bg-surface border border-border rounded-xl hover:border-brand-300 hover:bg-brand-50 transition-all group"
        >
            <div
                className="size-10 rounded-full flex-center shrink-0 overflow-hidden"
                style={!hasLogo ? { backgroundColor: `hsl(${hue}, 20%, 85%)` } : undefined}
            >
                {hasLogo ? (
                    <img src={store.logo!} alt={store.name} className="size-full object-cover" />
                ) : (
                    <span
                        className="text-xs font-semibold text-center leading-tight px-1"
                        style={{ color: `hsl(${hue}, 40%, 30%)` }}
                    >
                        {store.name.length > 8 ? store.name.slice(0, 7) + '…' : store.name}
                    </span>
                )}
            </div>
            <div className="min-w-0">
                <p className="text-sm font-medium text-content truncate group-hover:text-brand-700 transition-colors">
                    {store.name}
                </p>
                <p className="text-xs text-content-muted">
                    {store.products_count ?? 0} products
                </p>
            </div>
        </Link>
    );
}
