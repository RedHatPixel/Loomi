import { Head, Link, router } from '@inertiajs/react';
import { PageProps } from '@/Types';
import AdminLayout from '@/Layouts/AdminLayout';
import { ArrowLeftIcon, TrashIcon } from '@heroicons/react/24/outline';
import { useState } from 'react';
import Reveal from '@/Components/Shared/Reveal';

interface ProductData {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    price: number;
    stock: number;
    is_published: boolean;
    store: string;
    store_id: number;
    category: string;
    images: string[];
    created_at: string;
}

interface Props extends PageProps {
    productData: ProductData;
}

export default function AdminProductsShow({ productData }: Props) {
    const [mainImage, setMainImage] = useState(0);

    const handleToggle = () => {
        router.post(route('admin.products.toggle', productData.id), {}, { preserveScroll: true });
    };

    const handleDelete = () => {
        if (!confirm(`Delete product "${productData.name}"? This cannot be undone.`)) return;
        router.delete(route('admin.products.destroy', productData.id));
    };

    return (
        <>
            <Head title={productData.name} />
            <AdminLayout header={productData.name}>
                <div className="page-container py-6 sm:py-8 space-y-6">
                    <Link href={route('admin.products.index')} className="inline-flex items-center gap-1.5 text-sm text-content-muted hover:text-content transition-colors">
                        <ArrowLeftIcon className="w-4 h-4" />
                        Back to products
                    </Link>

                    <div className="grid lg:grid-cols-3 gap-6">
                        {/* Images */}
                        <Reveal className="lg:col-span-1">
                            <div className="card p-4">
                                <div className="aspect-[4/5] rounded-xl overflow-hidden bg-surface-raised mb-3">
                                    {productData.images[mainImage] ? (
                                        <img src={productData.images[mainImage]} alt={productData.name} className="w-full h-full object-cover" />
                                    ) : (
                                        <div className="w-full h-full flex items-center justify-center text-content-muted text-sm">No image</div>
                                    )}
                                </div>
                                {productData.images.length > 1 && (
                                    <div className="flex gap-2">
                                        {productData.images.map((img, i) => (
                                            <button key={i} type="button" onClick={() => setMainImage(i)}
                                                className={`w-14 h-16 rounded-lg overflow-hidden border-2 transition-colors ${i === mainImage ? 'border-brand-600' : 'border-transparent'}`}
                                            >
                                                <img src={img} alt="" className="w-full h-full object-cover" />
                                            </button>
                                        ))}
                                    </div>
                                )}
                            </div>
                        </Reveal>

                        {/* Details */}
                        <Reveal delay={100} className="lg:col-span-2">
                            <div className="card p-6 space-y-5">
                                <div className="flex items-start justify-between gap-4">
                                    <div>
                                        <h2 className="text-2xl font-semibold text-content">{productData.name}</h2>
                                        <p className="text-sm text-content-muted">{productData.category}</p>
                                    </div>
                                    <div className="flex items-center gap-2 shrink-0">
                                        <button type="button" onClick={handleToggle}
                                            className={`px-3 py-1.5 rounded-lg text-xs font-medium transition-colors ${
                                                productData.is_published ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                                            }`}
                                        >
                                            {productData.is_published ? 'Published' : 'Draft'}
                                        </button>
                                        <button type="button" onClick={handleDelete} className="btn-ghost p-2 text-red-500 hover:bg-red-50" aria-label="Delete product">
                                            <TrashIcon className="w-4 h-4" />
                                        </button>
                                    </div>
                                </div>

                                <div className="flex items-center gap-6 text-sm">
                                    <div><span className="text-content-muted">Price:</span> <span className="font-semibold text-content">PHP {productData.price.toLocaleString()}</span></div>
                                    <div><span className="text-content-muted">Stock:</span> <span className="font-semibold text-content">{productData.stock}</span></div>
                                </div>

                                <div className="text-sm">
                                    <span className="text-content-muted">Store:</span>{' '}
                                    <Link href={route('admin.stores.show', productData.store_id)} className="text-brand-600 hover:underline font-medium">
                                        {productData.store}
                                    </Link>
                                </div>

                                {productData.description && (
                                    <div>
                                        <p className="text-xs font-medium text-content-muted mb-1 uppercase tracking-wider">Description</p>
                                        <p className="text-sm text-content-secondary leading-relaxed whitespace-pre-line">{productData.description}</p>
                                    </div>
                                )}

                                <p className="text-xs text-content-muted">Created {productData.created_at}</p>
                            </div>
                        </Reveal>
                    </div>
                </div>
            </AdminLayout>
        </>
    );
}
