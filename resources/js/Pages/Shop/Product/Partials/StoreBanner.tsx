import { Link } from '@inertiajs/react';
import { BuildingStorefrontIcon, ChevronRightIcon } from '@heroicons/react/24/outline';
import Reveal from '@/Components/Shared/Reveal';

interface Props {
    store: {
        id: number;
        name: string;
        slug: string;
    };
}

export default function StoreBanner({ store }: Props) {
    const initial = store.name.charAt(0).toUpperCase();

    return (
        <Reveal>
            <section className="border-t border-border bg-surface-page">
            <div className="page-container py-8">
                <Link
                    href={route('stores.show', store.slug)}
                    className="flex items-center gap-4 p-5 rounded-xl bg-surface border border-border hover:border-brand-300 hover:shadow-sm transition-all group"
                >
                    <div className="size-14 rounded-full bg-gradient-to-br from-brand-100 via-brand-50 to-brand-200 flex-center shrink-0">
                        <span className="text-xl font-bold text-brand-700">{initial}</span>
                    </div>
                    <div className="flex-1 min-w-0">
                        <p className="text-xs text-content-muted mb-0.5">Sold by</p>
                        <p className="text-sm font-semibold text-content group-hover:text-brand-700 transition-colors">
                            {store.name}
                        </p>
                    </div>
                    <span className="flex items-center gap-1 text-xs font-medium text-brand-600 opacity-0 group-hover:opacity-100 transition-opacity shrink-0">
                        Visit store
                        <ChevronRightIcon className="w-3 h-3" />
                    </span>
                </Link>
            </div>
        </section>
        </Reveal>
    );
}
