import Reveal from '@/Components/Shared/Reveal'
import { HOME_TRUST_BADGES } from '@/Constants/home';

export default function TrustBadges() {
    return (
        <Reveal>
            <section className="grid grid-cols-2 sm:grid-cols-4 gap-6">
                {HOME_TRUST_BADGES.map((badge) => (
                    <div key={badge.title} className="flex flex-col items-start gap-2">
                        <span className="flex-center w-10 h-10 rounded-lg bg-brand-50 text-brand-700">
                            <badge.icon className="w-5 h-5" />
                        </span>
                        <h3 className="text-sm font-semibold text-content">{badge.title}</h3>
                        <p className="text-xs text-content-muted">{badge.description}</p>
                    </div>
                ))}
            </section>
        </Reveal>
    )
}
