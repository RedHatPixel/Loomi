import { Head, Link, router, useForm } from '@inertiajs/react';
import { PageProps } from '@/Types';
import SellerLayout from '@/Layouts/SellerLayout';
import { useEffect, useState } from 'react';
import { CheckCircleIcon, TrashIcon } from '@heroicons/react/24/outline';
import ConfirmDialog from '@/Components/UI/ConfirmDialog';
import ImageUpload from '@/Components/Shared/ImageUpload';

interface StoreData {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    story: string | null;
    logo: string | null;
    background_image: string | null;
    website: string | null;
    instagram: string | null;
    tiktok: string | null;
    is_active: boolean;
}

interface Props extends PageProps {
    stores: StoreData[];
}

export default function SellerSettings({ stores }: Props) {
    const [activeStoreId, setActiveStoreId] = useState<number | null>(
        stores.length > 0 ? stores[0].id : null
    );
    const [deleting, setDeleting] = useState(false);

    const activeStore = stores.find((s) => s.id === activeStoreId);

    const { data, setData, patch, errors, processing, recentlySuccessful } = useForm({
        name: activeStore?.name ?? '',
        description: activeStore?.description ?? '',
        story: activeStore?.story ?? '',
        logo: activeStore?.logo ?? '',
        background_image: activeStore?.background_image ?? '',
        website: activeStore?.website ?? '',
        instagram: activeStore?.instagram ?? '',
        tiktok: activeStore?.tiktok ?? '',
        is_active: activeStore?.is_active ?? true,
    });

    useEffect(() => {
        if (activeStore) {
            setData({
                name: activeStore.name,
                description: activeStore.description ?? '',
                story: activeStore.story ?? '',
                logo: activeStore.logo ?? '',
                background_image: activeStore.background_image ?? '',
                website: activeStore.website ?? '',
                instagram: activeStore.instagram ?? '',
                tiktok: activeStore.tiktok ?? '',
                is_active: activeStore.is_active,
            });
        }
    }, [activeStoreId]);

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        if (!activeStoreId) return;
        patch(route('seller.stores.update', activeStoreId));
    };

    const [confirmDeleteStore, setConfirmDeleteStore] = useState(false);

    const handleDelete = () => {
        if (!activeStoreId || !activeStore) return;
        setConfirmDeleteStore(true);
    };

    const confirmDelete = () => {
        if (!activeStoreId || !activeStore) return;
        setConfirmDeleteStore(false);
        setDeleting(true);
        router.delete(route('seller.stores.destroy', activeStoreId), {
            onSuccess: () => {
                setDeleting(false);
                setActiveStoreId(null);
            },
            onError: () => setDeleting(false),
        });
    };

    if (stores.length === 0) {
        return (
            <>
                <Head title="Settings" />
                <SellerLayout header="Settings">
                    <div className="page-container py-6 sm:py-8">
                        <div className="card text-center py-16">
                            <h2 className="text-lg font-semibold text-content mb-2">No stores</h2>
                            <p className="text-sm text-content-secondary mb-6">
                                Create a store first to manage settings.
                            </p>
                            <Link href={route('seller.create')} className="btn-primary px-5 py-2.5">
                                Create your store
                            </Link>
                        </div>
                    </div>
                </SellerLayout>
            </>
        );
    }

    return (
        <>
            <Head title="Store Settings" />
            <SellerLayout header="Settings">
                <div className="page-container py-6 sm:py-8 max-w-3xl">
                    {/* Store selector */}
                    {stores.length > 1 && (
                        <div className="flex items-center gap-2 mb-6 flex-wrap">
                            <span className="text-sm text-content-muted">Store:</span>
                            {stores.map((s) => (
                                <button
                                    key={s.id}
                                    type="button"
                                    onClick={() => setActiveStoreId(s.id)}
                                    className={`px-3 py-1.5 rounded-lg text-sm font-medium transition-colors ${
                                        s.id === activeStoreId
                                            ? 'bg-brand-50 text-brand-700 border border-brand-200'
                                            : 'bg-surface-raised text-content-secondary border border-border hover:bg-surface-page'
                                    }`}
                                >
                                    {s.name}
                                </button>
                            ))}
                        </div>
                    )}

                    {/* Store preview card */}
                    {activeStore && (
                        <div className="card mb-6 overflow-hidden !p-0">
                            {/* Background image preview */}
                            <div
                                className="h-28 sm:h-36 bg-gradient-to-br from-brand-100 via-brand-50 to-brand-200 bg-cover bg-center"
                                style={data.background_image ? { backgroundImage: `url(${data.background_image})` } : undefined}
                            >
                                <div className="size-full bg-gradient-to-t from-black/30 to-transparent flex items-end p-4">
                                    <div className="size-12 rounded-full bg-white shadow-md flex-center ring-2 ring-white/80 overflow-hidden">
                                        {data.logo ? (
                                            <img src={data.logo} alt="Logo" className="size-full object-cover" />
                                        ) : (
                                            <span className="text-lg font-bold text-brand-700">
                                                {data.name.charAt(0).toUpperCase()}
                                            </span>
                                        )}
                                    </div>
                                </div>
                            </div>
                            <div className="p-4">
                                <h3 className="text-sm font-semibold text-content">{data.name || 'Store name'}</h3>
                                <p className="text-xs text-content-muted">loomi.com/stores/{activeStore.slug}</p>
                            </div>
                        </div>
                    )}

                    {activeStore && (
                        <form onSubmit={submit} className="space-y-6">
                            {/* Store name */}
                            <div>
                                <label className="label">Store name</label>
                                <input
                                    type="text"
                                    value={data.name}
                                    onChange={(e) => setData('name', e.target.value)}
                                    className="input"
                                />
                                {errors.name && <p className="form-error">{errors.name}</p>}
                            </div>

                            {/* Description */}
                            <div>
                                <label className="label">Description</label>
                                <textarea
                                    rows={3}
                                    value={data.description}
                                    onChange={(e) => setData('description', e.target.value)}
                                    className="input"
                                    placeholder="Short description of your store..."
                                />
                                {errors.description && <p className="form-error">{errors.description}</p>}
                                <p className="text-xs text-content-muted mt-1">
                                    Appears on your store card across the marketplace.
                                </p>
                            </div>

                            {/* Store URL (read-only) */}
                            <div>
                                <label className="label">Store URL</label>
                                <input
                                    type="text"
                                    value={`loomi.com/stores/${activeStore.slug}`}
                                    className="input bg-surface-raised text-content-muted cursor-not-allowed"
                                    disabled
                                />
                                <p className="text-xs text-content-muted mt-1">
                                    Store URLs cannot be changed after creation.
                                </p>
                            </div>

                            {/* Store logo — file upload */}
                            <ImageUpload
                                value={data.logo}
                                onChange={(path) => setData('logo', path ?? '')}
                                directory="stores"
                                label="Store logo"
                                description="Square image, at least 200×200px. Shows on your store card and page."
                                shape="circle"
                                previewSize="size-20"
                            />

                            {/* Background image — file upload */}
                            <ImageUpload
                                value={data.background_image}
                                onChange={(path) => setData('background_image', path ?? '')}
                                directory="stores"
                                label="Background image"
                                description="Banner image, recommended 1200×400px. Shows as the store header background."
                                shape="rectangle"
                                previewSize="w-full h-24"
                            />

                            {/* Social links */}
                            <div className="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label className="label">Website</label>
                                    <input
                                        type="url"
                                        value={data.website}
                                        onChange={(e) => setData('website', e.target.value)}
                                        className="input"
                                        placeholder="https://mybrand.com"
                                    />
                                </div>
                                <div>
                                    <label className="label">Instagram</label>
                                    <input
                                        type="text"
                                        value={data.instagram}
                                        onChange={(e) => setData('instagram', e.target.value)}
                                        className="input"
                                        placeholder="@yourbrand"
                                    />
                                </div>
                            </div>

                            <div>
                                <label className="label">TikTok</label>
                                <input
                                    type="text"
                                    value={data.tiktok}
                                    onChange={(e) => setData('tiktok', e.target.value)}
                                    className="input"
                                    placeholder="@yourbrand"
                                />
                            </div>

                            {/* Active toggle */}
                            <div className="flex items-center gap-3">
                                <input
                                    type="checkbox"
                                    id="is_active"
                                    checked={data.is_active}
                                    onChange={(e) => setData('is_active', e.target.checked)}
                                    className="rounded border-border text-brand-600 focus:ring-brand-500"
                                />
                                <div>
                                    <label htmlFor="is_active" className="text-sm font-medium text-content cursor-pointer">
                                        Store active
                                    </label>
                                    <p className="text-xs text-content-muted">
                                        When inactive, your store and products will not be visible to customers.
                                    </p>
                                </div>
                            </div>

                            {/* Submit */}
                            <div className="flex items-center justify-between pt-4 border-t border-border">
                                <button
                                    type="button"
                                    onClick={handleDelete}
                                    disabled={deleting}
                                    className="btn-danger inline-flex items-center gap-2 text-sm"
                                >
                                    <TrashIcon className="w-4 h-4" />
                                    {deleting ? 'Deleting...' : 'Delete store'}
                                </button>
                                <div className="flex items-center gap-3">
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="btn-primary px-5 py-2.5 inline-flex items-center gap-2"
                                    >
                                        {processing ? 'Saving...' : 'Save settings'}
                                    </button>
                                    {recentlySuccessful && (
                                        <span className="inline-flex items-center gap-1.5 text-sm text-green-600">
                                            <CheckCircleIcon className="w-4 h-4" />
                                            Saved
                                        </span>
                                    )}
                                </div>
                            </div>
                        </form>
                    )}
                </div>

                {/* Confirm delete store */}
                <ConfirmDialog
                    open={confirmDeleteStore}
                    onClose={() => setConfirmDeleteStore(false)}
                    onConfirm={confirmDelete}
                    title={`Delete "${activeStore?.name ?? ''}"?`}
                    message="This will permanently remove the store and all its products. This cannot be undone."
                    confirmText="Delete store"
                    variant="danger"
                    loading={deleting}
                />
            </SellerLayout>
        </>
    );
}
