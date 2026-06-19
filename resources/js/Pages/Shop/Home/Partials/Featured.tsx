import Reveal from '@/Components/Shared/Reveal';
import StoreCard from '@/Components/Shared/StoreCard';
import { Store } from '@/Types';
import { Link } from '@inertiajs/react';

interface Props {
    featuredStores: Store[];
}

export default function Featured({ featuredStores }: Props) {
    return (
        <>
            {featuredStores.length > 0 && (
                <Reveal>
                    <section>
                        <div className="flex-between mb-4">
                            <h2 className="text-base font-semibold text-content">Featured stores</h2>
                            <Link href={route('stores.index')} className="text-sm text-brand-600 hover:underline">
                                View all
                            </Link>
                        </div>
                        <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                            {featuredStores.map((store) => (
                                <div key={store.id} className="transition-transform duration-300 hover:-translate-y-1">
                                    <StoreCard store={store} />
                                </div>
                            ))}
                        </div>
                    </section>
                </Reveal>
            )}
        </>
    )
}
