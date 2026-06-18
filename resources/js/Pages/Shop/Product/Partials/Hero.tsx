import { Link } from '@inertiajs/react';
import { ArrowRightIcon } from '@heroicons/react/24/outline';
import Reveal from '@/Components/Shared/Reveal';

export default function Hero() {
    return (
        <Reveal>
            <section className="bg-gradient-to-b from-surface to-brand-50/30 border-b border-border">
            <div className="page-container py-12 sm:py-16 text-center">
                <span className="inline-block mb-3 px-3 py-1 rounded-full bg-brand-100 text-brand-700 text-xs font-medium tracking-wide uppercase border border-brand-200">
                    Shop the collection
                </span>
                <h1 className="text-4xl sm:text-5xl font-semibold tracking-tight text-content leading-[1.15] mb-3">
                    All products
                </h1>
                <p className="text-content-secondary max-w-xl mx-auto text-sm sm:text-base">
                    Browse every piece from every independent brand on Loomi — find
                    something that feels like yours.
                </p>
                <div className="flex items-center justify-center gap-3 mt-8 flex-wrap">
                    <Link
                        href={route('stores.index')}
                        className="inline-flex items-center gap-1.5 text-sm font-medium text-brand-600 hover:text-brand-700 transition-colors"
                    >
                        Browse stores
                        <ArrowRightIcon className="w-4 h-4" />
                    </Link>
                </div>
            </div>
            </section>
        </Reveal>
    );
}
