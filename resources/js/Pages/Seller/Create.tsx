import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { PageProps } from '@/Types';
import ClientLayout from '@/Layouts/ClientLayout';
import { CheckIcon, ChevronLeftIcon, ChevronRightIcon } from '@heroicons/react/24/outline';
import ImageUpload from '@/Components/Shared/ImageUpload';
import {
    CREATE_STEPS,
    STORE_CATEGORIES,
    EXPERIENCE_LEVELS,
    EMPTY_FORM,
    type FormData,
} from '@/Constants/seller';

interface Props extends PageProps {}

export default function SellerCreate({ auth }: Props) {
    const [step, setStep] = useState(0);
    const [form, setForm] = useState<FormData>(EMPTY_FORM);
    const [errors, setErrors] = useState<Partial<Record<keyof FormData, string>>>({});
    const [submitting, setSubmitting] = useState(false);
    const [done, setDone] = useState(false);

    const update = <K extends keyof FormData>(key: K, value: FormData[K]) => {
        setForm((f) => ({ ...f, [key]: value }));
        setErrors((e) => ({ ...e, [key]: undefined }));
    };

    const totalSteps = CREATE_STEPS.length;
    const progress = ((step + 1) / totalSteps) * 100;
    const isFirst = step === 0;
    const isLast = step === totalSteps - 1;

    /* ── validation ── */
    const validateStep = (i: number): boolean => {
        const next: typeof errors = {};
        if (i === 0) {
            if (!form.name.trim()) next.name = 'Store name is required';
            else if (form.name.trim().length < 2) next.name = 'At least 2 characters';
            if (!form.slug.trim()) next.slug = 'Store URL is required';
            else if (!/^[a-z0-9-]+$/.test(form.slug)) next.slug = 'Only lowercase letters, numbers, and hyphens';
        }
        if (i === 1) {
            if (!form.description.trim()) next.description = 'Short description is required';
            if (!form.story.trim()) next.story = 'Tell us your brand story';
        }
        if (i === 3) {
            if (form.categories.length === 0) next.categories = 'Select at least one category';
        }
        if (i === 4) {
            if (!form.agree_terms) next.agree_terms = 'You must agree to continue';
        }
        setErrors(next);
        return Object.keys(next).length === 0;
    };

    const nextStep = () => {
        if (!validateStep(step)) return;
        if (isLast) return handleSubmit();
        setStep((s) => Math.min(s + 1, totalSteps - 1));
    };

    const prevStep = () => setStep((s) => Math.max(s - 1, 0));

    /* ── submit ── */
    const handleSubmit = () => {
        setSubmitting(true);
        router.post(route('seller.store'), {
                name: form.name,
                slug: form.slug,
                description: form.description,
                story: form.story,                        category: form.categories.join(', '),
                        experience: form.experience,
                website: form.website,
                instagram: form.instagram,
                tiktok: form.tiktok,
            }, {
            preserveScroll: true,
            onSuccess: () => {
                setDone(true);
                setSubmitting(false);
            },
            onError: () => setSubmitting(false),
        });
    };

    /* ── helpers ── */
    const autoSlug = (val: string) => {
        update('name', val);
        update('slug', val.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, ''));
    };

    const inputClass = (key: keyof FormData) =>
        `input w-full ${errors[key] ? 'border-status-danger focus:border-status-danger focus:ring-status-danger' : ''}`;

    const errorMsg = (key: keyof FormData) =>
        errors[key] && <p className="text-xs text-status-danger mt-1">{errors[key]}</p>;

    /* ── thankyou screen ── */
    if (done) {
        return (
            <>
                <Head title="Store created!" />
                <ClientLayout>
                    <div className="min-h-[80vh] flex-center">
                        <div className="max-w-lg mx-auto text-center px-4">
                            <div className="size-20 rounded-full bg-brand-100 flex-center mx-auto mb-6">
                                <CheckIcon className="w-10 h-10 text-brand-700" />
                            </div>
                            <h1 className="text-3xl font-semibold text-content tracking-tight mb-3">
                                Your store is being prepared!
                            </h1>
                            <p className="text-content-secondary mb-8">
                                We&rsquo;re setting everything up. You&rsquo;ll be able to start adding products
                                and customizing your storefront from your dashboard in just a moment.
                            </p>
                            <Link href={route('seller.dashboard')} className="btn-primary px-6 py-2.5">
                                Go to dashboard
                            </Link>
                        </div>
                    </div>
                </ClientLayout>
            </>
        );
    }

    return (
        <>
            <Head title="Open your store" />
            <ClientLayout>
                {/* ── progress bar ── */}
                <div className="fixed top-16 sm:top-20 left-0 right-0 z-40 h-1 bg-surface-raised">
                    <div
                        className="h-full bg-brand-600 transition-all duration-500 ease-out"
                        style={{ width: `${progress}%` }}
                    />
                </div>

                <div className="min-h-[calc(100vh-4rem)] sm:min-h-[calc(100vh-5rem)] flex flex-col lg:flex-row">
                    {/* ── sidebar ── */}
                    <aside className="hidden lg:flex flex-col w-72 shrink-0 border-r border-border bg-surface-page p-8 sticky top-20 self-start min-h-[calc(100vh-5rem)]">
                        <div className="mb-10">
                            <h2 className="text-xs font-semibold text-content-muted uppercase tracking-widest mb-6">
                                Set up your store
                            </h2>
                            <nav className="space-y-1">
                                {CREATE_STEPS.map((s, i) => {
                                    const Icon = s.icon;
                                    const isActive = i === step;
                                    const isDone = i < step;
                                    return (
                                        <button
                                            key={s.key}
                                            type="button"
                                            onClick={() => { if (i < step || validateStep(step)) setStep(i); }}
                                            className={`flex items-center gap-3 w-full px-3 py-2.5 rounded-lg text-sm font-medium transition-all ${
                                                isActive
                                                    ? 'bg-brand-50 text-brand-700'
                                                    : isDone
                                                        ? 'text-content-secondary hover:bg-surface-raised'
                                                        : 'text-content-disabled cursor-not-allowed'
                                            }`}
                                        >
                                            <span className={`flex-center size-7 rounded-full text-xs font-bold ${
                                                isDone
                                                    ? 'bg-brand-600 text-white'
                                                    : isActive
                                                        ? 'bg-brand-200 text-brand-700'
                                                        : 'bg-surface-raised text-content-disabled'
                                            }`}>
                                                {isDone ? <CheckIcon className="w-3.5 h-3.5" /> : i + 1}
                                            </span>
                                            <div className="text-left">
                                                <p className="text-xs font-medium">{s.title}</p>
                                                <p className="text-[10px] text-content-muted mt-0.5">{s.subtitle}</p>
                                            </div>
                                        </button>
                                    );
                                })}
                            </nav>
                        </div>

                        <div className="mt-auto pt-6 border-t border-border">
                            <p className="text-xs text-content-muted">
                                Step {step + 1} of {totalSteps}
                            </p>
                        </div>
                    </aside>

                    {/* ── main content ── */}
                    <div className="flex-1 flex-center px-4 sm:px-8 py-12">
                        <div className="w-full max-w-xl animate-fadeIn">
                            {/* Mobile step indicator */}
                            <div className="lg:hidden mb-8">
                                <div className="flex items-center gap-2 mb-2">
                                    {CREATE_STEPS.map((s, i) => {
                                        const Icon = s.icon;
                                        const isActive = i === step;
                                        const isDone = i < step;
                                        return (
                                            <div
                                                key={s.key}
                                                className={`flex-center size-8 rounded-full text-xs font-bold transition-all ${
                                                    isDone
                                                        ? 'bg-brand-600 text-white'
                                                        : isActive
                                                            ? 'bg-brand-200 text-brand-700 ring-2 ring-brand-400'
                                                            : 'bg-surface-raised text-content-disabled'
                                                }`}
                                            >
                                                {isDone ? <CheckIcon className="w-3.5 h-3.5" /> : i + 1}
                                            </div>
                                        );
                                    })}
                                </div>
                                <p className="text-xs text-content-muted">
                                    Step {step + 1} of {totalSteps} — {CREATE_STEPS[step].title}
                                </p>
                            </div>

                            {/* ── Step 0: Brand name ── */}
                            {step === 0 && (
                                <div className="space-y-6">
                                    <div>
                                        <h1 className="text-3xl font-semibold tracking-tight text-content">
                                            What&rsquo;s your brand called?
                                        </h1>
                                        <p className="text-content-secondary mt-2 text-sm">
                                            This will be your store name on Loomi. You can always change it later.
                                        </p>
                                    </div>

                                    <div>
                                        <label className="label">Store name</label>
                                        <input
                                            type="text"
                                            placeholder="e.g. Iron Loom, Studio Mare, Northbound Knits"
                                            value={form.name}
                                            onChange={(e) => autoSlug(e.target.value)}
                                            className={inputClass('name')}
                                            autoFocus
                                        />
                                        {errorMsg('name')}
                                    </div>

                                    <div>
                                        <label className="label">Store URL</label>
                                        <div className="flex items-center gap-1.5 text-sm text-content-muted">
                                            <span>loomi.com/stores/</span>
                                            <span className="input flex-1 bg-surface-raised cursor-default select-all">
                                                {form.slug || 'store-name'}
                                            </span>
                                        </div>
                                        <p className="text-xs text-content-muted mt-1">
                                            Auto-generated from your store name. Cannot be changed later.
                                        </p>
                                    </div>
                                </div>
                            )}

                            {/* ── Step 1: Your story ── */}
                            {step === 1 && (
                                <div className="space-y-6">
                                    <div>
                                        <h1 className="text-3xl font-semibold tracking-tight text-content">
                                            Tell us your story
                                        </h1>
                                        <p className="text-content-secondary mt-2 text-sm">
                                            Customers love hearing about the people behind the brand.
                                        </p>
                                    </div>

                                    <div>
                                        <label className="label">Short description</label>
                                        <input
                                            type="text"
                                            placeholder="e.g. Heavyweight denim, built to last decades."
                                            value={form.description}
                                            onChange={(e) => update('description', e.target.value)}
                                            className={inputClass('description')}
                                            autoFocus
                                        />
                                        {errorMsg('description')}
                                        <p className="text-xs text-content-muted mt-1">
                                            Appears on your store card — keep it punchy
                                        </p>
                                    </div>

                                    <div>
                                        <label className="label">Brand story</label>
                                        <textarea
                                            rows={5}
                                            placeholder="What inspired you to start? What makes your brand different? What should customers know before they buy?"
                                            value={form.story}
                                            onChange={(e) => update('story', e.target.value)}
                                            className={inputClass('story')}
                                        />
                                        {errorMsg('story')}
                                        <p className="text-xs text-content-muted mt-1">
                                            This goes on your store page. A few paragraphs is perfect.
                                        </p>
                                    </div>
                                </div>
                            )}

                            {/* ── Step 2: Brand identity ── */}
                            {step === 2 && (
                                <div className="space-y-6">
                                    <div>
                                        <h1 className="text-3xl font-semibold tracking-tight text-content">
                                            Brand identity
                                        </h1>
                                        <p className="text-content-secondary mt-2 text-sm">
                                            Add a logo and pick your brand&rsquo;s category.
                                        </p>
                                    </div>

                                    <ImageUpload
                                        value={form.logo}
                                        onChange={(path) => update('logo', path)}
                                        directory="stores"
                                        label="Logo"
                                        description="Square PNG or JPG, max 5MB, at least 400×400px. Optional — we'll use your brand initial."
                                        shape="circle"
                                        previewSize="size-20"
                                    />

                                    <div>
                                        <label className="label">Experience level</label>
                                        <div className="grid gap-2">
                                            {EXPERIENCE_LEVELS.map((opt) => (
                                                <button
                                                    key={opt.value}
                                                    type="button"
                                                    onClick={() => update('experience', opt.value)}
                                                    className={`text-left px-4 py-3 rounded-lg border text-sm font-medium transition-all ${
                                                        form.experience === opt.value
                                                            ? 'border-brand-600 bg-brand-50 text-brand-700'
                                                            : 'border-border text-content-secondary hover:border-brand-300 hover:bg-surface-raised'
                                                    }`}
                                                >
                                                    {opt.label}
                                                </button>
                                            ))}
                                        </div>
                                    </div>
                                </div>
                            )}

                            {/* ── Step 3: Details ── */}
                            {step === 3 && (
                                <div className="space-y-6">
                                    <div>
                                        <h1 className="text-3xl font-semibold tracking-tight text-content">
                                            Store details
                                        </h1>
                                        <p className="text-content-secondary mt-2 text-sm">
                                            Help customers find you and connect with your brand.
                                        </p>
                                    </div>

                                    <div>
                                        <label className="label">Categories</label>
                                        <div className="grid grid-cols-2 gap-2">
                                            {STORE_CATEGORIES.map((cat) => {
                                                const selected = form.categories.includes(cat);
                                                return (
                                                    <button
                                                        key={cat}
                                                        type="button"
                                                        onClick={() => {
                                                            const next = selected
                                                                ? form.categories.filter((c) => c !== cat)
                                                                : [...form.categories, cat];
                                                            update('categories', next);
                                                        }}
                                                        className={`text-left px-3 py-2.5 rounded-lg border text-sm font-medium transition-all ${
                                                            selected
                                                                ? 'border-brand-600 bg-brand-50 text-brand-700 ring-1 ring-brand-400'
                                                                : 'border-border text-content-secondary hover:border-brand-300 hover:bg-surface-raised'
                                                        }`}
                                                    >
                                                        {cat}
                                                    </button>
                                                );
                                            })}
                                        </div>
                                        {errors.categories && <p className="text-xs text-status-danger mt-1">{errors.categories}</p>}
                                    </div>

                                    <div className="grid sm:grid-cols-2 gap-4">
                                        <div>
                                            <label className="label">Website (optional)</label>
                                            <input
                                                type="url"
                                                placeholder="https://mybrand.com"
                                                value={form.website}
                                                onChange={(e) => update('website', e.target.value)}
                                                className="input"
                                            />
                                        </div>
                                        <div>
                                            <label className="label">Instagram (optional)</label>
                                            <input
                                                type="text"
                                                placeholder="@yourbrand"
                                                value={form.instagram}
                                                onChange={(e) => update('instagram', e.target.value)}
                                                className="input"
                                            />
                                        </div>
                                    </div>

                                    <div>
                                        <label className="label">TikTok (optional)</label>
                                        <input
                                            type="text"
                                            placeholder="@yourbrand"
                                            value={form.tiktok}
                                            onChange={(e) => update('tiktok', e.target.value)}
                                            className="input"
                                        />
                                    </div>
                                </div>
                            )}

                            {/* ── Step 4: Review ── */}
                            {step === 4 && (
                                <div className="space-y-6">
                                    <div>
                                        <h1 className="text-3xl font-semibold tracking-tight text-content">
                                            Review &amp; launch
                                        </h1>
                                        <p className="text-content-secondary mt-2 text-sm">
                                            Give everything one last look before you go live.
                                        </p>
                                    </div>

                                    <div className="card space-y-4">
                                        <div className="flex items-center gap-3 pb-3 border-b border-border">
                                            <div className="size-12 rounded-full bg-gradient-to-br from-brand-100 to-brand-200 flex-center">
                                                <span className="text-lg font-bold text-brand-700">
                                                    {form.name ? form.name.charAt(0).toUpperCase() : '?'}
                                                </span>
                                            </div>
                                            <div>
                                                <p className="text-sm font-semibold text-content">{form.name || 'Unnamed store'}</p>
                                                <p className="text-xs text-content-muted">loomi.com/stores/{form.slug || 'slug'}</p>
                                            </div>
                                        </div>

                                        <ReviewRow label="Description" value={form.description} />
                                        <ReviewRow label="Story" value={form.story} />
                                        <ReviewRow label="Categories" value={form.categories.length > 0 ? form.categories.join(', ') : undefined} />
                                        <ReviewRow label="Experience" value={EXPERIENCE_LEVELS.find(e => e.value === form.experience)?.label} />
                                        <ReviewRow label="Website" value={form.website} />
                                        <ReviewRow label="Instagram" value={form.instagram} />
                                        <ReviewRow label="TikTok" value={form.tiktok} />
                                    </div>

                                    <label className="flex items-start gap-3 cursor-pointer">
                                        <input
                                            type="checkbox"
                                            checked={form.agree_terms}
                                            onChange={(e) => update('agree_terms', e.target.checked)}
                                            className="mt-1 rounded border-border text-brand-600 focus:ring-brand-500"
                                        />
                                        <span className="text-sm text-content-secondary">
                                            I agree to the{' '}
                                            <a href="#" className="text-content-link hover:underline">Terms of Service</a>
                            {' '}and{' '}
                            <a href="#" className="text-content-link hover:underline">Seller Agreement</a>
                        </span>
                    </label>
                    {errorMsg('agree_terms')}
                </div>
            )}

            {/* ── navigation ── */}
            <div className="flex items-center justify-between mt-10 pt-6 border-t border-border">
                <div>
                    {!isFirst && (
                        <button
                            type="button"
                            onClick={prevStep}
                            className="btn-ghost inline-flex items-center gap-1.5 text-sm"
                        >
                            <ChevronLeftIcon className="w-4 h-4" />
                            Back
                        </button>
                    )}
                </div>
                <button
                    type="button"
                    onClick={nextStep}
                    disabled={submitting}
                    className="btn-primary inline-flex items-center gap-1.5 text-sm px-5 py-2.5"
                >
                    {submitting ? (
                        <>
                            <svg className="animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none">
                                <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                                <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                            </svg>
                            Creating…
                        </>
                    ) : (
                        <>
                            {isLast ? 'Launch store' : 'Continue'}
                            {!isLast && <ChevronRightIcon className="w-4 h-4" />}
                        </>
                    )}
                </button>
            </div>
        </div>
    </div>
</div>

</ClientLayout>
</>
);
}

/* ── review row helper ── */
function ReviewRow({ label, value }: { label: string; value: string | undefined | null }) {
if (!value) return null;
return (
<div className="flex items-start gap-2 text-sm">
    <span className="text-content-muted shrink-0 w-24">{label}</span>
    <span className="text-content">{value}</span>
</div>
);
}
