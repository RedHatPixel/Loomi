import { Link } from '@inertiajs/react';

interface Props {
    withText?: boolean
}

export default function Logo({ withText = true, }: Props) {
    return (
        <Link href={route('home')} className="group flex shrink-0 items-center gap-2">
            <span className="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-600 text-sm font-bold text-white transition-transform duration-200 group-hover:rotate-3 group-hover:scale-105">
                Li
            </span>
            {withText && (
                <span className="text-base font-semibold text-content">Loomi</span>
            )}
        </Link>
    );
}
