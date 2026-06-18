import ClientLayout from '@/Layouts/ClientLayout';
import { PageProps } from '@/Types';
import { Head, Link } from '@inertiajs/react';
import { ArrowRightIcon } from '@heroicons/react/24/outline';
import { StarIcon } from '@heroicons/react/24/solid';
import Reveal from '@/Components/Shared/Reveal';
import {
    SELLER_STATS,
    SELLER_STEPS,
    SELLER_FEATURES,
    SELLER_BENEFITS,
    SELLER_TESTIMONIALS,
} from '@/Constants/seller';

export default function SellerLanding({ auth }: PageProps) {
    return (
        <>
            <Head title="Sell on Loomi" />

            <ClientLayout>
                {/* Hero */}
                <Reveal>
                <section className="relative overflow-hidden bg-brand-800 text-white">
                    <div className="pointer-events-none absolute -top-24 -right-16 h-80 w-80 rounded-full bg-brand-500/30 blur-3xl animate-pulse" />
                    <div className="pointer-events-none absolute -bottom-28 -left-12 h-72 w-72 rounded-full bg-brand-400/20 blur-3xl animate-pulse" />

                    <div className="page-container py-20 sm:py-28 relative">
                        <div className="max-w-3xl mx-auto text-center">
                            <span className="inline-block mb-4 px-3 py-1 rounded-full bg-brand-700 text-brand-200 text-xs font-medium tracking-wide uppercase border border-brand-500/40">
                                Open to all clothing brands
                            </span>
                            <h1 className="text-4xl sm:text-5xl lg:text-6xl font-semibold tracking-tight text-white leading-[1.1] mb-5">
                                Your brand.
                                <br />
                                <span className="text-brand-300">Your storefront.</span>
                            </h1>
                            <p className="text-brand-100 text-base sm:text-lg max-w-2xl mx-auto leading-relaxed">
                                Loomi gives independent clothing brands everything they need to sell
                                directly to customers — from storefronts to order fulfillment, all in one place.
                            </p>
                            <div className="flex flex-col sm:flex-row items-center justify-center gap-3 mt-8">
                                <Link
                                    href={auth.user ? route('seller.create') : route('register')}
                                    className="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-white text-brand-800 text-base font-medium hover:bg-brand-50 transition-colors w-full sm:w-auto justify-center"
                                >
                                    {auth.user ? 'Open your store' : 'Open your store for free'}
                                    <ArrowRightIcon className="w-4 h-4" />
                                </Link>
                                <Link
                                    href={route('stores.index')}
                                    className="inline-flex items-center gap-2 px-6 py-3 rounded-lg border border-white/30 text-white text-base font-medium hover:bg-white/10 transition-colors w-full sm:w-auto justify-center"
                                >
                                    Browse the marketplace
                                </Link>
                            </div>
                        </div>
                    </div>
                </section>
                </Reveal>

                {/* Stats */}
                <Reveal>
                <section className="border-y border-border bg-surface">
                    <div className="page-container grid grid-cols-2 md:grid-cols-4 divide-x divide-border">
                        {SELLER_STATS.map((stat) => (
                            <div key={stat.label} className="py-8 px-6 text-center">
                                <p className="text-3xl font-semibold text-brand-700">{stat.value}</p>
                                <p className="text-sm text-content-muted mt-1">{stat.label}</p>
                            </div>
                        ))}
                    </div>
                </section>
                </Reveal>

                {/* How it works */}
                <Reveal>
                <section className="section">
                    <div className="page-container">
                        <div className="text-center mb-14">
                            <span className="inline-block mb-3 px-3 py-1 rounded-full bg-brand-50 text-brand-700 text-xs font-medium tracking-wide uppercase border border-brand-200">
                                Getting started
                            </span>
                            <h2 className="text-3xl font-semibold text-content tracking-tight">
                                Set up in minutes
                            </h2>
                            <p className="text-content-secondary mt-3 max-w-xl mx-auto">
                                No technical knowledge required. Create your account, open a store, and start selling today.
                            </p>
                        </div>
                        <div className="grid md:grid-cols-3 gap-6">
                            {SELLER_STEPS.map((item) => (
                                <div key={item.step} className="card relative overflow-hidden">
                                    <p className="text-6xl font-bold text-brand-50 select-none absolute -top-2 -right-1 leading-none">
                                        {item.step}
                                    </p>
                                    <p className="text-xs font-medium text-brand-600 mb-3 tracking-widest uppercase">
                                        Step {item.step}
                                    </p>
                                    <h3 className="text-lg font-medium text-content mb-2">{item.title}</h3>
                                    <p className="text-sm text-content-secondary leading-relaxed">{item.desc}</p>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>
                </Reveal>

                {/* Benefits */}
                <Reveal>
                <section className="section bg-surface border-y border-border">
                    <div className="page-container">
                        <div className="text-center mb-14">
                            <span className="inline-block mb-3 px-3 py-1 rounded-full bg-brand-50 text-brand-700 text-xs font-medium tracking-wide uppercase border border-brand-200">
                                Why Loomi
                            </span>
                            <h2 className="text-3xl font-semibold text-content tracking-tight">
                                Built for independent brands
                            </h2>
                            <p className="text-content-secondary mt-3 max-w-xl mx-auto">
                                Every feature is designed with clothing sellers in mind.
                            </p>
                        </div>
                        <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            {SELLER_BENEFITS.map((b) => (
                                <div key={b.title} className="flex flex-col items-start gap-3 p-5 rounded-xl border border-border bg-surface-page hover:border-border-strong transition-colors">
                                    <span className="flex-center w-10 h-10 rounded-lg bg-brand-50 text-brand-700">
                                        <b.icon className="w-5 h-5" />
                                    </span>
                                    <h3 className="text-sm font-semibold text-content">{b.title}</h3>
                                    <p className="text-xs text-content-muted leading-relaxed">{b.desc}</p>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>
                </Reveal>

                {/* Features */}
                <Reveal>
                <section className="section">
                    <div className="page-container">
                        <div className="text-center mb-14">
                            <h2 className="text-3xl font-semibold text-content tracking-tight">
                                Everything a clothing brand needs
                            </h2>
                            <p className="text-content-secondary mt-3 max-w-xl mx-auto">
                                Built for sellers of every size — from solo designers to growing labels.
                            </p>
                        </div>
                        <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            {SELLER_FEATURES.map((f) => (
                                <div key={f.title} className="flex gap-4 p-5 rounded-xl border border-border bg-surface-page hover:border-border-strong transition-colors">
                                    <span className="flex-center w-10 h-10 rounded-lg bg-brand-50 text-brand-700 shrink-0 mt-0.5">
                                        <f.icon className="w-5 h-5" />
                                    </span>
                                    <div>
                                        <h3 className="text-sm font-medium text-content mb-1">{f.title}</h3>
                                        <p className="text-sm text-content-secondary leading-relaxed">{f.desc}</p>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>
                </Reveal>

                {/* Testimonials */}
                <Reveal>
                <section className="section bg-surface border-y border-border">
                    <div className="page-container">
                        <div className="text-center mb-12">
                            <h2 className="text-2xl font-semibold text-content tracking-tight">
                                Loved by sellers
                            </h2>
                            <p className="text-content-secondary mt-2 max-w-lg mx-auto">
                                Hear from brands already selling on Loomi.
                            </p>
                        </div>
                        <div className="grid sm:grid-cols-3 gap-6">
                            {SELLER_TESTIMONIALS.map((t) => (
                                <div key={t.author} className="card">
                                    <div className="flex gap-0.5 mb-3">
                                        {Array.from({ length: 5 }).map((_, i) => (
                                            <StarIcon key={i} className="w-4 h-4 text-status-warning" />
                                        ))}
                                    </div>
                                    <p className="text-sm text-content-secondary italic leading-relaxed">
                                        &ldquo;{t.quote}&rdquo;
                                    </p>
                                    <div className="mt-4 border-t border-border pt-3">
                                        <p className="text-sm font-medium text-content">{t.author}</p>
                                        <p className="text-xs text-content-muted">{t.role}</p>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>
                </Reveal>

                {/* CTA */}
                <Reveal>
                <section className="section">
                    <div className="page-container">
                        <div className="bg-gradient-to-br from-brand-800 to-brand-900 rounded-2xl px-8 py-16 text-center">
                            <h2 className="text-3xl sm:text-4xl font-semibold text-white tracking-tight mb-4">
                                Ready to open your store?
                            </h2>
                            <p className="text-brand-200 max-w-lg mx-auto mb-8">
                                Join thousands of independent clothing brands already selling on Loomi.
                                It takes less than 10 minutes to get started.
                            </p>
                            <div className="flex flex-col sm:flex-row items-center justify-center gap-3">
                                <Link
                                    href={auth.user ? route('seller.create') : route('register')}
                                    className="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-white text-brand-800 font-medium text-base hover:bg-brand-50 transition-colors"
                                >
                                    {auth.user ? 'Set up your store' : 'Create your free account'}
                                    <ArrowRightIcon className="w-4 h-4" />
                                </Link>
                                <Link
                                    href={route('stores.index')}
                                    className="inline-flex items-center gap-2 px-6 py-3 rounded-lg border border-white/30 text-white font-medium text-base hover:bg-white/10 transition-colors"
                                >
                                    Explore the marketplace
                                </Link>
                            </div>
                        </div>
                    </div>
                </section>
                </Reveal>
            </ClientLayout>
        </>
    );
}
