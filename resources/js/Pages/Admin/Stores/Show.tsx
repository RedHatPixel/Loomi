import { Head, Link, router } from '@inertiajs/react';
import { PageProps, PaginatedData } from '@/Types';
import AdminLayout from '@/Layouts/AdminLayout';
import { ArrowLeftIcon, TrashIcon } from '@heroicons/react/24/outline';
import Reveal from '@/Components/Shared/Reveal';

interface StoreData {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    story: string | null;
    logo: string | null;
    is_active: boolean;
    owner: string;
    owner_id: number;
    created_at: string;
}

interface ProductRow {
    id: number;
    name: string;
    slug: string;
    price: number;
    stock: number;
    is_published: boolean;
    category: string | null;
}

interface Props extends PageProps {
    storeData: StoreData;
    products: PaginatedData<ProductRow>;
}

export default function AdminStoresShow({ storeData, products }: Props) {
    const handleToggle = () => {
        router.post(route('admin.stores.toggle', storeData.id), {}, { preserveScroll: true });
    };

    const handleDelete = () => {
        if (!confirm(`Delete store "${storeData.name}"? This cannot be undone.`)) return;
        router.delete(route('admin.stores.destroy', storeData.id));
    };

    return (
        <>
            <Head title={storeData.name} />
            <AdminLayout header={storeData.name}>
                <div className="page-container py-6 sm:py-8 space-y-6">
                    <Link href={route('admin.stores.index')} className="inline-flex items-center gap-1.5 text-sm text-content-muted hover:text-content transition-colors">
                        <ArrowLeftIcon className="w-4 h-4" />
                        Back to stores
                    </Link>

                    {/* Store info */}
                    <Reveal>
                        <div className="card p-6">
                            <div className="flex items-start gap-4">
                                <div className="size-14 rounded-full bg-brand-100 flex-center text-xl font-bold text-brand-700 shrink-0">
                                    {storeData.name.charAt(0).toUpperCase()}
                                </div>
                                <div className="flex-1 min-w-0">
                                    <h2 className="text-xl font-semibold text-content">{storeData.name}</h2>
                                    <p className="text-sm text-content-muted">/{storeData.slug}</p>
                                    <p className="text-xs text-content-muted mt-1">Owner: <Link href={route('admin.users.show', storeData.owner_id)} className="text-brand-600 hover:underline">{storeData.owner}</Link></p>
                                    <p className="text-xs text-content-muted">Created {storeData.created_at}</p>
                                </div>
                                <div className="flex items-center gap-2">
                                    <button type="button" onClick={handleToggle}
                                        className={`px-3 py-1.5 rounded-lg text-xs font-medium transition-colors ${
                                            storeData.is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200'
                                        }`}
                                    >
                                        {storeData.is_active ? 'Deactivate' : 'Activate'}
                                    </button>
                                    <button type="button" onClick={handleDelete} className="btn-ghost p-2 text-red-500 hover:bg-red-50" aria-label="Delete store">
                                        <TrashIcon className="w-4 h-4" />
                                    </button>
                                </div>
                            </div>
                            {storeData.description && <p className="text-sm text-content-secondary mt-4">{storeData.description}</p>}
                            {storeData.story && (
                                <div className="mt-4 p-4 rounded-lg bg-surface-raised">
                                    <p className="text-xs font-medium text-content-muted mb-1">Brand Story</p>
                                    <p className="text-sm text-content-secondary">{storeData.story}</p>
                                </div>
                            )}
                        </div>
                    </Reveal>

                    {/* Products */}
                    <Reveal delay={100}>
                        <section>
                            <h3 className="text-base font-semibold text-content mb-3">Products ({products.meta?.total ?? 0})</h3>
                            <div className="card !p-0 overflow-hidden">
                                {products.data.length === 0 ? (
                                    <div className="p-6 text-center text-sm text-content-muted">No products in this store.</div>
                                ) : (
                                    <div className="overflow-x-auto">
                                        <table className="w-full text-sm">
                                            <thead>
                                                <tr className="border-b border-border bg-surface-raised text-left">
                                                    <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider">Product</th>
                                                    <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider">Price</th>
                                                    <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider hidden sm:table-cell">Stock</th>
                                                    <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider hidden md:table-cell">Category</th>
                                                    <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider">Status</th>
                                                    <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider text-right">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody className="divide-y divide-border">
                                                {products.data.map((product) => (
                                                    <tr key={product.id} className="hover:bg-surface-page transition-colors">
                                                        <td className="px-4 sm:px-6 py-3">
                                                            <Link href={route('admin.products.show', product.id)} className="font-medium text-content hover:text-brand-700">
                                                                {product.name}
                                                            </Link>
                                                        </td>
                                                        <td className="px-4 sm:px-6 py-3 text-content font-medium">PHP {product.price.toLocaleString()}</td>
                                                        <td className="px-4 sm:px-6 py-3 text-content-secondary hidden sm:table-cell">{product.stock}</td>
                                                        <td className="px-4 sm:px-6 py-3 text-content-muted text-xs hidden md:table-cell">{product.category ?? '—'}</td>
                                                        <td className="px-4 sm:px-6 py-3">
                                                            <span className={`px-2 py-0.5 rounded-full text-[10px] font-medium ${product.is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600'}`}>
                                                                {product.is_published ? 'Published' : 'Draft'}
                                                            </span>
                                                        </td>
                                                        <td className="px-4 sm:px-6 py-3 text-right">
                                                            <Link href={route('admin.products.show', product.id)} className="btn-ghost text-xs px-2 py-1">
                                                                View
                                                            </Link>
                                                        </td>
                                                    </tr>
                                                ))}
                                            </tbody>
                                        </table>
                                    </div>
                                )}
                            </div>
                            {products.meta && products.meta.last_page > 1 && (
                                <div className="flex items-center justify-between text-sm text-content-muted mt-4">
                                    <span>Page {products.meta.current_page} of {products.meta.last_page}</span>
                                    <div className="flex gap-1">
                                        {Array.from({ length: products.meta.last_page }, (_, i) => i + 1).map((page) => (
                                            <Link key={page} href={route('admin.stores.show', [storeData.id, { page }])} preserveState
                                                className={`px-3 py-1.5 rounded-md text-sm font-medium transition-colors ${
                                                    page === products.meta.current_page ? 'bg-brand-50 text-brand-700' : 'text-content-secondary hover:bg-surface-raised'
                                                }`}
                                            >
                                                {page}
                                            </Link>
                                        ))}
                                    </div>
                                </div>
                            )}
                        </section>
                    </Reveal>
                </div>
            </AdminLayout>
        </>
    );
}
