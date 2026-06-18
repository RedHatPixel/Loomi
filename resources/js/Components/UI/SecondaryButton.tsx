import { ButtonHTMLAttributes } from 'react';

export default function SecondaryButton({
    type = 'button',
    className = '',
    disabled,
    children,
    ...props
}: ButtonHTMLAttributes<HTMLButtonElement>) {
    return (
        <button
            {...props}
            type={type}
            className={
                `inline-flex items-center rounded-lg border border-border bg-white px-4 py-2.5 text-sm font-semibold text-content transition-all duration-150 hover:bg-surface-raised hover:border-border-strong focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed ` + className
            }
            disabled={disabled}
        >
            {children}
        </button>
    );
}
