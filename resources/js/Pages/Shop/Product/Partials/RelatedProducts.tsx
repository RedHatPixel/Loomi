import { Link } from '@inertiajs/react';
import Reveal from '@/Components/Shared/Reveal';

interface RelatedProduct {
    id: number;
    name: string;
    slug: string;
    price: number;
    image: string | null;
}

interface Props {
    products: RelatedProduct[];
}

const PLACEHOLDER_IMAGE = 'https://placehold.co/600x750/e7e2d8/3a3a3a?text=No+Image';

function formatPrice(price: number): string {
    return new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP',
        minimumFractionDigits: 2,
    }).format(price);
}

function RelatedProductCard({ product }: { product: RelatedProduct }) {
    return (
        <Link href={route('products.show', product.slug)} className="group block">
            <div className="aspect-[4/5] rounded-xl overflow-hidden bg-surface-raised">
                <img
                    src={product.image ?? PLACEHOLDER_IMAGE}
                    alt={product.name}
                    className="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                />
            </div>
            <p className="text-sm font-medium text-content mt-2 truncate">{product.name}</p>
            <p className="text-sm text-content-muted">{formatPrice(product.price)}</p>
        </Link>
    );
}

export default function RelatedProducts({ products }: Props) {
    if (products.length === 0) return null;

    return (
        <Reveal>
            <section className="mt-16">
            <h2 className="text-base font-semibold text-content mb-4">You might also like</h2>
            <div className="grid grid-cols-2 sm:grid-cols-4 gap-4">
                {products.map((p) => (
                    <RelatedProductCard key={p.id} product={p} />
                ))}
            </div>
        </section>
        </Reveal>
    );
}
