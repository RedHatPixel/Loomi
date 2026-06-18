import { ArrowRightIcon } from '@heroicons/react/24/outline'
import { Link } from '@inertiajs/react'

export default function Hero() {
    return (
        <div className="relative overflow-hidden bg-brand-800 text-white">
            <div className="pointer-events-none absolute -top-24 -right-16 h-72 w-72 rounded-full bg-brand-500/30 blur-3xl animate-pulse" />
            <div className="pointer-events-none absolute -bottom-28 -left-12 h-64 w-64 rounded-full bg-brand-400/20 blur-3xl animate-pulse" />

            <div className="page-container py-14 relative flex flex-col md:flex-row items-center justify-between gap-8">
                <div className="max-w-xl">
                    <p className="text-xs font-medium text-brand-300 uppercase tracking-widest mb-1">
                        New this season
                    </p>
                    <h1 className="text-3xl sm:text-4xl font-semibold tracking-tight text-white">
                        Fresh drops from independent brands
                    </h1>
                    <p className="text-brand-200 text-sm sm:text-base mt-3">
                        Discover clothing made by real people, sold directly to you — no
                        middleman, just makers and the brands they built.
                    </p>

                    <div className="flex flex-wrap items-center gap-3 mt-6">
                        <Link
                            href={route('products.index')}
                            className="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-white text-brand-800 text-sm font-medium hover:bg-brand-50 transition-colors"
                        >
                            Start shopping
                        </Link>
                        <Link
                            href={route('stores.index')}
                            className="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg border border-white/30 text-white text-sm font-medium hover:bg-white/10 transition-colors"
                        >
                            Browse brands
                            <ArrowRightIcon className="w-4 h-4" />
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    )
}
