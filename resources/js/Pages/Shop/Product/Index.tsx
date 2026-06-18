import { Head, router } from '@inertiajs/react';
import { PageProps, Product, Category, PaginatedData } from '@/Types';
import ClientLayout from '@/Layouts/ClientLayout';
import Hero from './Partials/Hero';
import FilterSidebar from './Partials/FilterSidebar';
import ProductGrid from './Partials/ProductGrid';
import TrustBadges from './Partials/TrustBadges';
import Newsletter from './Partials/Newsletter';

interface Filters {
    search: string;
    category: number | null;
    sort: string;
    min_price: number | null;
    max_price: number | null;
}

interface Props extends PageProps {
    products: PaginatedData<Product>;
    categories: Category[];
    filters: Filters;
}

export default function ProductsIndex({ products, categories, filters }: Props) {
    const apply = (key: string, value: string | number | null) => {
        router.get(route('products.index'), { ...filters, [key]: value }, {
            preserveState: true,
            replace: true,
        });
    };

    const clearAll = () => router.get(route('products.index'));

    const hasActiveFilters = !!(filters.search || filters.category || filters.min_price || filters.max_price);

    return (
        <>
            <Head title="Products" />
            <ClientLayout>
                <Hero />

                <div className="page-container py-8">
                    <div className="flex flex-col lg:flex-row gap-8">
                        <FilterSidebar
                            filters={filters}
                            categories={categories}
                            onApply={apply}
                            onClearAll={clearAll}
                            hasActiveFilters={hasActiveFilters}
                        />

                        <ProductGrid
                            products={products}
                            filters={filters}
                            onApply={apply}
                            onClearAll={clearAll}
                            hasActiveFilters={hasActiveFilters}
                        />
                    </div>
                </div>

                <TrustBadges />

                <Newsletter />
            </ClientLayout>
        </>
    );
}
