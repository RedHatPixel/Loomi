import { Head } from '@inertiajs/react';
import { Category, PageProps } from '@/Types';
import ClientLayout from '@/Layouts/ClientLayout';
import Breadcrumb from './Partials/Breadcrumb';
import ProductGallery from './Partials/ProductGallery';
import ProductInfo from './Partials/ProductInfo';
import RelatedProducts from './Partials/RelatedProducts';
import StoreBanner from './Partials/StoreBanner';
import Newsletter from './Partials/Newsletter';

interface ShowProduct {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    price: number;
    stock: number;
    category: Category | null;
    store: {
        id: number;
        name: string;
        slug: string;
    };
    images: string[];
}

interface RelatedProduct {
    id: number;
    name: string;
    slug: string;
    price: number;
    image: string | null;
}

interface Props extends PageProps {
    product: ShowProduct;
    related: RelatedProduct[];
}

export default function ProductShow({ product, related }: Props) {
    return (
        <>
            <Head title={product.name} />
            <ClientLayout>
                <div className="page-container py-8">
                    <Breadcrumb productName={product.name} category={product.category} />

                    <div className="flex flex-col lg:flex-row gap-10">
                        <ProductGallery images={product.images} productName={product.name} />

                        <ProductInfo product={product} />
                    </div>

                    <RelatedProducts products={related} />
                </div>

                <StoreBanner store={product.store} />

                <Newsletter />
            </ClientLayout>
        </>
    );
}
