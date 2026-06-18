import { STORE_TRUST_BADGES } from '@/Constants/stores';
import Reveal from '@/Components/Shared/Reveal';

export default function StoreTrustBadges() {
    return (
        <Reveal>
            <section className="border-b border-border bg-surface-page">
            <div className="page-container py-10">
                <div className="grid grid-cols-2 sm:grid-cols-4 gap-6">
                    {STORE_TRUST_BADGES.map((badge) => (
                        <div key={badge.title} className="flex flex-col items-start gap-2">
                            <span className="flex-center w-9 h-9 rounded-lg bg-brand-50 text-brand-700">
                                <badge.icon className="w-4 h-4" />
                            </span>
                            <h3 className="text-sm font-medium text-content">{badge.title}</h3>
                            <p className="text-xs text-content-muted">{badge.description}</p>
                        </div>
                    ))}
                </div>
            </div>
        </section>
        </Reveal>
    );
}
