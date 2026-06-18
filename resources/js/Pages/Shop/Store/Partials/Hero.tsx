import { useEffect, useState } from 'react';
import { router } from '@inertiajs/react';
import { MagnifyingGlassIcon } from '@heroicons/react/24/outline';
import Reveal from '@/Components/Shared/Reveal';

interface Props {
    initialSearch: string;
    currentSort: string;
    totalStores: number;
}

function formatCount(n: number): string {
    return n.toLocaleString('en-US');
}

export default function Hero({ initialSearch, currentSort, totalStores }: Props) {
    const [searchInput, setSearchInput] = useState(initialSearch);

    useEffect(() => {
        setSearchInput(initialSearch);
    }, [initialSearch]);

    const apply = (value: string) => {
        router.get(route('stores.index'), { search: value || undefined, sort: currentSort }, {
            preserveState: true,
            replace: true,
        });
    };

    return (
        <Reveal>
            <section className="bg-gradient-to-b from-surface to-brand-50/30 border-b border-border">
            <div className="page-container py-12 sm:py-16 text-center">
                <span className="inline-block mb-3 px-3 py-1 rounded-full bg-brand-100 text-brand-700 text-xs font-medium tracking-wide uppercase border border-brand-200">
                    Discover brands
                </span>
                <h1 className="text-4xl sm:text-5xl font-semibold tracking-tight text-content leading-[1.15] mb-3">
                    Browse all stores
                </h1>
                <p className="text-content-secondary max-w-xl mx-auto text-sm sm:text-base">
                    Explore independent clothing brands — each with its own unique
                    style, story, and collection.
                </p>

                <div className="max-w-md mx-auto mt-8 relative">
                    <MagnifyingGlassIcon className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-content-muted pointer-events-none" />
                    <input
                        type="text"
                        placeholder="Search stores…"
                        value={searchInput}
                        onChange={(e) => setSearchInput(e.target.value)}
                        onKeyDown={(e) => {
                            if (e.key === 'Enter') {
                                apply(searchInput);
                            }
                        }}
                        className="input pl-9 text-sm w-full"
                    />
                </div>

                <p className="text-xs text-content-muted mt-4">
                    {formatCount(totalStores)} {totalStores === 1 ? 'store' : 'stores'} on Loomi
                </p>
            </div>
            </section>
        </Reveal>
    );
}
