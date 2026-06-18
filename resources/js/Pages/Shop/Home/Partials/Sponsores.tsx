import Reveal from '@/Components/Shared/Reveal'
import { TagIcon } from '@heroicons/react/24/solid';

import { SPOTLIGHT_CAMPAIGNS } from '@/Constants/home';

export default function Sponsores() {
    return (
        <Reveal>
            <section>
                <div className="flex-between mb-4">
                    <h2 className="text-base font-semibold text-content">Spotlight</h2>
                    <span className="text-xs text-content-muted">Promoted placements from our brands</span>
                </div>
                <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    {SPOTLIGHT_CAMPAIGNS.map((campaign) => (
                        <div
                            key={campaign.title}
                            className={`rounded-2xl p-6 text-white bg-gradient-to-br ${campaign.accent}`}
                        >
                            <span className="inline-flex items-center gap-1 text-xs font-medium uppercase tracking-wide bg-white/20 rounded-full px-2.5 py-1">
                                <TagIcon className="w-3.5 h-3.5" />
                                {campaign.tag}
                            </span>
                            <h3 className="text-lg text-white font-bold mt-3">{campaign.title}</h3>
                            <p className="text-sm text-white/85 mt-1">{campaign.description}</p>
                        </div>
                    ))}
                </div>
            </section>
        </Reveal>
    )
}
