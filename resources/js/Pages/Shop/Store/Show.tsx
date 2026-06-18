import { Head, router } from '@inertiajs/react';
import { PageProps, Product, Category, PaginatedData, Store } from '@/Types';
import ClientLayout from '@/Layouts/ClientLayout';
import StoreHeader from './Partials/StoreHeader';
import FiltersBar from './Partials/FiltersBar';
import ProductGrid from './Partials/ProductGrid';
import ExploreOtherStores from './Partials/ExploreOtherStores';
import StoreTrustBadges from './Partials/StoreTrustBadges';

interface Props extends PageProps {
    store: Store;
    products: PaginatedData<Product>;
    featuredProducts: Product[];
    categories: Category[];
    filters: {
        sort: string;
        category: number | null;
    };
}

export default function StoreShow({ store, products, featuredProducts, categories, filters }: Props) {
    const apply = (key: string, value: string | number | null) => {
        router.get(route('stores.show', store.slug), { ...filters, [key]: value || undefined }, {
            preserveState: true,
            replace: true,
        });
    };

    return (
        <>
            <Head title={store.name} />
            <ClientLayout>
                <StoreHeader store={store} categories={categories} />

                <FiltersBar
                    categories={categories}
                    currentCategory={filters.category}
                    currentSort={filters.sort}
                    onApply={apply}
                />

                <ProductGrid
                    products={products}
                    currentCategory={filters.category}
                    slug={store.slug}
                />

                <StoreTrustBadges />

                <ExploreOtherStores products={featuredProducts} />
            </ClientLayout>
        </>
    );
}
