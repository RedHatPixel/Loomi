import { Dialog, DialogPanel, DialogTitle, Transition, TransitionChild } from '@headlessui/react';

interface Props {
    open: boolean;
    onClose: () => void;
    onConfirm: () => void;
    title: string;
    message: string;
    confirmText?: string;
    cancelText?: string;
    variant?: 'danger' | 'warning' | 'info';
    loading?: boolean;
}

const variantButton = {
    danger:  'bg-red-600 hover:bg-red-500 focus-visible:outline-red-600',
    warning: 'bg-amber-600 hover:bg-amber-500 focus-visible:outline-amber-600',
    info:    'bg-brand-600 hover:bg-brand-500 focus-visible:outline-brand-600',
};

export default function ConfirmDialog({
    open,
    onClose,
    onConfirm,
    title,
    message,
    confirmText = 'Confirm',
    cancelText = 'Cancel',
    variant = 'danger',
    loading = false,
}: Props) {
    return (
        <Transition show={open} leave="duration-150">
            <Dialog as="div" className="fixed inset-0 z-50" onClose={loading ? () => {} : onClose}>
                {/* Semi-transparent backdrop */}
                <TransitionChild
                    enter="ease-out duration-200"
                    enterFrom="opacity-0"
                    enterTo="opacity-100"
                    leave="ease-in duration-150"
                    leaveFrom="opacity-100"
                    leaveTo="opacity-0"
                >
                    <div className="fixed inset-0 bg-black/20 transition-opacity" />
                </TransitionChild>

                {/* Panel — top-center, floating rectangular, like Chrome dialog */}
                <TransitionChild
                    enter="ease-out duration-200"
                    enterFrom="opacity-0 -translate-y-4"
                    enterTo="opacity-100 translate-y-0"
                    leave="ease-in duration-150"
                    leaveFrom="opacity-100 translate-y-0"
                    leaveTo="opacity-0 -translate-y-4"
                >
                    <div className="fixed inset-0 flex items-start justify-center pt-[15vh]">
                        <DialogPanel className="w-full max-w-sm rounded-xl bg-white shadow-2xl border border-gray-200 overflow-hidden">
                            {/* Content area */}
                            <div className="px-5 pt-5 pb-4">
                                <DialogTitle as="h3" className="text-sm font-semibold text-gray-900">
                                    {title}
                                </DialogTitle>
                                <p className="mt-2 text-sm text-gray-500 leading-relaxed">
                                    {message}
                                </p>
                            </div>

                            {/* Actions — bottom-right, like Chrome */}
                            <div className="flex items-center justify-end gap-2 px-5 py-3 bg-gray-50 border-t border-gray-200">
                                <button
                                    type="button"
                                    onClick={onClose}
                                    disabled={loading}
                                    className="rounded-lg border border-gray-300 bg-white px-3.5 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors disabled:opacity-50"
                                >
                                    {cancelText}
                                </button>
                                <button
                                    type="button"
                                    onClick={onConfirm}
                                    disabled={loading}
                                    className={`rounded-lg px-3.5 py-1.5 text-sm font-medium text-white transition-colors disabled:opacity-50 inline-flex items-center gap-1.5 ${variantButton[variant]}`}
                                >
                                    {loading && (
                                        <svg className="animate-spin h-3.5 w-3.5" fill="none" viewBox="0 0 24 24">
                                            <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                                            <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                        </svg>
                                    )}
                                    {loading ? 'Processing…' : confirmText}
                                </button>
                            </div>
                        </DialogPanel>
                    </div>
                </TransitionChild>
            </Dialog>
        </Transition>
    );
}
