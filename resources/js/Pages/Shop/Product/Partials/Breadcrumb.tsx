import { Link } from '@inertiajs/react';
import { ChevronRightIcon } from '@heroicons/react/24/outline';
import { Category } from '@/Types';

interface Props {
    productName: string;
    category: Category | null;
}

export default function Breadcrumb({ productName, category }: Props) {
    return (
        <nav className="flex items-center gap-1.5 text-xs text-content-muted mb-6 flex-wrap">
            <Link href={route('home')} className="hover:text-brand-600">Home</Link>
            <ChevronRightIcon className="w-3.5 h-3.5" />
            <Link href={route('products.index')} className="hover:text-brand-600">Products</Link>
            {category && (
                <>
                    <ChevronRightIcon className="w-3.5 h-3.5" />
                    <Link
                        href={route('products.index', { category: category.id })}
                        className="hover:text-brand-600"
                    >
                        {category.name}
                    </Link>
                </>
            )}
            <ChevronRightIcon className="w-3.5 h-3.5" />
            <span className="text-content truncate">{productName}</span>
        </nav>
    );
}
