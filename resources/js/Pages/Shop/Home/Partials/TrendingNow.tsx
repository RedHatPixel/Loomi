import ProductCard from "@/Components/Shared/ProductCard";
import Reveal from "@/Components/Shared/Reveal"
import { Product } from "@/Types";
import { SparklesIcon } from "@heroicons/react/24/outline"
import { Link } from "@inertiajs/react"

interface Props {
    products: Product[];
}

export default function TrendingNow({ products }: Props) {
    return (
        <>
            {products.length > 0 && (
                <Reveal>
                    <section>
                        <div className="flex-between mb-4">
                            <h2 className="text-base font-semibold text-content flex items-center gap-1.5">
                                <SparklesIcon className="w-4 h-4 text-brand-600" />
                                Trending now
                            </h2>
                            <Link href={route('products.index')} className="text-sm text-brand-600 hover:underline">
                                View all
                            </Link>
                        </div>
                        <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            {products.slice(0, 5).map((product) => (
                                <div key={product.id} className="transition-transform duration-300 hover:-translate-y-1">
                                    <ProductCard product={product} />
                                </div>
                            ))}
                        </div>
                    </section>
                </Reveal>
            )}
        </>
    )
}
