import { Link } from '@inertiajs/react';
import { SELLER_TRUST_BADGES } from '@/Constants/stores';
import Reveal from '@/Components/Shared/Reveal';

export default function TrustBadges() {
    return (
        <Reveal>
            <section className="border-y border-border bg-surface">
            <div className="page-container py-12">
                <div className="text-center mb-10">
                    <h2 className="text-2xl font-semibold tracking-tight text-content">
                        Why sell on Loomi
                    </h2>
                    <p className="text-content-secondary text-sm mt-2 max-w-lg mx-auto">
                        Built for independent clothing brands of every size.
                    </p>
                </div>
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    {SELLER_TRUST_BADGES.map((badge) => (
                        <div key={badge.title} className="flex flex-col items-start gap-3">
                            <span className="flex-center w-10 h-10 rounded-lg bg-brand-50 text-brand-700">
                                <badge.icon className="w-5 h-5" />
                            </span>
                            <h3 className="text-sm font-semibold text-content">{badge.title}</h3>
                            <p className="text-xs text-content-muted leading-relaxed">{badge.description}</p>
                        </div>
                    ))}
                </div>
                <div className="text-center mt-8">
                    <Link
                        href={route('seller.landing')}
                        className="inline-flex items-center gap-1.5 text-sm font-medium text-brand-600 hover:text-brand-700 transition-colors"
                    >
                        Learn more about selling →
                    </Link>
                </div>
            </div>
        </section>
        </Reveal>
    );
}
