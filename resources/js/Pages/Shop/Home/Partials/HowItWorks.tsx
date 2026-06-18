import Reveal from '@/Components/Shared/Reveal'
import { HOW_IT_WORKS } from '@/Constants/home';

export default function HowItWorks() {
    return (
        <section>
            <h2 className="text-base font-semibold text-content mb-6 text-center">
                How Loomi works
            </h2>
            <div className="grid grid-cols-1 sm:grid-cols-3 gap-8">
                {HOW_IT_WORKS.map((step, index) => (
                    <Reveal key={step.title} delay={index * 150}>
                        <div className="flex flex-col items-center text-center gap-3">
                            <span className="flex-center w-12 h-12 rounded-full bg-brand-700 text-white font-semibold">
                                {index + 1}
                            </span>
                            <h3 className="text-sm font-semibold text-content">{step.title}</h3>
                            <p className="text-xs text-content-muted max-w-[220px]">{step.description}</p>
                        </div>
                    </Reveal>
                ))}
            </div>
        </section>
    )
}
