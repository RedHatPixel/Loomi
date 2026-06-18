import { Head, Link, router } from '@inertiajs/react';
import { PageProps, PaginatedData } from '@/Types';
import AdminLayout from '@/Layouts/AdminLayout';
import { MagnifyingGlassIcon } from '@heroicons/react/24/outline';
import { useState } from 'react';
import Reveal from '@/Components/Shared/Reveal';

interface ProductRow {
    id: number;
    name: string;
    slug: string;
    price: number;
    stock: number;
    is_published: boolean;
    store: string;
    category: string | null;
    created_at: string;
}

interface StoreOption {
    id: number;
    name: string;
}

interface Props extends PageProps {
    products: PaginatedData<ProductRow>;
    storeOptions: StoreOption[];
    filters: { search: string; published: string; store_id: string };
}

export default function AdminProductsIndex({ products, storeOptions, filters }: Props) {
    const [search, setSearch] = useState(filters.search);

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        router.get(route('admin.products.index'), { search, published: filters.published, store_id: filters.store_id }, { preserveState: true, replace: true });
    };

    const handleFilter = (key: string, value: string) => {
        router.get(route('admin.products.index'), { search, [key]: value || undefined }, { preserveState: true, replace: true });
    };

    const handleToggle = (product: ProductRow) => {
        // Need product id - we'll use the id from the row
        router.post(route('admin.products.toggle', product.id), {}, { preserveScroll: true });
    };

    const handleDelete = (product: ProductRow) => {
        if (!confirm(`Delete product "${product.name}"? This cannot be undone.`)) return;
        router.delete(route('admin.products.destroy', product.id), { preserveScroll: true });
    };

    return (
        <>
            <Head title="Products" />
            <AdminLayout header="Products">
                <div className="page-container py-6 sm:py-8 space-y-6">
                    {/* Filters */}
                    <Reveal>
                        <div className="flex flex-col sm:flex-row items-start sm:items-center gap-3 flex-wrap">
                            <form onSubmit={handleSearch} className="relative flex-1 sm:max-w-xs">
                                <MagnifyingGlassIcon className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-content-muted" />
                                <input type="text" placeholder="Search products..." value={search} onChange={(e) => setSearch(e.target.value)} className="input pl-9 text-sm w-full" />
                            </form>
                            <select value={filters.store_id} onChange={(e) => handleFilter('store_id', e.target.value)} className="input w-auto text-sm">
                                <option value="">All stores</option>
                                {storeOptions.map((s) => (<option key={s.id} value={s.id}>{s.name}</option>))}
                            </select>
                            <div className="flex items-center gap-2 flex-wrap">
                                {[{ value: '', label: 'All' }, { value: '1', label: 'Published' }, { value: '0', label: 'Drafts' }].map((opt) => (
                                    <button key={opt.value} onClick={() => handleFilter('published', opt.value)}
                                        className={`px-3 py-1.5 rounded-full text-xs font-medium border transition-all ${
                                            filters.published === opt.value || (!filters.published && opt.value === '')
                                                ? 'bg-brand-700 text-white border-brand-700' : 'bg-surface text-content border-border hover:border-brand-300'
                                        }`}
                                    >
                                        {opt.label}
                                    </button>
                                ))}
                            </div>
                        </div>
                    </Reveal>

                    {/* Table */}
                    <Reveal delay={100}>
                        {products.data.length === 0 ? (
                            <div className="card text-center py-16"><p className="text-sm text-content-muted">No products found.</p></div>
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
                                                        <Link href={route('admin.products.show', product.id)} className="font-medium text-content hover:text-brand-700">
                                                            {product.name}
                                                        </Link>
                                                        {product.category && <p className="text-xs text-content-muted">{product.category}</p>}
                                                    </td>
                                                    <td className="px-4 sm:px-6 py-3 text-content-secondary hidden sm:table-cell">{product.store}</td>
                                                    <td className="px-4 sm:px-6 py-3 text-content font-medium">PHP {product.price.toLocaleString()}</td>
                                                    <td className="px-4 sm:px-6 py-3 text-content-secondary hidden md:table-cell">{product.stock}</td>
                                                    <td className="px-4 sm:px-6 py-3">
                                                        <button type="button" onClick={() => handleToggle(product)}
                                                            className={`px-2 py-0.5 rounded-full text-[10px] font-medium transition-colors ${
                                                                product.is_published ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                                                            }`}
                                                        >
                                                            {product.is_published ? 'Published' : 'Draft'}
                                                        </button>
                                                    </td>
                                                    <td className="px-4 sm:px-6 py-3 text-right">
                                                        <div className="flex items-center justify-end gap-1">
                                                            <Link href={route('admin.products.show', product.id)} className="btn-ghost text-xs px-2 py-1">View</Link>
                                                            <button type="button" onClick={() => handleDelete(product)} className="btn-ghost text-xs px-2 py-1 text-red-500 hover:bg-red-50">Delete</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        )}
                    </Reveal>

                    {products.meta && products.meta.last_page > 1 && (
                        <Reveal delay={200}>
                            <div className="flex items-center justify-between text-sm text-content-muted">
                                <span>Page {products.meta.current_page} of {products.meta.last_page}</span>
                                <div className="flex gap-1">
                                    {Array.from({ length: products.meta.last_page }, (_, i) => i + 1).map((page) => (
                                        <Link key={page} href={route('admin.products.index', { page, ...filters })} preserveState
                                            className={`px-3 py-1.5 rounded-md text-sm font-medium transition-colors ${
                                                page === products.meta.current_page ? 'bg-brand-50 text-brand-700' : 'text-content-secondary hover:bg-surface-raised'
                                            }`}
                                        >
                                            {page}
                                        </Link>
                                    ))}
                                </div>
                            </div>
                        </Reveal>
                    )}
                </div>
            </AdminLayout>
        </>
    );
}
