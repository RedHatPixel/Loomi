import { router } from '@inertiajs/react';
import { FormEvent, useState } from 'react';

interface Props {
    initialValue?: string;
}

export default function SearchBar({ initialValue = '' }: Props) {
    const [value, setValue] = useState(initialValue);

    const submit = (e: FormEvent) => {
        e.preventDefault();
        router.get(route('products.index'), { search: value }, { preserveState: true, replace: true });
    };

    return (
        <form onSubmit={submit} className="relative w-full max-w-2xl mx-auto">
            <input
                type="text"
                value={value}
                onChange={(e) => setValue(e.target.value)}
                placeholder="Search products, brands, categories..."
                className="input pr-12 shadow-sm font-sans"
            />
            <button
                type="submit"
                className="absolute right-3 top-1/2 -translate-y-1/2 text-content-muted hover:text-brand-600 transition-colors"
                aria-label="Search"
            >
                <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                    <path strokeLinecap="round" strokeLinejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                </svg>
            </button>
        </form>
    );
}
