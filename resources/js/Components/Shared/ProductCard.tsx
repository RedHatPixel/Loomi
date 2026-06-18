import { Link } from '@inertiajs/react';
import { Product } from '@/Types';
import { storageUrl } from '@/Utils/storage';
import { nameHue } from '@/Utils/color';

interface Props {
    product: Product;
}

export default function ProductCard({ product }: Props) {
    const thumbnail = product.images[0]?.path
        ? storageUrl(product.images[0].path)
        : null;

    const hue = nameHue(product.name);

    return (
        <Link
            href={route('products.show', product.slug)}
            className="group flex flex-col bg-surface border border-border rounded-xl overflow-hidden hover:border-border-strong hover:shadow-sm transition-all"
        >
            <div className="aspect-square bg-surface-raised overflow-hidden">
                {thumbnail ? (
                    <img
                        src={thumbnail}
                        alt={product.name}
                        className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                    />
                ) : (
                    <div
                        className="w-full h-full flex items-center justify-center"
                        style={{ backgroundColor: `hsl(${hue}, 20%, 85%)` }}
                    >
                        <span
                            className="text-lg font-semibold text-center px-4 leading-snug"
                            style={{ color: `hsl(${hue}, 40%, 30%)` }}
                        >
                            {product.name}
                        </span>
                    </div>
                )}
            </div>

            <div className="p-3 flex flex-col gap-1">
                {product.category && (
                    <span className="text-xs text-content-muted">{product.category.name}</span>
                )}
                <p className="text-sm font-medium text-content leading-snug line-clamp-2">{product.name}</p>
                <p className="text-xs text-content-secondary">{product.store.name}</p>
                <p className="text-sm font-semibold text-brand-700 mt-1">
                    ₱{Number(product.price).toLocaleString('en-PH', { minimumFractionDigits: 2 })}
                </p>
            </div>
        </Link>
    );
}
