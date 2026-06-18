import { ArrowRightIcon } from '@heroicons/react/24/outline';
import Reveal from '@/Components/Shared/Reveal';

export default function Newsletter() {
    return (
        <Reveal>
            <section className="border-b border-border">
            <div className="page-container py-12">
                <div className="rounded-2xl bg-gradient-to-br from-brand-700 to-brand-900 text-white p-8 md:p-12">
                    <div className="flex flex-col md:flex-row items-start md:items-center justify-between gap-8">
                        <div className="max-w-lg">
                            <h2 className="text-2xl font-semibold tracking-tight text-white">
                                Get new drops before they sell out
                            </h2>
                            <p className="mt-3 text-sm text-brand-100">
                                One email a week: new brands on Loomi, limited drops, and restocks from
                                the stores you follow.
                            </p>
                        </div>
                        <form
                            className="shrink-0 flex w-full md:w-auto gap-2"
                            onSubmit={(e) => e.preventDefault()}
                        >
                            <input
                                type="email"
                                required
                                placeholder="you@email.com"
                                className="input flex-1 md:w-64 text-content"
                            />
                            <button
                                type="submit"
                                className="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-white text-brand-800 text-sm font-medium hover:bg-brand-50 transition-colors shrink-0"
                            >
                                Subscribe
                                <ArrowRightIcon className="w-4 h-4" />
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        </Reveal>
    );
}
