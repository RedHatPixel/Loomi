import { Head, Link, router } from '@inertiajs/react';
import { PageProps, PaginatedData } from '@/Types';
import AdminLayout from '@/Layouts/AdminLayout';
import { useState } from 'react';
import Reveal from '@/Components/Shared/Reveal';

interface CategoryRow {
    id: number;
    name: string;
    slug: string;
    products_count: number;
    created_at: string;
}

interface Props extends PageProps {
    categories: PaginatedData<CategoryRow>;
}

export default function AdminCategoriesIndex({ categories }: Props) {
    const [editingId, setEditingId] = useState<number | null>(null);
    const [editName, setEditName] = useState('');
    const [newName, setNewName] = useState('');

    const createCategory = (e: React.FormEvent) => {
        e.preventDefault();
        router.post(route('admin.categories.store'), { name: newName }, {
            preserveScroll: true,
            onSuccess: () => setNewName(''),
        });
    };

    const startEdit = (cat: CategoryRow) => {
        setEditingId(cat.id);
        setEditName(cat.name);
    };

    const saveEdit = (id: number) => {
        router.patch(route('admin.categories.update', id), { name: editName }, {
            preserveScroll: true,
            onSuccess: () => setEditingId(null),
        });
    };

    const cancelEdit = () => {
        setEditingId(null);
        setEditName('');
    };

    const handleDelete = (cat: CategoryRow) => {
        if (!confirm(`Delete category "${cat.name}"?`)) return;
        router.delete(route('admin.categories.destroy', cat.id), { preserveScroll: true });
    };

    return (
        <>
            <Head title="Categories" />
            <AdminLayout header="Categories">
                <div className="page-container py-6 sm:py-8 space-y-6">
                    {/* Add new */}
                    <Reveal>
                        <form onSubmit={createCategory} className="card p-5">
                            <label className="label text-sm mb-2">Add new category</label>
                            <div className="flex items-center gap-2">
                                <input type="text" placeholder="Category name" value={newName} onChange={(e) => setNewName(e.target.value)}
                                    className="input text-sm flex-1" required />
                                <button type="submit" className="btn-primary text-sm px-4 py-2">Create</button>
                            </div>
                        </form>
                    </Reveal>

                    {/* Categories table */}
                    <Reveal delay={100}>
                        <div className="card !p-0 overflow-hidden">
                            <div className="overflow-x-auto">
                                <table className="w-full text-sm">
                                    <thead>
                                        <tr className="border-b border-border bg-surface-raised text-left">
                                            <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider">Name</th>
                                            <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider">Slug</th>
                                            <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider hidden sm:table-cell">Products</th>
                                            <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider hidden md:table-cell">Created</th>
                                            <th className="px-4 sm:px-6 py-3 font-medium text-content-muted text-xs uppercase tracking-wider text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody className="divide-y divide-border">
                                        {categories.data.map((cat) => (
                                            <tr key={cat.id} className="hover:bg-surface-page transition-colors">
                                                <td className="px-4 sm:px-6 py-3">
                                                    {editingId === cat.id ? (
                                                        <input type="text" value={editName} onChange={(e) => setEditName(e.target.value)}
                                                            className="input text-sm py-1 px-2 w-full max-w-xs" autoFocus />
                                                    ) : (
                                                        <span className="font-medium text-content">{cat.name}</span>
                                                    )}
                                                </td>
                                                <td className="px-4 sm:px-6 py-3 text-content-muted">{cat.slug}</td>
                                                <td className="px-4 sm:px-6 py-3 text-content-secondary hidden sm:table-cell">{cat.products_count}</td>
                                                <td className="px-4 sm:px-6 py-3 text-content-muted text-xs hidden md:table-cell">{cat.created_at}</td>
                                                <td className="px-4 sm:px-6 py-3 text-right">
                                                    {editingId === cat.id ? (
                                                        <div className="flex items-center justify-end gap-1">
                                                            <button type="button" onClick={() => saveEdit(cat.id)} className="btn-primary text-xs px-2 py-1">Save</button>
                                                            <button type="button" onClick={cancelEdit} className="btn-ghost text-xs px-2 py-1">Cancel</button>
                                                        </div>
                                                    ) : (
                                                        <div className="flex items-center justify-end gap-1">
                                                            <button type="button" onClick={() => startEdit(cat)} className="btn-ghost text-xs px-2 py-1">Edit</button>
                                                            <button type="button" onClick={() => handleDelete(cat)} disabled={cat.products_count > 0}
                                                                className="btn-ghost text-xs px-2 py-1 text-red-500 hover:bg-red-50 disabled:opacity-40 disabled:cursor-not-allowed"
                                                                title={cat.products_count > 0 ? 'Cannot delete: has products' : 'Delete category'}
                                                            >
                                                                Delete
                                                            </button>
                                                        </div>
                                                    )}
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </Reveal>

                    {/* Pagination */}
                    {categories.meta && categories.meta.last_page > 1 && (
                        <div className="flex items-center justify-between text-sm text-content-muted">
                            <span>Page {categories.meta.current_page} of {categories.meta.last_page}</span>
                            <div className="flex gap-1">
                                {Array.from({ length: categories.meta.last_page }, (_, i) => i + 1).map((page) => (
                                    <Link key={page} href={route('admin.categories.index', { page })} preserveState
                                        className={`px-3 py-1.5 rounded-md text-sm font-medium transition-colors ${
                                            page === categories.meta.current_page ? 'bg-brand-50 text-brand-700' : 'text-content-secondary hover:bg-surface-raised'
                                        }`}
                                    >
                                        {page}
                                    </Link>
                                ))}
                            </div>
                        </div>
                    )}
                </div>
            </AdminLayout>
        </>
    );
}
