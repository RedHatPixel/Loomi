import { PropsWithChildren, ReactNode, useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { PageProps } from '@/Types';
import Logo from '@/Components/Logo';
import Footer from '@/Components/Shared/Footer';
import FlashToaster from '@/Components/UI/FlashToaster';
import NotificationPanel from '@/Components/UI/NotificationPanel';
import UserDropdown from '@/Components/Shared/UserDropdown';
import ConfirmDialog from '@/Components/UI/ConfirmDialog';
import {
    ChartBarIcon,
    ShoppingBagIcon,
    CubeIcon,
    UserGroupIcon,
    BuildingStorefrontIcon,
    TagIcon,
    Bars3Icon,
    XMarkIcon,
    HomeIcon,
    ArrowRightStartOnRectangleIcon,
} from '@heroicons/react/24/outline';
import { router } from '@inertiajs/react';

interface Props extends PropsWithChildren {
    header?: ReactNode;
}

const SIDEBAR_LINKS = [
    { label: 'Dashboard',    href: route('admin.dashboard'),          icon: ChartBarIcon,         routeName: 'admin.dashboard',       badgeKey: undefined },
    { label: 'Users',        href: route('admin.users.index'),        icon: UserGroupIcon,        routeName: 'admin.users.*',         badgeKey: undefined },
    { label: 'Stores',       href: route('admin.stores.index'),       icon: BuildingStorefrontIcon, routeName: 'admin.stores.*',       badgeKey: 'pending_stores' },
    { label: 'Products',     href: route('admin.products.index'),     icon: CubeIcon,             routeName: 'admin.products.*',      badgeKey: undefined },
    { label: 'Orders',       href: route('admin.orders.index'),       icon: ShoppingBagIcon,      routeName: 'admin.orders.*',        badgeKey: 'pending_orders_admin' },
    { label: 'Categories',   href: route('admin.categories.index'),   icon: TagIcon,              routeName: 'admin.categories.*',    badgeKey: undefined },
] as const;

function isActive(routeName: string): boolean {
    if (routeName.endsWith('.*')) {
        const base = routeName.slice(0, -2);
        return route().current(base + '*');
    }
    return route().current(routeName);
}

export default function AdminLayout({ header, children }: Props) {
    const { auth, pending_stores, pending_orders_admin } = usePage<PageProps>().props;
    const [sidebarOpen, setSidebarOpen] = useState(false);

    const [confirmLogout, setConfirmLogout] = useState(false);

    const handleLogout = () => {
        setConfirmLogout(true);
    };

    const doLogout = () => {
        setConfirmLogout(false);
        router.post(route('logout'));
    };

    const sidebarBadge = (badgeKey?: string): number | null => {
        if (badgeKey === 'pending_stores') return pending_stores;
        if (badgeKey === 'pending_orders_admin') return pending_orders_admin;
        return null;
    };

    return (
        <div className="min-h-screen bg-surface-page font-sans">
            <FlashToaster />
            {/* Mobile sidebar overlay */}
            {sidebarOpen && (
                <div
                    className="fixed inset-0 z-40 bg-black/40 lg:hidden"
                    onClick={() => setSidebarOpen(false)}
                />
            )}

            {/* Sidebar */}
            <aside
                className={`fixed inset-y-0 left-0 z-50 w-64 bg-surface border-r border-border transform transition-transform duration-200 ease-in-out lg:translate-x-0 ${
                    sidebarOpen ? 'translate-x-0' : '-translate-x-full'
                }`}
            >
                <div className="flex flex-col h-full">
                    {/* Logo area */}
                    <div className="flex items-center justify-between px-5 h-14 sm:h-16 border-b border-border shrink-0">
                        <Logo />
                        <button
                            type="button"
                            onClick={() => setSidebarOpen(false)}
                            className="lg:hidden btn-ghost p-1"
                            aria-label="Close sidebar"
                        >
                            <XMarkIcon className="w-5 h-5" />
                        </button>
                    </div>

                    {/* Navigation */}
                    <nav className="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                        {SIDEBAR_LINKS.map((link) => {
                            const Icon = link.icon;
                            const active = isActive(link.routeName);
                            const badge = sidebarBadge(link.badgeKey);
                            return (
                                <Link
                                    key={link.label}
                                    href={link.href}
                                    onClick={() => setSidebarOpen(false)}
                                    className={`flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all ${
                                        active
                                            ? 'bg-brand-50 text-brand-700'
                                            : 'text-content-secondary hover:text-content hover:bg-surface-raised'
                                    }`}
                                >
                                    <Icon className={`w-5 h-5 shrink-0 ${active ? 'text-brand-600' : 'text-content-muted'}`} />
                                    <span className="flex-1">{link.label}</span>
                                    {badge !== null && badge > 0 && (
                                        <span className="inline-flex items-center justify-center h-5 min-w-[20px] rounded-full bg-red-500 px-1.5 text-[10px] font-bold text-white">
                                            {badge > 9 ? '9+' : badge}
                                        </span>
                                    )}
                                </Link>
                            );
                        })}
                    </nav>

                    {/* Bottom section */}
                    <div className="border-t border-border p-3 space-y-1">
                        <Link
                            href={route('home')}
                            className="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-content-secondary hover:text-content hover:bg-surface-raised transition-all"
                        >
                            <HomeIcon className="w-5 h-5 text-content-muted shrink-0" />
                            Back to site
                        </Link>
                        <button
                            type="button"
                            onClick={handleLogout}
                            className="flex w-full items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50 transition-all"
                        >
                            <ArrowRightStartOnRectangleIcon className="w-5 h-5 shrink-0" />
                            Log out
                        </button>
                    </div>

                    {/* User info */}
                    {auth.user && (
                        <div className="border-t border-border px-4 py-3 shrink-0">
                            <p className="text-xs text-content-muted truncate">{auth.user.name}</p>
                            <p className="text-[10px] text-content-disabled truncate">{auth.user.email}</p>
                        </div>
                    )}
                </div>
            </aside>

            {/* Main content */}
            <div className="flex-1 flex flex-col min-h-screen lg:ml-64">
                {/* Top bar */}
                <header className="sticky top-0 z-30 bg-surface/90 backdrop-blur-sm border-b border-border">
                    <div className="flex items-center justify-between h-14 sm:h-16 px-4 sm:px-6">
                        <div className="flex items-center gap-3">
                            <button
                                type="button"
                                onClick={() => setSidebarOpen(true)}
                                className="lg:hidden btn-ghost p-2 -ml-2"
                                aria-label="Open sidebar"
                            >
                                <Bars3Icon className="w-5 h-5" />
                            </button>
                            {header && (
                                <h1 className="text-lg font-semibold text-content">{header}</h1>
                            )}
                        </div>
                        <div className="flex items-center gap-2">
                            {auth.user && <UserDropdown user={auth.user} />}
                        </div>
                    </div>
                </header>

                {/* Page content */}
                <main className="flex-1">
                    {children}
                </main>

                <Footer />
            </div>

            <NotificationPanel />

            {/* Confirm logout */}
            <ConfirmDialog
                open={confirmLogout}
                onClose={() => setConfirmLogout(false)}
                onConfirm={doLogout}
                title="Log out?"
                message="Are you sure you want to log out of your account?"
                confirmText="Log out"
                variant="danger"
            />
        </div>
    );
}
