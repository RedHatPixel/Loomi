import { Head, Link, router } from '@inertiajs/react';
import { PageProps, CartItem } from '@/Types';
import ClientLayout from '@/Layouts/ClientLayout';
import { storageUrl } from '@/Utils/storage';
import { useState } from 'react';
import Reveal from '@/Components/Shared/Reveal';

interface Props extends PageProps {
    items: CartItem[];
    total: number;
}

function csrfToken(): string {
    return document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content ?? '';
}

export default function Cart({ auth, items, total }: Props) {
    const [notes, setNotes] = useState('');
    const [paymentMethod, setPaymentMethod] = useState<'cod' | 'prepaid'>('cod');
    const [cardType, setCardType] = useState('');
    const [cardNumber, setCardNumber] = useState('');
    const [cardPassword, setCardPassword] = useState('');
    const [processing, setProcessing] = useState(false);
    const [updating, setUpdating] = useState<Record<number, boolean>>({});
    const [error, setError] = useState<string | null>(null);

    const updateQuantity = async (itemId: number, quantity: number) => {
        if (quantity < 1) return;

        setUpdating((prev) => ({ ...prev, [itemId]: true }));
        setError(null);

        try {
            const res = await fetch(route('cart.update', itemId), {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken(),
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ quantity }),
            });

            if (!res.ok) {
                const data = await res.json().catch(() => ({}));
                throw new Error(data?.errors?.quantity?.[0] || data?.message || 'Failed to update quantity');
            }

            router.reload({ only: ['items', 'total'] });
        } catch (e: unknown) {
            const msg = e instanceof Error ? e.message : 'Something went wrong';
            setError(msg);
            setTimeout(() => setError(null), 4000);
        } finally {
            setUpdating((prev) => ({ ...prev, [itemId]: false }));
        }
    };

    const removeItem = async (itemId: number) => {
        setUpdating((prev) => ({ ...prev, [itemId]: true }));
        setError(null);

        try {
            const res = await fetch(route('cart.remove', itemId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken(),
                    'Accept': 'application/json',
                },
            });

            if (!res.ok) {
                throw new Error('Failed to remove item');
            }

            router.reload({ only: ['items', 'total'] });
        } catch (e: unknown) {
            const msg = e instanceof Error ? e.message : 'Something went wrong';
            setError(msg);
            setTimeout(() => setError(null), 4000);
        } finally {
            setUpdating((prev) => ({ ...prev, [itemId]: false }));
        }
    };

    const checkout = () => {
        setProcessing(true);
        router.post(route('orders.store'), {
            notes,
            payment_method: paymentMethod,
            card_type: paymentMethod === 'prepaid' ? cardType : undefined,
            card_number: paymentMethod === 'prepaid' ? cardNumber : undefined,
            card_password: paymentMethod === 'prepaid' ? cardPassword : undefined,
        });
    };

    if (items.length === 0) {
        return (
            <>
                <Head title="Cart" />
                <ClientLayout>
                    <div className="flex-1 flex items-center justify-center py-24">
                        <div className="text-center px-4">
                            <div className="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-surface-raised">
                                <svg className="h-10 w-10 text-content-disabled" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c.51 0 .962-.328 1.1-.82l1.485-5.758A1.125 1.125 0 0 0 17.43 8.25H5.907M7.5 14.25 5.106 5.272M7.5 14.25l-1.5 6m10.5-6 1.5 6" />
                                </svg>
                            </div>
                            <h2 className="text-xl font-semibold text-content mb-2">Your cart is empty</h2>
                            <p className="text-content-muted text-sm mb-6 max-w-xs mx-auto">
                                Looks like you haven't added anything yet. Explore our products and find something you love.
                            </p>
                            <Link href={route('products.index')} className="btn-primary inline-flex items-center gap-2">
                                <svg className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                                Browse products
                            </Link>
                        </div>
                    </div>
                </ClientLayout>
            </>
        );
    }

    return (
        <>
            <Head title="Cart" />
            <ClientLayout>
                {/* Error toast */}
                {error && (
                    <div className="fixed top-20 right-4 z-50 max-w-sm rounded-lg bg-status-danger text-white px-4 py-3 text-sm shadow-lg transition-all duration-300">
                        {error}
                    </div>
                )}

                <div className="page-container py-6 lg:py-10">
                    {/* Header */}
                    <Reveal>
                    <div className="flex items-center justify-between mb-6 lg:mb-8">
                        <div>
                            <h1 className="text-2xl font-bold text-content">Shopping Cart</h1>
                            <p className="text-sm text-content-muted mt-1">{items.length} {items.length === 1 ? 'item' : 'items'}</p>
                        </div>
                        <Link href={route('products.index')} className="text-sm text-content-link hover:text-brand-700 transition-colors font-medium">
                            Continue shopping
                        </Link>
                    </div>
                    </Reveal>

                    <div className="flex flex-col lg:flex-row gap-6 lg:gap-10">

                        {/* ── Items list ── */}
                        <Reveal delay={100} className="flex-1 min-w-0">
                        <div className="flex-1 min-w-0 space-y-4">
                            {items.map((item) => {
                                const isUpdating = updating[item.id];

                                return (
                                    <div
                                        key={item.id}
                                        className={`card flex gap-4 sm:gap-5 p-4 sm:p-5 transition-all duration-200 ${
                                            isUpdating ? 'opacity-60' : 'hover:shadow-md'
                                        }`}
                                    >
                                        {/* Product image */}
                                        <div className="size-20 sm:size-24 rounded-xl bg-surface-raised overflow-hidden shrink-0 relative">
                                            {item.product.image ? (
                                                <img
                                                    src={item.product.image.startsWith('http') ? item.product.image : storageUrl(item.product.image)}
                                                    alt={item.product.name}
                                                    className="w-full h-full object-cover"
                                                />
                                            ) : (
                                                <div className="w-full h-full flex items-center justify-center text-content-disabled">
                                                    <svg className="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
                                                        <path strokeLinecap="round" strokeLinejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            )}
                                            {isUpdating && (
                                                <div className="absolute inset-0 flex items-center justify-center bg-white/40 rounded-xl">
                                                    <svg className="animate-spin h-5 w-5 text-brand-600" fill="none" viewBox="0 0 24 24">
                                                        <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                                                        <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                                    </svg>
                                                </div>
                                            )}
                                        </div>

                                        {/* Product info + controls */}
                                        <div className="flex-1 min-w-0 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                                            <div className="flex-1 min-w-0">
                                                <Link
                                                    href={route('products.show', item.product.slug)}
                                                    className="text-sm sm:text-base font-medium text-content hover:text-brand-700 transition-colors line-clamp-2"
                                                >
                                                    {item.product.name}
                                                </Link>
                                                <p className="text-xs sm:text-sm text-content-muted mt-0.5">{item.product.store.name}</p>
                                                <p className="text-sm sm:text-base font-semibold text-brand-700 mt-1.5">
                                                    ₱{Number(item.product.price).toLocaleString('en-PH', { minimumFractionDigits: 2 })}
                                                </p>
                                            </div>

                                            <div className="flex sm:flex-col items-center sm:items-end gap-3 sm:gap-2 shrink-0">
                                                {/* Quantity controls */}
                                                <div className="flex items-center border border-border rounded-lg overflow-hidden">
                                                    <button
                                                        onClick={() => updateQuantity(item.id, item.quantity - 1)}
                                                        disabled={item.quantity <= 1 || isUpdating}
                                                        className="px-2.5 py-1.5 text-content-secondary hover:bg-surface-raised transition-colors text-sm disabled:opacity-40 disabled:cursor-not-allowed"
                                                        aria-label="Decrease quantity"
                                                    >
                                                        <svg className="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2.5}>
                                                            <path strokeLinecap="round" strokeLinejoin="round" d="M5 12h14" />
                                                        </svg>
                                                    </button>
                                                    <span className="px-3 py-1.5 text-sm font-medium text-content border-x border-border min-w-[2.5rem] text-center tabular-nums">
                                                        {item.quantity}
                                                    </span>
                                                    <button
                                                        onClick={() => updateQuantity(item.id, item.quantity + 1)}
                                                        disabled={item.quantity >= item.product.stock || isUpdating}
                                                        className="px-2.5 py-1.5 text-content-secondary hover:bg-surface-raised transition-colors text-sm disabled:opacity-40 disabled:cursor-not-allowed"
                                                        aria-label="Increase quantity"
                                                    >
                                                        <svg className="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2.5}>
                                                            <path strokeLinecap="round" strokeLinejoin="round" d="M12 5v14m7-7H5" />
                                                        </svg>
                                                    </button>
                                                </div>

                                                {/* Line total + remove */}
                                                <div className="flex items-center gap-3">
                                                    <p className="text-sm font-semibold text-content tabular-nums">
                                                        ₱{(item.product.price * item.quantity).toLocaleString('en-PH', { minimumFractionDigits: 2 })}
                                                    </p>
                                                    <button
                                                        onClick={() => removeItem(item.id)}
                                                        disabled={isUpdating}
                                                        className="text-content-muted hover:text-status-danger transition-colors p-1 -m-1 disabled:opacity-40"
                                                        aria-label="Remove item"
                                                    >
                                                        <svg className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                                                            <path strokeLinecap="round" strokeLinejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                        </Reveal>

                        {/* ── Order summary ── */}
                        <Reveal delay={200} className="w-full lg:w-96 shrink-0">
                        <div className="w-full lg:w-96 shrink-0">
                            <div className="card p-5 sm:p-6 space-y-5 sticky top-24">
                                <h2 className="text-lg font-semibold text-content">Order summary</h2>

                                {/* Items breakdown */}
                                <div className="space-y-3 max-h-48 overflow-y-auto pr-1 -mr-1">
                                    {items.map((item) => (
                                        <div key={item.id} className="flex items-start gap-3">
                                            <div className="size-10 rounded-lg bg-surface-raised overflow-hidden shrink-0">
                                                {item.product.image ? (
                                                    <img
                                                        src={item.product.image.startsWith('http') ? item.product.image : storageUrl(item.product.image)}
                                                        alt={item.product.name}
                                                        className="w-full h-full object-cover"
                                                    />
                                                ) : (
                                                    <div className="w-full h-full flex items-center justify-center text-content-disabled text-[8px]">
                                                        No img
                                                    </div>
                                                )}
                                            </div>
                                            <div className="flex-1 min-w-0">
                                                <p className="text-sm text-content truncate">{item.product.name}</p>
                                                <p className="text-xs text-content-muted">Qty: {item.quantity}</p>
                                            </div>
                                            <p className="text-sm font-medium text-content shrink-0 tabular-nums">
                                                ₱{(item.product.price * item.quantity).toLocaleString('en-PH', { minimumFractionDigits: 2 })}
                                            </p>
                                        </div>
                                    ))}
                                </div>

                                <div className="border-t border-border pt-4 space-y-2">
                                    <div className="flex justify-between text-sm">
                                        <span className="text-content-secondary">Subtotal</span>
                                        <span className="text-content font-medium tabular-nums">
                                            ₱{Number(total).toLocaleString('en-PH', { minimumFractionDigits: 2 })}
                                        </span>
                                    </div>
                                    <div className="flex justify-between text-sm">
                                        <span className="text-content-secondary">Shipping</span>
                                        <span className="text-content-muted">Calculated at checkout</span>
                                    </div>
                                </div>

                                <div className="border-t border-border pt-4 flex justify-between items-baseline">
                                    <span className="text-base font-semibold text-content">Total</span>
                                    <span className="text-xl font-bold text-brand-700 tabular-nums">
                                        ₱{Number(total).toLocaleString('en-PH', { minimumFractionDigits: 2 })}
                                    </span>
                                </div>

                                {/* Payment method */}
                                <div className="border-t border-border pt-4">
                                    <h3 className="text-sm font-semibold text-content mb-3">Payment method</h3>
                                    <div className="space-y-2">
                                        <label className={`flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition-colors ${paymentMethod === 'cod' ? 'border-brand-600 bg-brand-50' : 'border-border hover:border-brand-300'}`}>
                                            <input
                                                type="radio"
                                                name="payment_method"
                                                value="cod"
                                                checked={paymentMethod === 'cod'}
                                                onChange={() => setPaymentMethod('cod')}
                                                className="text-brand-600 focus:ring-brand-500"
                                            />
                                            <div>
                                                <p className="text-sm font-medium text-content">Cash on Delivery</p>
                                                <p className="text-xs text-content-muted">Pay when you receive your order</p>
                                            </div>
                                        </label>
                                        <label className={`flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition-colors ${paymentMethod === 'prepaid' ? 'border-brand-600 bg-brand-50' : 'border-border hover:border-brand-300'}`}>
                                            <input
                                                type="radio"
                                                name="payment_method"
                                                value="prepaid"
                                                checked={paymentMethod === 'prepaid'}
                                                onChange={() => setPaymentMethod('prepaid')}
                                                className="text-brand-600 focus:ring-brand-500"
                                            />
                                            <div>
                                                <p className="text-sm font-medium text-content">Online Payment</p>
                                                <p className="text-xs text-content-muted">Pay with credit/debit card</p>
                                            </div>
                                        </label>
                                    </div>

                                    {paymentMethod === 'prepaid' && (
                                        <div className="mt-3 space-y-3 pl-2">
                                            <div>
                                                <label className="label text-xs">Card type</label>
                                                <select
                                                    value={cardType}
                                                    onChange={(e) => setCardType(e.target.value)}
                                                    className="input text-sm"
                                                >
                                                    <option value="">Select card type</option>
                                                    <option value="visa">Visa</option>
                                                    <option value="mastercard">Mastercard</option>
                                                    <option value="amex">American Express</option>
                                                    <option value="jcb">JCB</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label className="label text-xs">Card number</label>
                                                <input
                                                    type="text"
                                                    value={cardNumber}
                                                    onChange={(e) => setCardNumber(e.target.value.replace(/\D/g, '').slice(0, 16))}
                                                    placeholder="1234 5678 9012 3456"
                                                    className="input text-sm"
                                                />
                                            </div>
                                            <div>
                                                <label className="label text-xs">CVV / Password</label>
                                                <input
                                                    type="password"
                                                    value={cardPassword}
                                                    onChange={(e) => setCardPassword(e.target.value.slice(0, 4))}
                                                    placeholder="***"
                                                    className="input text-sm"
                                                />
                                            </div>
                                        </div>
                                    )}
                                </div>

                                <button
                                    onClick={checkout}
                                    disabled={processing}
                                    className="btn-primary w-full py-2.5 text-sm flex items-center justify-center gap-2"
                                >
                                    {processing ? (
                                        <>
                                            <svg className="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                                <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                                                <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                            </svg>
                                            Placing order…
                                        </>
                                    ) : (
                                        <>
                                            <svg className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                                                <path strokeLinecap="round" strokeLinejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Place order
                                        </>
                                    )}
                                </button>
                            </div>
                        </div>
                        </Reveal>

                    </div>
                </div>
            </ClientLayout>
        </>
    );
}
