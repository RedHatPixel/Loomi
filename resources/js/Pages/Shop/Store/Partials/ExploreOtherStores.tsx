import { Link } from '@inertiajs/react';
import { Product } from '@/Types';
import ProductCard from '@/Components/Shared/ProductCard';
import { SparklesIcon, ChevronRightIcon } from '@heroicons/react/24/outline';
import Reveal from '@/Components/Shared/Reveal';

interface Props {
    products: Product[];
}

export default function ExploreOtherStores({ products }: Props) {
    if (products.length === 0) return null;

    return (
        <Reveal>
            <section className="border-t border-border bg-surface">
            <div className="page-container py-12">
                <div className="flex items-center gap-2 mb-6">
                    <SparklesIcon className="w-5 h-5 text-brand-600" />
                    <h2 className="text-base font-semibold text-content">
                        Explore other stores
                    </h2>
                </div>
                <div className="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    {products.map((product) => (
                        <ProductCard key={product.id} product={product} />
                    ))}
                </div>
                <div className="mt-6 text-center">
                    <Link
                        href={route('stores.index')}
                        className="inline-flex items-center gap-1.5 text-sm font-medium text-brand-600 hover:text-brand-700 transition-colors"
                    >
                        Browse all stores
                        <ChevronRightIcon className="w-4 h-4" />
                    </Link>
                </div>
            </div>
        </section>
        </Reveal>
    );
}
