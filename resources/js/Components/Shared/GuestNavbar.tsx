import { useState } from 'react';
import { Link } from '@inertiajs/react';
import Logo from '../Logo';
import { Bars3Icon, XMarkIcon } from '@heroicons/react/24/outline';
import { GUEST_NAV_LINKS } from '@/Constants/navigation';

interface Props {}

export default function GuestNavbar() {
    const [mobileOpen, setMobileOpen] = useState(false);

    return (
        <>
            <header className="sticky top-0 inset-x-0 z-50 bg-surface/80 backdrop-blur-sm border-b border-border">
                <div className="page-container flex-between h-16">
                    <Logo />

                    <nav className="hidden sm:flex items-center gap-1">
                        {GUEST_NAV_LINKS.map((link) => (
                            <Link
                                key={link.label}
                                href={link.href}
                                className="px-3 py-1.5 rounded-md text-sm font-medium text-content-secondary hover:text-content hover:bg-surface-raised transition-colors"
                            >
                                {link.label}
                            </Link>
                        ))}
                        <div className="w-px h-5 bg-border mx-2" />
                        <Link href={route('login')} className="btn-ghost text-sm">
                            Log in
                        </Link>
                        <Link href={route('register')} className="btn-primary text-sm">
                            Get started
                        </Link>
                    </nav>

                    <button
                        type="button"
                        onClick={() => setMobileOpen((o) => !o)}
                        className="sm:hidden btn-ghost p-2 -mr-2"
                        aria-label="Toggle navigation menu"
                    >
                        {mobileOpen ? (
                            <XMarkIcon className="w-5 h-5" />
                        ) : (
                            <Bars3Icon className="w-5 h-5" />
                        )}
                    </button>
                </div>
            </header>

            {/* Mobile navigation */}
            {mobileOpen && (
                <div className="sm:hidden border-b border-border bg-surface">
                    <nav className="page-container py-3 space-y-1">
                        {GUEST_NAV_LINKS.map((link) => (
                            <Link
                                key={link.label}
                                href={link.href}
                                onClick={() => setMobileOpen(false)}
                                className="block px-3 py-2 rounded-md text-sm font-medium text-content-secondary hover:text-content hover:bg-surface-raised transition-colors"
                            >
                                {link.label}
                            </Link>
                        ))}
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
                            Get started
                        </Link>
                    </nav>
                </div>
            )}
        </>
    );
}
