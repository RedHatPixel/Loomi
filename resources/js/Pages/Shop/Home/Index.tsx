import { PageProps, Product, Store, Category, PaginatedData } from '@/Types';
import ClientLayout from '@/Layouts/ClientLayout';
import Newsletter from './Partials/Newsletter';
import Testimonials from './Partials/Testimonials';
import HowItWorks from './Partials/HowItWorks';
import TrendingNow from './Partials/TrendingNow';
import Sponsores from './Partials/Sponsores';
import Products from './Partials/Products';
import Featured from './Partials/Featured';
import TrustBadges from './Partials/TrustBadges';
import Hero from './Partials/Hero';
import { Head } from '@inertiajs/react';

interface Filters {
    category: number | null;
    sort: string;
}

interface HomeProps extends PageProps {
    products: PaginatedData<Product>;
    trendingProducts: Product[];
    featuredStores: Store[];
    categories: Category[];
    filters: Filters;
}

export default function Home({ products, trendingProducts, featuredStores, categories, filters }: HomeProps) {
    return (
        <>
            <Head title="Shop" />
            <ClientLayout>
                <Hero />

                <div className="page-container py-8 space-y-16">
                    <TrustBadges />
                    <Featured featuredStores={featuredStores} />
                    <Products products={products} filters={filters} categories={categories} />
                    <Sponsores />
                    <TrendingNow products={trendingProducts} />
                    <Testimonials/>
                    <HowItWorks/>
                    <Newsletter />
                </div>

            </ClientLayout>
        </>
    );
}
