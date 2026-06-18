import Reveal from "@/Components/Shared/Reveal";
import { StarIcon } from "@heroicons/react/24/solid";
import { HOME_TESTIMONIALS } from '@/Constants/home';

export default function Testimonials() {
    return (
        <Reveal>
            <section>
                <h2 className="text-base font-semibold text-content mb-6 text-center">
                    Loved by shoppers
                </h2>
                <div className="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    {HOME_TESTIMONIALS.map((t) => (
                        <div key={t.author} className="card">
                            <div className="flex gap-0.5 mb-3">
                                {Array.from({ length: 5 }).map((_, i) => (
                                    <StarIcon key={i} className="w-4 h-4 text-status-warning" />
                                ))}
                            </div>
                            <p className="text-sm text-content-secondary italic">"{t.quote}"</p>
                            <p className="text-xs text-content-muted mt-3">— {t.author}</p>
                        </div>
                    ))}
                </div>
            </section>
        </Reveal>
    )
}
