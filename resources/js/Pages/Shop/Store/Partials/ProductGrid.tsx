import { router } from '@inertiajs/react';
import { Product, PaginatedData } from '@/Types';
import ProductCard from '@/Components/Shared/ProductCard';
import { ShoppingBagIcon } from '@heroicons/react/24/outline';
import Reveal from '@/Components/Shared/Reveal';

interface Props {
    products: PaginatedData<Product>;
    currentCategory: number | null;
    slug: string;
}

function formatCount(n: number): string {
    return n.toLocaleString('en-US');
}

export default function ProductGrid({ products, currentCategory, slug }: Props) {
    const hasActiveFilters = currentCategory !== null;

    return (
        <Reveal className="w-full">
            <div className="page-container py-8">
            <div className="flex-between mb-5">
                <p className="text-sm text-content-muted">
                    {products.meta.total > 0
                        ? `Showing ${formatCount(products.meta.total)} ${products.meta.total === 1 ? 'product' : 'products'}`
                        : 'No products found'}
                </p>
                {hasActiveFilters && (
                    <button
                        onClick={() => router.get(route('stores.show', slug), { sort: undefined }, { preserveState: true, replace: true })}
                        className="text-xs text-content-link hover:underline"
                    >
                        Clear filter
                    </button>
                )}
            </div>

            {products.data.length > 0 ? (
                <>
                    <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                        {products.data.map((product) => (
                            <ProductCard key={product.id} product={product} />
                        ))}
                    </div>

                    {products.meta.last_page > 1 && (
                        <div className="flex-center gap-3 mt-12">
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
                    <ShoppingBagIcon className="w-12 h-12 text-content-disabled mb-4" />
                    <p className="text-content-muted text-sm">
                        {hasActiveFilters
                            ? 'No products in this category yet.'
                            : 'This store has no products yet.'}
                    </p>
                    {hasActiveFilters && (
                        <button
                            onClick={() => router.get(route('stores.show', slug), { category: undefined }, { preserveState: true, replace: true })}
                            className="btn-ghost text-sm mt-3"
                        >
                            View all products
                        </button>
                    )}
                </div>
            )}
        </div>
        </Reveal>
    );
}
