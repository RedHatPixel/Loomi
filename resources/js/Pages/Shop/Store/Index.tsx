import { Head, router } from '@inertiajs/react';
import { PageProps, Store, PaginatedData } from '@/Types';
import ClientLayout from '@/Layouts/ClientLayout';
import Hero from './Partials/Hero';
import StoreGrid from './Partials/StoreGrid';
import TrustBadges from './Partials/TrustBadges';
import Newsletter from './Partials/Newsletter';

interface Filters {
    search: string;
    sort: string;
}

interface Props extends PageProps {
    stores: PaginatedData<Store>;
    filters: Filters;
}

export default function StoresIndex({ stores, filters }: Props) {
    const apply = (key: string, value: string) => {
        router.get(route('stores.index'), { ...filters, [key]: value || undefined }, {
            preserveState: true,
            replace: true,
        });
    };

    const hasActiveFilters = !!filters.search;
    const totalStores = stores.meta.total;

    return (
        <>
            <Head title="Stores" />
            <ClientLayout>
                <Hero initialSearch={filters.search} currentSort={filters.sort} totalStores={totalStores} />

                <StoreGrid
                    stores={stores}
                    filters={filters}
                    hasActiveFilters={hasActiveFilters}
                    onApply={apply}
                />

                <TrustBadges />

                <Newsletter />
            </ClientLayout>
        </>
    );
}
