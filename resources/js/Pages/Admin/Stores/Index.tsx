import { Head, Link, router } from '@inertiajs/react';
import { PageProps, PaginatedData } from '@/Types';
import AdminLayout from '@/Layouts/AdminLayout';
import { MagnifyingGlassIcon } from '@heroicons/react/24/outline';
import { useState } from 'react';
import Reveal from '@/Components/Shared/Reveal';

interface StoreRow {
    id: number;
    name: string;
    slug: string;
    is_active: boolean;
    owner: string;
    products_count: number;
    created_at: string;
}

interface Props extends PageProps {
    stores: PaginatedData<StoreRow>;
    filters: { search: string; active: string };
}

export default function AdminStoresIndex({ stores, filters }: Props) {
    const [search, setSearch] = useState(filters.search);

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        router.get(route('admin.stores.index'), { search, active: filters.active || undefined }, { preserveState: true, replace: true });
    };

    const handleFilter = (active: string) => {
        router.get(route('admin.stores.index'), { search, active: active || undefined }, { preserveState: true, replace: true });
    };

    const handleToggle = (store: StoreRow) => {
        router.post(route('admin.stores.toggle', store.id), {}, { preserveScroll: true });
    };

    const handleDelete = (store: StoreRow) => {
        if (!confirm(`Delete store "${store.name}"? This cannot be undone.`)) return;
        router.delete(route('admin.stores.destroy', store.id), { preserveScroll: true });
    };

    return (
        <>
            <Head title="Stores" />
            <AdminLayout header="Stores">
                <div className="page-container py-6 sm:py-8 space-y-6">
                    {/* Filters */}
                    <Reveal>
                        <div className="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                            <form onSubmit={handleSearch} className="relative flex-1 sm:max-w-xs">
                                <MagnifyingGlassIcon className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-content-muted" />
                                <input
                                    type="text"
                                    placeholder="Search stores..."
                                    value={search}
                                    onChange={(e) => setSearch(e.target.value)}
                                    className="input pl-9 text-sm w-full"
                                />
                            </form>
                            <div className="flex items-center gap-2 flex-wrap">
                                {[{ value: '', label: 'All' }, { value: '1', label: 'Active' }, { value: '0', label: 'Inactive' }].map((opt) => (
                                    <button
                                        key={opt.value}
                                        onClick={() => handleFilter(opt.value)}
                                        className={`px-3 py-1.5 rounded-full text-xs font-medium border transition-all ${
                                            filters.active === opt.value || (!filters.active && opt.value === '')
                                                ? 'bg-brand-700 text-white border-brand-700'
                                                : 'bg-surface text-content border-border hover:border-brand-300'
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
                        {stores.data.length === 0 ? (
                            <div className="card text-center py-16">
                                <p className="text-sm text-content-muted">No stores found.</p>
                            </div>
                        ) : (
                            <div className="card !p-0 overflow-hidden">
                                <div className="overflow-x-auto">
                                    <table className="w-full text-sm">
                                        <thead>
                                            <tr className="border-b border-border bg-surface-raised text-left">
                                                <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider">Store</th>
                                                <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider hidden sm:table-cell">Owner</th>
                                                <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider hidden md:table-cell">Products</th>
                                                <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider">Status</th>
                                                <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider hidden lg:table-cell">Created</th>
                                                <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider text-right">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody className="divide-y divide-border">
                                            {stores.data.map((store) => (
                                                <tr key={store.id} className="hover:bg-surface-page transition-colors">
                                                    <td className="px-4 sm:px-6 py-3">
                                                        <Link href={route('admin.stores.show', store.id)} className="font-medium text-content hover:text-brand-700">
                                                            {store.name}
                                                        </Link>
                                                        <p className="text-xs text-content-muted">/{store.slug}</p>
                                                    </td>
                                                    <td className="px-4 sm:px-6 py-3 text-content-secondary hidden sm:table-cell">{store.owner}</td>
                                                    <td className="px-4 sm:px-6 py-3 text-content-secondary hidden md:table-cell">{store.products_count}</td>
                                                    <td className="px-4 sm:px-6 py-3">
                                                        <button type="button" onClick={() => handleToggle(store)}
                                                            className={`px-2 py-0.5 rounded-full text-[10px] font-medium transition-colors ${
                                                                store.is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200'
                                                            }`}
                                                        >
                                                            {store.is_active ? 'Active' : 'Inactive'}
                                                        </button>
                                                    </td>
                                                    <td className="px-4 sm:px-6 py-3 text-content-muted text-xs hidden lg:table-cell">{store.created_at}</td>
                                                    <td className="px-4 sm:px-6 py-3 text-right">
                                                        <div className="flex items-center justify-end gap-1">
                                                            <Link href={route('admin.stores.show', store.id)} className="btn-ghost text-xs px-2 py-1">
                                                                View
                                                            </Link>
                                                            <button type="button" onClick={() => handleDelete(store)} className="btn-ghost text-xs px-2 py-1 text-red-500 hover:bg-red-50">
                                                                Delete
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
                    </Reveal>

                    {/* Pagination */}
                    {stores.meta && stores.meta.last_page > 1 && (
                        <Reveal delay={200}>
                            <div className="flex items-center justify-between text-sm text-content-muted">
                                <span>Page {stores.meta.current_page} of {stores.meta.last_page}</span>
                                <div className="flex gap-1">
                                    {Array.from({ length: stores.meta.last_page }, (_, i) => i + 1).map((page) => (
                                        <Link
                                            key={page}
                                            href={route('admin.stores.index', { page, ...filters })}
                                            preserveState
                                            className={`px-3 py-1.5 rounded-md text-sm font-medium transition-colors ${
                                                page === stores.meta.current_page ? 'bg-brand-50 text-brand-700' : 'text-content-secondary hover:bg-surface-raised'
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
