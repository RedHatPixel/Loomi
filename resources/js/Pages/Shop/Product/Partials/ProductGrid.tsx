import { router } from '@inertiajs/react';
import { Product, PaginatedData } from '@/Types';
import ProductCard from '@/Components/Shared/ProductCard';
import { SORT_OPTIONS } from '@/Constants/products';
import Reveal from '@/Components/Shared/Reveal';

interface Filters {
    search: string;
    category: number | null;
    sort: string;
    min_price: number | null;
    max_price: number | null;
}

interface Props {
    products: PaginatedData<Product>;
    filters: Filters;
    onApply: (key: string, value: string | number | null) => void;
    onClearAll: () => void;
    hasActiveFilters: boolean;
}

export default function ProductGrid({ products, filters, onApply, onClearAll, hasActiveFilters }: Props) {
    return (
        <Reveal className="w-full">
            <div className="flex-1 min-w-0 min-h-[100vh]">
            <div className="flex-between mb-5 flex-wrap gap-3">
                <div>
                    <h1 className="text-base font-semibold text-content">
                        {filters.search ? `Results for "${filters.search}"` : 'All products'}
                    </h1>
                    <p className="text-xs text-content-muted mt-0.5">{products.meta.total} items</p>
                </div>
                <select
                    value={filters.sort}
                    onChange={(e) => onApply('sort', e.target.value)}
                    className="input w-auto text-sm py-1.5"
                >
                    {SORT_OPTIONS.map((o) => (
                        <option key={o.value} value={o.value}>{o.label}</option>
                    ))}
                </select>
            </div>

            {products.data.length > 0 ? (
                <>
                    <div className="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
                        {products.data.map((product) => (
                            <ProductCard key={product.id} product={product} />
                        ))}
                    </div>

                    {products.meta.last_page > 1 && (
                        <div className="flex-center gap-2 mt-10">
                            {products.links?.prev && (
                                <button onClick={() => router.get(products.links!.prev!)} className="btn-secondary text-sm">
                                    Previous
                                </button>
                            )}
                            <span className="text-sm text-content-muted px-2">
                                Page {products.meta.current_page} of {products.meta.last_page}
                            </span>
                            {products.links?.next && (
                                <button onClick={() => router.get(products.links!.next!)} className="btn-secondary text-sm">
                                    Next
                                </button>
                            )}
                        </div>
                    )}
                </>
            ) : (
                <div className="flex-center flex-col py-24 text-center">
                    <p className="text-content-muted text-sm">No products found.</p>
                    {hasActiveFilters && (
                        <button onClick={onClearAll} className="btn-ghost text-sm mt-3">
                            Clear filters
                        </button>
                    )}
                </div>
            )}
        </div>
        </Reveal>
    );
}
