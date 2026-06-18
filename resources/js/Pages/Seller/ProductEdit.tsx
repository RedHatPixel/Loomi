import { Head, Link, useForm } from '@inertiajs/react';
import { PageProps, Product } from '@/Types';
import SellerLayout from '@/Layouts/SellerLayout';
import { ChevronLeftIcon } from '@heroicons/react/24/outline';
import ImageUpload from '@/Components/Shared/ImageUpload';

interface StoreOption {
    id: number;
    name: string;
    slug: string;
}

interface CategoryOption {
    id: number;
    name: string;
}

interface Props extends PageProps {
    product: Product;
    stores: StoreOption[];
    categories: CategoryOption[];
}

export default function SellerProductEdit({ product, stores, categories }: Props) {
    const { data, setData, patch, errors, processing } = useForm({
        store_id: String(product.store_id),
        category_id: product.category_id ? String(product.category_id) : '',
        name: product.name,
        description: product.description ?? '',
        price: product.price.toString(),
        stock: product.stock.toString(),
        is_published: product.is_published,
        image: product.images?.[0]?.path ?? null,
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        patch(route('seller.products.update', product.id));
    };

    return (
        <>
            <Head title={`Edit ${product.name}`} />
            <SellerLayout header="Edit Product">
                <div className="page-container py-6 sm:py-8 max-w-3xl">
                    <Link
                        href={route('seller.products.index')}
                        className="inline-flex items-center gap-1 text-sm text-content-secondary hover:text-content mb-6"
                    >
                        <ChevronLeftIcon className="w-4 h-4" />
                        Back to products
                    </Link>

                    <form onSubmit={submit} className="space-y-6">
                        {/* Store selection */}
                        {stores.length > 1 && (
                            <div>
                                <label className="label">Store</label>
                                <select
                                    value={data.store_id}
                                    onChange={(e) => setData('store_id', e.target.value)}
                                    className="input"
                                >
                                    {stores.map((s) => (
                                        <option key={s.id} value={s.id}>{s.name}</option>
                                    ))}
                                </select>
                                {errors.store_id && <p className="form-error">{errors.store_id}</p>}
                            </div>
                        )}

                        {/* Name */}
                        <div>
                            <label className="label">Product name</label>
                            <input
                                type="text"
                                value={data.name}
                                onChange={(e) => setData('name', e.target.value)}
                                className="input"
                                autoFocus
                            />
                            {errors.name && <p className="form-error">{errors.name}</p>}
                        </div>

                        {/* Description */}
                        <div>
                            <label className="label">Description</label>
                            <textarea
                                rows={4}
                                value={data.description}
                                onChange={(e) => setData('description', e.target.value)}
                                className="input"
                            />
                            {errors.description && <p className="form-error">{errors.description}</p>}
                        </div>

                        {/* Price and Stock */}
                        <div className="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label className="label">Price (PHP)</label>
                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    value={data.price}
                                    onChange={(e) => setData('price', e.target.value)}
                                    className="input"
                                />
                                {errors.price && <p className="form-error">{errors.price}</p>}
                            </div>
                            <div>
                                <label className="label">Stock</label>
                                <input
                                    type="number"
                                    min="0"
                                    value={data.stock}
                                    onChange={(e) => setData('stock', e.target.value)}
                                    className="input"
                                />
                                {errors.stock && <p className="form-error">{errors.stock}</p>}
                            </div>
                        </div>

                        {/* Category */}
                        <div>
                            <label className="label">Category</label>
                            <select
                                value={data.category_id}
                                onChange={(e) => setData('category_id', e.target.value)}
                                className="input"
                            >
                                <option value="">No category</option>
                                {categories.map((c) => (
                                    <option key={c.id} value={c.id}>{c.name}</option>
                                ))}
                            </select>
                            {errors.category_id && <p className="form-error">{errors.category_id}</p>}
                        </div>

                        {/* Product image */}
                        <ImageUpload
                            value={data.image}
                            onChange={(path) => setData('image', path ?? '')}
                            directory="products"
                            label="Product image"
                            description="PNG or JPG, max 5MB. Shows in product listings."
                            shape="rectangle"
                        />

                        {/* Published toggle */}
                        <div className="flex items-center gap-3">
                            <input
                                type="checkbox"
                                id="is_published"
                                checked={data.is_published}
                                onChange={(e) => setData('is_published', e.target.checked)}
                                className="rounded border-border text-brand-600 focus:ring-brand-500"
                            />
                            <label htmlFor="is_published" className="text-sm text-content-secondary">
                                Published
                            </label>
                        </div>

                        {/* Submit */}
                        <div className="flex items-center gap-3 pt-4 border-t border-border">
                            <button
                                type="submit"
                                disabled={processing}
                                className="btn-primary px-5 py-2.5"
                            >
                                {processing ? 'Saving...' : 'Save changes'}
                            </button>
                            <Link
                                href={route('seller.products.index')}
                                className="btn-ghost px-4 py-2.5"
                            >
                                Cancel
                            </Link>
                        </div>
                    </form>
                </div>
            </SellerLayout>
        </>
    );
}
