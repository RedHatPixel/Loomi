import { useState } from 'react';
import { Link, router, usePage } from '@inertiajs/react';
import { Category, PageProps } from '@/Types';
import {
    BuildingStorefrontIcon,
    MinusIcon,
    PlusIcon,
    ShoppingBagIcon,
    CheckCircleIcon,
} from '@heroicons/react/24/outline';
import Reveal from '@/Components/Shared/Reveal';

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

function formatPrice(price: number): string {
    return new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP',
        minimumFractionDigits: 2,
    }).format(price);
}

function QuantityStepper({ value, max, onChange }: { value: number; max: number; onChange: (v: number) => void }) {
    return (
        <div className="flex items-center border border-border rounded-lg">
            <button
                type="button"
                onClick={() => onChange(Math.max(1, value - 1))}
                disabled={value <= 1}
                className="p-2.5 text-content-muted hover:text-content disabled:opacity-40"
            >
                <MinusIcon className="w-4 h-4" />
            </button>
            <span className="w-10 text-center text-sm font-medium text-content">{value}</span>
            <button
                type="button"
                onClick={() => onChange(Math.min(max, value + 1))}
                disabled={value >= max}
                className="p-2.5 text-content-muted hover:text-content disabled:opacity-40"
            >
                <PlusIcon className="w-4 h-4" />
            </button>
        </div>
    );
}

function StockNotice({ stock }: { stock: number }) {
    if (stock === 0) {
        return <p className="text-sm font-medium text-status-danger mt-2">Out of stock</p>;
    }
    if (stock <= 5) {
        return <p className="text-sm font-medium text-status-warning mt-2">Only {stock} left</p>;
    }
    return <p className="text-sm font-medium text-status-success mt-2">In stock</p>;
}

interface Props {
    product: ShowProduct;
}

export default function ProductInfo({ product }: Props) {
    const { auth } = usePage<PageProps>().props;
    const [quantity, setQuantity] = useState(1);
    const [adding, setAdding] = useState(false);
    const [added, setAdded] = useState(false);

    const handleAddToCart = async () => {
        if (product.stock === 0) return;

        // Redirect to login if not authenticated
        if (!auth.user) {
            router.visit(route('login'), {
                data: { redirect: route('products.show', product.slug) },
            });
            return;
        }

        setAdding(true);
        setAdded(false);

        try {
            const res = await fetch(route('cart.add'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ product_id: product.id, quantity }),
            });

            if (res.ok) {
                setAdded(true);
                setQuantity(1);
                setTimeout(() => setAdded(false), 2500);
            }
        } catch {
            // Network error — silently fail
        } finally {
            setAdding(false);
        }
    };

    return (
        <Reveal>
            <div className="flex-1 min-w-0">
            {product.category && (
                <Link
                    href={route('products.index', { category: product.category.id })}
                    className="text-xs font-medium text-brand-600 uppercase tracking-wide hover:underline"
                >
                    {product.category.name}
                </Link>
            )}

            <h1 className="text-2xl font-semibold text-content mt-1">{product.name}</h1>
            <Link
                href={route('stores.show', product.store.slug)}
                className="inline-flex items-center gap-1.5 text-sm text-content-muted hover:text-brand-600 mt-1"
            >
                <BuildingStorefrontIcon className="w-4 h-4" />
                {product.store.name}
            </Link>

            <p className="text-2xl font-semibold text-content mt-4">
                {formatPrice(product.price)}
            </p>

            <StockNotice stock={product.stock} />

            <p className="text-sm text-content-secondary mt-6 leading-relaxed whitespace-pre-line">
                {product.description ?? 'No description provided.'}
            </p>

            <div className="flex items-center gap-3 mt-8">
                <QuantityStepper
                    value={quantity}
                    max={Math.max(product.stock, 1)}
                    onChange={setQuantity}
                />
                <button
                    type="button"
                    onClick={handleAddToCart}
                    disabled={product.stock === 0 || adding}
                    className="btn-primary flex-1 inline-flex items-center justify-center gap-2 text-sm disabled:opacity-50"
                >
                    {added ? <CheckCircleIcon className="w-4 h-4" /> : <ShoppingBagIcon className="w-4 h-4" />}
                    {product.stock === 0 ? 'Out of stock' : added ? 'Added!' : adding ? 'Adding…' : 'Add to cart'}
                </button>
            </div>
        </div>
        </Reveal>
    );
}
