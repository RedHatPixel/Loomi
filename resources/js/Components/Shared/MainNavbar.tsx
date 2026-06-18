import { Link } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { User } from '@/Types';
import SearchBar from '@/Components/Shared/SearchBar';
import UserDropdown from '@/Components/Shared/UserDropdown';

import {
    Bars3Icon,
    XMarkIcon,
} from '@heroicons/react/24/outline';
import { AUTH_NAV_LINKS } from '@/Constants/navigation';
import Logo from '../Logo';

interface Props {
    user: User | null;
    search?: string;
}

export default function MainNavbar({ user, search = '' }: Props) {
    const [scrolled, setScrolled] = useState(false);
    const [mobileOpen, setMobileOpen] = useState(false);

    useEffect(() => {
        const onScroll = () => setScrolled(window.scrollY > 8);
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
        return () => window.removeEventListener('scroll', onScroll);
    }, []);

    const linkClass = (routeName: string) =>
        `p-2 text-sm rounded-md font-medium transition-all duration-150 sm:px-3 ${
            route().current(routeName)
                ? 'bg-surface-raised text-brand-700 underline'
                : 'btn-ghost text-content'
        }`;

    return (
        <header
            className={`sticky top-0 z-50 border-b border-border bg-surface/90 backdrop-blur-sm transition-shadow duration-200 ${
                scrolled ? 'shadow-sm' : ''
            }`}
        >
            <div className="page-container flex-between h-16 gap-3 sm:h-[80px] sm:gap-4">
                <Logo />

                <div className="hidden max-w-xl flex-1 sm:block">
                    <SearchBar initialValue={search} />
                </div>

                <nav className="flex items-center gap-0.5 shrink-0 sm:gap-2">
                    {user ? (
                        <>
                            {/* Desktop nav links */}
                            <div className="hidden sm:flex items-center gap-0.5">
                                {AUTH_NAV_LINKS.map((link) => (
                                    <Link
                                        key={link.label}
                                        href={link.href}
                                        className={linkClass(link.routeName)}
                                    >
                                        {link.label}
                                    </Link>
                                ))}
                            </div>

                            <UserDropdown user={user} />

                            {/* Mobile hamburger */}
                            <button
                                type="button"
                                onClick={() => setMobileOpen((o) => !o)}
                                className="sm:hidden btn-ghost p-2"
                                aria-label="Toggle navigation menu"
                            >
                                {mobileOpen ? (
                                    <XMarkIcon className="w-5 h-5" />
                                ) : (
                                    <Bars3Icon className="w-5 h-5" />
                                )}
                            </button>
                        </>
                    ) : (
                        <>
                            <Link href={route('products.index')} className="hidden sm:inline-flex btn-ghost text-xs sm:text-sm">
                                Products
                            </Link>
                            <Link href={route('stores.index')} className="hidden sm:inline-flex btn-ghost text-xs sm:text-sm">
                                Stores
                            </Link>
                            <div className="w-px h-5 bg-border" />
                            <Link href={route('login')} className="btn-ghost text-xs sm:text-sm">
                                Log in
                            </Link>
                            <Link href={route('register')} className="btn-primary text-xs sm:text-sm">
                                Sign up
                            </Link>

                            {/* Mobile hamburger */}
                            <button
                                type="button"
                                onClick={() => setMobileOpen((o) => !o)}
                                className="sm:hidden btn-ghost p-2"
                                aria-label="Toggle navigation menu"
                            >
                                {mobileOpen ? (
                                    <XMarkIcon className="w-5 h-5" />
                                ) : (
                                    <Bars3Icon className="w-5 h-5" />
                                )}
                            </button>
                        </>
                    )}
                </nav>
            </div>

            <div className="sm:hidden px-4 pb-3">
                <SearchBar initialValue={search} />
            </div>

            {/* Mobile navigation */}
            {mobileOpen && (
                <div className="sm:hidden border-t border-border bg-surface">
                    <nav className="page-container py-3 space-y-1">
                        {user ? (
                            <>
                                {AUTH_NAV_LINKS.map((link) => (
                                    <Link
                                        key={link.label}
                                        href={link.href}
                                        onClick={() => setMobileOpen(false)}
                                        className={`block px-3 py-2 rounded-md text-sm font-medium transition-colors ${
                                            route().current(link.routeName)
                                                ? 'bg-brand-50 text-brand-700'
                                                : 'text-content-secondary hover:text-content hover:bg-surface-raised'
                                        }`}
                                    >
                                        {link.label}
                                    </Link>
                                ))}
                            </>
                        ) : (
                            <>
                                <Link
                                    href={route('products.index')}
                                    onClick={() => setMobileOpen(false)}
                                    className="block px-3 py-2 rounded-md text-sm font-medium text-content-secondary hover:text-content hover:bg-surface-raised transition-colors"
                                >
                                    Products
                                </Link>
                                <Link
                                    href={route('stores.index')}
                                    onClick={() => setMobileOpen(false)}
                                    className="block px-3 py-2 rounded-md text-sm font-medium text-content-secondary hover:text-content hover:bg-surface-raised transition-colors"
                                >
                                    Stores
                                </Link>
                                <hr className="my-2" />
                                <Link
                                    href={route('login')}
                                    onClick={() => setMobileOpen(false)}
                                    className="block px-3 py-2 rounded-md text-sm font-medium text-content-secondary hover:text-content hover:bg-surface-raised transition-colors"
                                >
                                    Log in
                                </Link>
                                <Link
                                    href={route('register')}
                                    onClick={() => setMobileOpen(false)}
                                    className="block px-3 py-2 rounded-md text-sm font-medium text-brand-600 hover:text-brand-700 hover:bg-brand-50 transition-colors"
                                >
                                    Sign up
                                </Link>
                            </>
                        )}
                    </nav>
                </div>
            )}
        </header>
    );
}
