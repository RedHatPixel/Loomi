import { Link } from '@inertiajs/react';
import { Store, Category } from '@/Types';
import {
    BuildingStorefrontIcon,
    ChevronRightIcon,
    ShoppingBagIcon,
} from '@heroicons/react/24/outline';
import Reveal from '@/Components/Shared/Reveal';

interface Props {
    store: Store;
    categories: Category[];
}

function formatCount(n: number): string {
    return n.toLocaleString('en-US');
}

export default function StoreHeader({ store, categories }: Props) {
    const initial = store.name.charAt(0).toUpperCase();
    const hasBg = !!store.background_image;
    const hasLogo = !!store.logo;

    return (
        <>
            {/* Breadcrumb */}
            <div className="page-container pt-6 pb-0">
                <nav className="flex items-center gap-1.5 text-xs text-content-muted flex-wrap">
                    <Link href={route('home')} className="hover:text-brand-600">Home</Link>
                    <ChevronRightIcon className="w-3.5 h-3.5" />
                    <Link href={route('stores.index')} className="hover:text-brand-600">Stores</Link>
                    <ChevronRightIcon className="w-3.5 h-3.5" />
                    <span className="text-content truncate">{store.name}</span>
                </nav>
            </div>

            {/* Store header */}
            <Reveal>
                <section
                className={`relative border-b border-border ${hasBg ? '' : 'bg-gradient-to-b from-surface to-brand-50/30'}`}
            >
                {hasBg && (
                    <div
                        className="absolute inset-0 bg-cover bg-center"
                        style={{ backgroundImage: `url(${store.background_image})` }}
                    >
                        <div className="absolute inset-0 bg-gradient-to-b from-black/50 via-black/30 to-black/60" />
                    </div>
                )}
                <div className={`page-container py-10 sm:py-14 relative z-10 ${hasBg ? 'text-white' : ''}`}>
                    <div className="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                        <div className={`size-20 rounded-full flex-center shadow-sm ring-4 shrink-0 overflow-hidden ${hasBg ? 'ring-white/70 bg-white/20' : 'bg-gradient-to-br from-brand-100 via-brand-50 to-brand-200 ring-white'}`}>
                            {hasLogo ? (
                                <img src={store.logo!} alt={store.name} className="size-full object-cover" />
                            ) : (
                                <span className={`text-3xl font-bold ${hasBg ? 'text-white' : 'text-brand-700'}`}>{initial}</span>
                            )}
                        </div>
                        <div className="flex-1 min-w-0">
                            <h1 className={`text-3xl sm:text-4xl font-semibold tracking-tight ${hasBg ? 'text-white' : 'text-content'}`}>
                                {store.name}
                            </h1>
                            <p className={`mt-2 max-w-2xl leading-relaxed ${hasBg ? 'text-white/80' : 'text-content-secondary'}`}>
                                {store.description ?? 'No description available.'}
                            </p>
                            <div className={`flex items-center justify-center sm:justify-start gap-4 mt-4 flex-wrap ${hasBg ? 'text-white/70' : 'text-content-muted'}`}>
                                <span className="inline-flex items-center gap-1.5 text-sm">
                                    <ShoppingBagIcon className="w-4 h-4" />
                                    {formatCount(store.products_count ?? 0)}{' '}
                                    {store.products_count === 1 ? 'product' : 'products'}
                                </span>
                                {categories.length > 0 && (
                                    <span className="inline-flex items-center gap-1.5 text-sm">
                                        <BuildingStorefrontIcon className="w-4 h-4" />
                                        {formatCount(categories.length)}{' '}
                                        {categories.length === 1 ? 'category' : 'categories'}
                                    </span>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
                </section>
            </Reveal>
        </>
    );
}
