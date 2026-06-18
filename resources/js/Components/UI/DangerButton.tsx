import { ButtonHTMLAttributes } from 'react';

export default function DangerButton({
    className = '',
    disabled,
    children,
    ...props
}: ButtonHTMLAttributes<HTMLButtonElement>) {
    return (
        <button
            {...props}
            className={
                `inline-flex items-center rounded-lg border border-transparent bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-150 hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 active:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed ` + className
            }
            disabled={disabled}
        >
            {children}
        </button>
    );
}
