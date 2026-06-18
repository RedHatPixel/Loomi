import { ButtonHTMLAttributes } from 'react';

export default function PrimaryButton({
    className = '',
    disabled,
    children,
    ...props
}: ButtonHTMLAttributes<HTMLButtonElement>) {
    return (
        <button
            {...props}
            className={
                `inline-flex items-center rounded-lg border border-transparent bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-150 hover:bg-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 active:bg-brand-700 disabled:opacity-50 disabled:cursor-not-allowed ` + className
            }
            disabled={disabled}
        >
            {children}
        </button>
    );
}
