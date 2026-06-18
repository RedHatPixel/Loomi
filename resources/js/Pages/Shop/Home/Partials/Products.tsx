import ProductCard from "@/Components/Shared/ProductCard";
import Reveal from "@/Components/Shared/Reveal";
import { SORT_OPTIONS } from "@/Constants/products";
import { Category, PaginatedData, Product, Store } from "@/Types";
import { router } from "@inertiajs/react";

interface Filters {
    category: number | null;
    sort: string;
}

interface Props {
    products: PaginatedData<Product>;
    categories: Category[];
    filters: Filters;
}

export default function Products({ products, filters, categories }: Props) {
    const applyFilter = (key: string, value: string | number | null) => {
        router.get(route('home'), { ...filters, [key]: value }, {
            preserveState: true,
            replace: true,
        });
    };

    return (
        <>
            <section>
                <div className="flex items-center gap-2 flex-wrap">
                    <button
                        onClick={() => applyFilter('category', null)}
                        className={`px-3 py-1.5 rounded-full text-sm font-medium border transition-all ${
                            !filters.category
                                ? 'bg-brand-700 text-white border-brand-700 scale-105'
                                : 'bg-surface text-content border-border hover:border-brand-300'
                        }`}
                    >
                        All
                    </button>
                    {categories.map((cat) => (
                        <button
                            key={cat.id}
                            onClick={() => applyFilter('category', cat.id)}
                            className={`px-3 py-1.5 rounded-full text-sm font-medium border transition-all ${
                                filters.category === cat.id
                                    ? 'bg-brand-700 text-white border-brand-700 scale-105'
                                    : 'bg-surface text-content border-border hover:border-brand-300'
                            }`}
                        >
                            {cat.name}
                        </button>
                    ))}
                </div>
            </section>
            <section>
                <div className="flex-between mb-4 flex-wrap gap-3">
                    <div>
                        <h2 className="text-base font-semibold text-content">
                            All products
                        </h2>
                        <p className="text-xs text-content-muted mt-0.5">
                            {products.meta.total} items
                        </p>
                    </div>

                    <select
                        value={filters.sort}
                        onChange={(e) => applyFilter('sort', e.target.value)}
                        className="input w-auto text-sm py-1.5"
                    >
                        {SORT_OPTIONS.map((opt) => (
                            <option key={opt.value} value={opt.value}>
                                {opt.label}
                            </option>
                        ))}
                    </select>
                </div>

                {products.data.length > 0 ? (
                    <Reveal>
                        <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            {products.data.map((product) => (
                                <div key={product.id} className="transition-transform duration-300 hover:-translate-y-1">
                                    <ProductCard product={product} />
                                </div>
                            ))}
                        </div>

                        {products.meta.last_page > 1 && (
                            <div className="flex-center gap-2 mt-10">
                                {products.links?.prev && (
                                    <button onClick={() => router.get(products.links?.prev!)} className="btn-secondary text-sm">
                                        Previous
                                    </button>
                                )}
                                <span className="text-sm text-content-muted px-2">
                                    Page {products.meta.current_page} of {products.meta.last_page}
                                </span>
                                {products.links?.next && (
                                    <button onClick={() => router.get(products.links?.next!)} className="btn-secondary text-sm">
                                        Next
                                    </button>
                                )}
                            </div>
                        )}
                    </Reveal>
                ) : (
                    <div className="flex-center flex-col py-20 text-center">
                        <p className="text-content-muted text-sm">No products found.</p>
                        {filters.category && (
                            <button onClick={() => router.get(route('home'))} className="btn-ghost text-sm mt-3">
                                Clear filters
                            </button>
                        )}
                    </div>
                )}
            </section>
        </>
    )
}
