import { Head, Link, router } from '@inertiajs/react';
import ConfirmDialog from '@/Components/UI/ConfirmDialog';
import { useForm } from '@inertiajs/react';
import { PageProps, Product, PaginatedData } from '@/Types';
import SellerLayout from '@/Layouts/SellerLayout';
import {
    PlusIcon,
    PencilSquareIcon,
    TrashIcon,
    EyeIcon,
    EyeSlashIcon,
    MagnifyingGlassIcon,
    FunnelIcon,
} from '@heroicons/react/24/outline';
import { useState } from 'react';

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
    products: PaginatedData<Product>;
    stores: StoreOption[];
    categories: CategoryOption[];
    filters: {
        store_id: number | null;
        search: string;
    };
}

export default function SellerProducts({ products, stores, filters }: Props) {
    const [search, setSearch] = useState(filters.search);
    const [storeFilter, setStoreFilter] = useState(filters.store_id ?? '');

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        router.get(route('seller.products.index'), {
            search,
            store_id: storeFilter || undefined,
        }, { preserveState: true, replace: true });
    };

    const handleFilter = (val: string) => {
        setStoreFilter(val);
        router.get(route('seller.products.index'), {
            search,
            store_id: val || undefined,
        }, { preserveState: true, replace: true });
    };

    const [deleteProduct, setDeleteProduct] = useState<Product | null>(null);

    const handleDelete = (product: Product) => {
        setDeleteProduct(product);
    };

    const confirmDelete = () => {
        if (!deleteProduct) return;
        const id = deleteProduct.id;
        setDeleteProduct(null);
        router.delete(route('seller.products.destroy', id), {
            preserveScroll: true,
        });
    };

    const handleToggle = (product: Product) => {
        router.post(route('seller.products.toggle', product.id), {}, {
            preserveScroll: true,
        });
    };

    return (
        <>
            <Head title="My Products" />
            <SellerLayout header="Products">
                <div className="page-container py-6 sm:py-8 space-y-6">
                    {/* Actions bar */}
                    <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <form onSubmit={handleSearch} className="flex items-center gap-2 w-full sm:w-auto">
                            <div className="relative flex-1 sm:w-64">
                                <MagnifyingGlassIcon className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-content-muted" />
                                <input
                                    type="text"
                                    placeholder="Search products..."
                                    value={search}
                                    onChange={(e) => setSearch(e.target.value)}
                                    className="input pl-9 text-sm"
                                />
                            </div>
                            {stores.length > 1 && (
                                <select
                                    value={storeFilter}
                                    onChange={(e) => handleFilter(e.target.value)}
                                    className="input w-auto text-sm"
                                >
                                    <option value="">All stores</option>
                                    {stores.map((s) => (
                                        <option key={s.id} value={s.id}>{s.name}</option>
                                    ))}
                                </select>
                            )}
                        </form>
                        <Link href={route('seller.products.create')} className="btn-primary text-sm shrink-0">
                            <PlusIcon className="w-4 h-4" />
                            Add product
                        </Link>
                    </div>

                    {/* Products table */}
                    {products.data.length === 0 ? (
                        <div className="card text-center py-16">
                            <div className="size-16 rounded-full bg-brand-50 flex-center mx-auto mb-4">
                                <EyeIcon className="w-8 h-8 text-brand-400" />
                            </div>
                            <h2 className="text-lg font-semibold text-content mb-1">No products yet</h2>
                            <p className="text-sm text-content-secondary mb-6">
                                Add your first product to start selling.
                            </p>
                            <Link href={route('seller.products.create')} className="btn-primary px-5 py-2.5">
                                <PlusIcon className="w-4 h-4" />
                                Add product
                            </Link>
                        </div>
                    ) : (
                        <div className="card !p-0 overflow-hidden">
                            <div className="overflow-x-auto">
                                <table className="w-full text-sm">
                                    <thead>
                                        <tr className="border-b border-border bg-surface-raised text-left">
                                            <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider">Product</th>
                                            <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider hidden sm:table-cell">Store</th>
                                            <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider">Price</th>
                                            <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider hidden md:table-cell">Stock</th>
                                            <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider">Status</th>
                                            <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody className="divide-y divide-border">
                                        {products.data.map((product) => (
                                            <tr key={product.id} className="hover:bg-surface-page transition-colors">
                                                <td className="px-4 sm:px-6 py-3">
                                                    <div className="flex items-center gap-3">
                                                        <div className="size-10 rounded-lg bg-surface-raised flex-center shrink-0 overflow-hidden">
                                                            {product.images?.[0]?.path ? (
                                                                <img
                                                                    src={product.images[0].path}
                                                                    alt={product.name}
                                                                    className="size-full object-cover"
                                                                />
                                                            ) : (
                                                                <EyeIcon className="w-4 h-4 text-content-muted" />
                                                            )}
                                                        </div>
                                                        <div className="min-w-0">
                                                            <p className="font-medium text-content truncate max-w-[200px]">
                                                                {product.name}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td className="px-4 sm:px-6 py-3 text-content-secondary hidden sm:table-cell">
                                                    {product.store?.name}
                                                </td>
                                                <td className="px-4 sm:px-6 py-3 text-content font-medium">
                                                    PHP {product.price.toLocaleString()}
                                                </td>
                                                <td className="px-4 sm:px-6 py-3 text-content-secondary hidden md:table-cell">
                                                    {product.stock}
                                                </td>
                                                <td className="px-4 sm:px-6 py-3">
                                                    <button
                                                        type="button"
                                                        onClick={() => handleToggle(product)}
                                                        className={`inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium transition-colors ${
                                                            product.is_published
                                                                ? 'bg-green-50 text-green-700 hover:bg-green-100'
                                                                : 'bg-gray-50 text-gray-500 hover:bg-gray-100'
                                                        }`}
                                                    >
                                                        {product.is_published ? (
                                                            <><EyeIcon className="w-3 h-3" /> Published</>
                                                        ) : (
                                                            <><EyeSlashIcon className="w-3 h-3" /> Draft</>
                                                        )}
                                                    </button>
                                                </td>
                                                <td className="px-4 sm:px-6 py-3">
                                                    <div className="flex items-center justify-end gap-1">
                                                        <Link
                                                            href={route('seller.products.edit', product.id)}
                                                            className="btn-ghost p-1.5"
                                                            aria-label="Edit product"
                                                        >
                                                            <PencilSquareIcon className="w-4 h-4" />
                                                        </Link>
                                                        <button
                                                            type="button"
                                                            onClick={() => handleDelete(product)}
                                                            className="btn-ghost p-1.5 text-red-500 hover:bg-red-50"
                                                            aria-label="Delete product"
                                                        >
                                                            <TrashIcon className="w-4 h-4" />
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    )}

                    {/* Pagination */}
                    {products.meta && products.meta.last_page > 1 && (
                        <div className="flex items-center justify-between text-sm text-content-muted">
                            <span>
                                Showing {(products.meta.current_page - 1) * (products.meta.per_page || 15) + 1}
                                {' '}-{' '}
                                {Math.min(products.meta.current_page * (products.meta.per_page || 15), products.meta.total)}
                                {' '}of {products.meta.total}
                            </span>
                            <div className="flex gap-1">
                                {Array.from({ length: products.meta.last_page }, (_, i) => i + 1).map((page) => (
                                    <Link
                                        key={page}
                                        href={route('seller.products.index', { page, ...filters })}
                                        preserveState
                                        className={`px-3 py-1.5 rounded-md text-sm font-medium transition-colors ${
                                            page === products.meta.current_page
                                                ? 'bg-brand-50 text-brand-700'
                                                : 'text-content-secondary hover:bg-surface-raised'
                                        }`}
                                    >
                                        {page}
                                    </Link>
                                ))}
                            </div>
                        </div>
                    )}
                </div>

                {/* Confirm delete product */}
                <ConfirmDialog
                    open={deleteProduct !== null}
                    onClose={() => setDeleteProduct(null)}
                    onConfirm={confirmDelete}
                    title={`Delete "${deleteProduct?.name ?? ''}"?`}
                    message="This action cannot be undone. The product will be permanently removed."
                    confirmText="Delete product"
                    variant="danger"
                />
            </SellerLayout>
        </>
    );
}
