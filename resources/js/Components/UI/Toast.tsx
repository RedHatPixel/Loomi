import { useEffect, useState } from 'react';
import { CheckCircleIcon, XCircleIcon, XMarkIcon } from '@heroicons/react/24/outline';

export type ToastVariant = 'success' | 'error';

interface ToastData {
    id: number;
    message: string;
    variant: ToastVariant;
}

let toastId = 0;
let addToastFn: ((message: string, variant: ToastVariant) => void) | null = null;

export function toast(message: string, variant: ToastVariant = 'success') {
    addToastFn?.(message, variant);
}

export default function ToastContainer() {
    const [toasts, setToasts] = useState<ToastData[]>([]);

    useEffect(() => {
        addToastFn = (message: string, variant: ToastVariant) => {
            const id = ++toastId;
            setToasts((prev) => [...prev, { id, message, variant }]);
            setTimeout(() => {
                setToasts((prev) => prev.filter((t) => t.id !== id));
            }, 4000);
        };
        return () => {
            addToastFn = null;
        };
    }, []);

    const remove = (id: number) => {
        setToasts((prev) => prev.filter((t) => t.id !== id));
    };

    if (toasts.length === 0) return null;

    return (
        <div className="fixed top-4 right-4 z-[100] flex flex-col gap-2 max-w-sm w-full pointer-events-none">
            {toasts.map((t) => (
                <div
                    key={t.id}
                    className={`pointer-events-auto flex items-start gap-3 rounded-xl px-4 py-3 shadow-lg border transition-all duration-300 animate-slide-in ${
                        t.variant === 'success'
                            ? 'bg-green-50 border-green-200 text-green-800'
                            : 'bg-red-50 border-red-200 text-red-800'
                    }`}
                >
                    {t.variant === 'success' ? (
                        <CheckCircleIcon className="w-5 h-5 shrink-0 text-green-500 mt-0.5" />
                    ) : (
                        <XCircleIcon className="w-5 h-5 shrink-0 text-red-500 mt-0.5" />
                    )}
                    <p className="text-sm flex-1">{t.message}</p>
                    <button
                        type="button"
                        onClick={() => remove(t.id)}
                        className="shrink-0 p-0.5 rounded hover:bg-black/5 transition-colors"
                    >
                        <XMarkIcon className="w-4 h-4" />
                    </button>
                </div>
            ))}
        </div>
    );
}
