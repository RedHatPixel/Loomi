import { Link, router } from '@inertiajs/react';
import { Store, PaginatedData } from '@/Types';
import { ChevronRightIcon, BuildingStorefrontIcon } from '@heroicons/react/24/outline';
import { STORE_INDEX_SORT } from '@/Constants/stores';
import { nameHue } from '@/Utils/color';
import Reveal from '@/Components/Shared/Reveal';

interface Props {
    stores: PaginatedData<Store>;
    filters: { search: string; sort: string };
    hasActiveFilters: boolean;
    onApply: (key: string, value: string) => void;
}

function formatCount(n: number): string {
    return n.toLocaleString('en-US');
}

function StoreCard({ store }: { store: Store }) {
    const hasLogo = !!store.logo;
    const hue = nameHue(store.name);

    return (
        <Link
            href={route('stores.show', store.slug)}
            className="group flex flex-col bg-surface border border-border rounded-xl overflow-hidden hover:border-border-strong hover:shadow-sm transition-all duration-200"
        >
            <div className="relative h-28 bg-gradient-to-br from-brand-50/80 via-surface to-brand-100/40">
                {store.background_image && (
                    <img
                        src={store.background_image}
                        alt=""
                        className="absolute inset-0 w-full h-full object-cover"
                    />
                )}
                <div className="absolute inset-0 bg-gradient-to-t from-surface via-surface/30 to-transparent" />
                <div className="absolute -bottom-6 left-4 size-12 rounded-full bg-white shadow-md flex-center ring-2 ring-white overflow-hidden"
                    style={!hasLogo ? { backgroundColor: `hsl(${hue}, 20%, 85%)` } : undefined}
                >
                    {hasLogo ? (
                        <img src={store.logo!} alt={store.name} className="size-full object-cover" />
                    ) : (
                        <span
                            className="text-xs font-semibold text-center leading-tight px-1"
                            style={{ color: `hsl(${hue}, 40%, 30%)` }}
                        >
                            {store.name.length > 10 ? store.name.slice(0, 9) + '…' : store.name}
                        </span>
                    )}
                </div>
            </div>
            <div className="pt-8 pb-3 px-4 flex flex-col gap-1">
                <h3 className="text-sm font-semibold text-content leading-tight group-hover:text-brand-700 transition-colors">
                    {store.name}
                </h3>
                <p className="text-xs text-content-muted leading-relaxed line-clamp-2">
                    {store.description ?? ''}
                </p>
                <span className="text-[11px] text-content-secondary mt-1">
                    {formatCount(store.products_count ?? 0)} {store.products_count === 1 ? 'product' : 'products'}
                </span>
            </div>
        </Link>
    );
}

export default function StoreGrid({ stores, filters, hasActiveFilters, onApply }: Props) {
    return (
        <Reveal className="w-full">
            <div className="page-container py-8">
            {/* Sort bar */}
            <div className="flex items-center justify-between mb-5 flex-wrap gap-2">
                <p className="text-sm text-content-muted">
                    {stores.data.length > 0
                        ? `Showing ${formatCount(stores.meta.total)} ${stores.meta.total === 1 ? 'store' : 'stores'}`
                        : 'No stores found'}
                </p>
                <select
                    value={filters.sort}
                    onChange={(e) => onApply('sort', e.target.value)}
                    className="input w-auto text-sm py-1.5"
                >{STORE_INDEX_SORT.map((opt) => (
                                        <option key={opt.value} value={opt.value}>{opt.label}</option>
                                    ))}
                </select>
            </div>

            {stores.data.length > 0 ? (
                <>
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                        {stores.data.map((store) => (
                            <StoreCard key={store.id} store={store} />
                        ))}
                    </div>

                    {stores.meta.last_page > 1 && (
                        <div className="flex-center gap-3 mt-12">
                            {stores.links?.prev && (
                                <button onClick={() => router.get(stores.links!.prev!)} className="btn-secondary text-sm">
                                    Previous
                                </button>
                            )}
                            <span className="text-sm text-content-muted px-2">
                                Page {stores.meta.current_page} of {stores.meta.last_page}
                            </span>
                            {stores.links?.next && (
                                <button onClick={() => router.get(stores.links!.next!)} className="btn-secondary text-sm">
                                    Next
                                </button>
                            )}
                        </div>
                    )}
                </>
            ) : (
                <div className="flex-center flex-col py-24 text-center">
                    <BuildingStorefrontIcon className="w-12 h-12 text-content-disabled mb-4" />
                    <p className="text-content-muted text-sm">No stores found.</p>
                    {hasActiveFilters && (
                        <button onClick={() => onApply('search', '')} className="btn-ghost text-sm mt-3">
                            Clear search
                        </button>
                    )}
                </div>
            )}
        </div>
        </Reveal>
    );
}
