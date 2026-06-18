import { Link, router, usePage } from '@inertiajs/react';
import { useEffect, useRef, useState } from 'react';
import { User, PageProps } from '@/Types';
import {
    ArrowRightStartOnRectangleIcon,
    ChevronDownIcon,
} from '@heroicons/react/24/outline';
import ConfirmDialog from '@/Components/UI/ConfirmDialog';
import { USER_DROPDOWN_ITEMS, SELLER_DROPDOWN_ITEM, ADMIN_DROPDOWN_ITEM } from '@/Constants/navigation';

interface Props {
    user: User;
}

export default function UserDropdown({ user }: Props) {
    const [open, setOpen] = useState(false);
    const rootRef = useRef<HTMLDivElement>(null);

    const { cart_count, active_orders, unread_notification_count, seller_pending_orders, pending_stores } = usePage<PageProps>().props;

    useEffect(() => {
        const handleClickOutside = (e: MouseEvent) => {
            if (rootRef.current && !rootRef.current.contains(e.target as Node)) {
                setOpen(false);
            }
        };
        const handleEscape = (e: KeyboardEvent) => {
            if (e.key === 'Escape') setOpen(false);
        };
        document.addEventListener('mousedown', handleClickOutside);
        document.addEventListener('keydown', handleEscape);
        return () => {
            document.removeEventListener('mousedown', handleClickOutside);
            document.removeEventListener('keydown', handleEscape);
        };
    }, []);

    const initials =
        user.name
            ?.split(' ')
            .filter(Boolean)
            .slice(0, 2)
            .map((part) => part[0]?.toUpperCase())
            .join('') || 'U';

    const badgeValue = (badgeKey?: string): number | null => {
        if (badgeKey === 'cart_count') return cart_count;
        if (badgeKey === 'active_orders') return active_orders;
        if (badgeKey === 'seller_pending_orders') return seller_pending_orders;
        if (badgeKey === 'pending_stores') return pending_stores;
        return null;
    };

    const totalBadge = cart_count + active_orders + unread_notification_count;

    const [confirmLogout, setConfirmLogout] = useState(false);

    const handleLogout = () => {
        setOpen(false);
        setConfirmLogout(true);
    };

    const doLogout = () => {
        setConfirmLogout(false);
        router.post(route('logout'));
    };

    return (
        <div className="relative" ref={rootRef}>
            <button
                type="button"
                onClick={() => setOpen((o) => !o)}
                aria-haspopup="menu"
                aria-expanded={open}
                aria-label="Account menu"
                className="group relative flex items-center gap-1.5 rounded-full p-1 transition-colors hover:bg-content/5"
            >
                <span className="flex h-9 w-9 items-center justify-center rounded-full bg-brand-600 text-xs font-semibold text-white ring-2 ring-brand-600/0 transition-all group-hover:ring-brand-600/20">
                    {initials}
                </span>
                {totalBadge > 0 && (
                    <span className="absolute -top-0.5 -right-0.5 flex h-4 min-w-[16px] items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white pointer-events-none">
                        {totalBadge > 9 ? '9+' : totalBadge}
                    </span>
                )}
                <ChevronDownIcon
                    className={`h-3.5 w-3.5 text-content-muted transition-transform duration-200 ${
                        open ? 'rotate-180' : ''
                    }`}
                />
            </button>

            <div
                role="menu"
                className={`absolute right-0 z-50 mt-2 w-64 origin-top-right rounded-xl border border-border bg-surface shadow-lg transition-all duration-150 ${
                    open ? 'translate-y-0 scale-100 opacity-100' : 'pointer-events-none -translate-y-1 scale-95 opacity-0'
                }`}
            >
                <div className="border-b border-border px-4 py-3">
                    <p className="truncate text-sm font-medium text-content">{user.name}</p>
                    <p className="truncate text-xs text-content-muted">{user.email}</p>
                </div>

                <div className="py-1">
                    {/* Admin panel (if applicable) */}
                    {user.is_admin && (() => {
                        const adminBadge = badgeValue(ADMIN_DROPDOWN_ITEM.badgeKey);
                        return (
                            <Link
                                href={ADMIN_DROPDOWN_ITEM.href}
                                onClick={() => setOpen(false)}
                                role="menuitem"
                                className="flex items-center gap-2.5 px-4 py-2 text-sm text-content transition-colors hover:bg-purple-50"
                            >
                                <ADMIN_DROPDOWN_ITEM.icon className="h-4 w-4 text-purple-600" />
                                <span className="flex-1">{ADMIN_DROPDOWN_ITEM.label}</span>
                                {adminBadge !== null && adminBadge > 0 && (
                                    <span className="inline-flex items-center justify-center h-5 min-w-[20px] rounded-full bg-red-500 px-1.5 text-[10px] font-bold text-white">
                                        {adminBadge > 9 ? '9+' : adminBadge}
                                    </span>
                                )}
                            </Link>
                        );
                    })()}

                    {/* Seller dashboard (if applicable) */}
                    {user.is_seller && (() => {
                        const sellerBadge = badgeValue(SELLER_DROPDOWN_ITEM.badgeKey);
                        return (
                            <Link
                                href={SELLER_DROPDOWN_ITEM.href}
                                onClick={() => setOpen(false)}
                                role="menuitem"
                                className="flex items-center gap-2.5 px-4 py-2 text-sm text-content transition-colors hover:bg-brand-50"
                            >
                                <SELLER_DROPDOWN_ITEM.icon className="h-4 w-4 text-brand-600" />
                                <span className="flex-1">{SELLER_DROPDOWN_ITEM.label}</span>
                                {sellerBadge !== null && sellerBadge > 0 && (
                                    <span className="inline-flex items-center justify-center h-5 min-w-[20px] rounded-full bg-amber-500 px-1.5 text-[10px] font-bold text-white">
                                        {sellerBadge > 9 ? '9+' : sellerBadge}
                                    </span>
                                )}
                            </Link>
                        );
                    })()}

                    {/* Dropdown items with badges */}
                    {USER_DROPDOWN_ITEMS.map((item) => {
                        const badge = badgeValue(item.badgeKey);
                        return (
                            <Link
                                key={item.label}
                                href={item.href}
                                onClick={() => setOpen(false)}
                                role="menuitem"
                                className="flex items-center gap-2.5 px-4 py-2 text-sm text-content transition-colors hover:bg-content/5"
                            >
                                <item.icon className="h-4 w-4 text-content-muted shrink-0" />
                                <span className="flex-1">{item.label}</span>
                                {badge !== null && badge > 0 && (
                                    <span className="inline-flex items-center justify-center h-5 min-w-[20px] rounded-full bg-brand-600 px-1.5 text-[10px] font-bold text-white">
                                        {badge > 9 ? '9+' : badge}
                                    </span>
                                )}
                            </Link>
                        );
                    })}
                </div>

                <div className="border-t border-border py-1">
                    <button
                        type="button"
                        onClick={handleLogout}
                        role="menuitem"
                        className="flex w-full items-center gap-2.5 px-4 py-2 text-sm text-red-600 transition-colors hover:bg-red-50"
                    >
                        <ArrowRightStartOnRectangleIcon className="h-4 w-4" />
                        Log out
                    </button>
                </div>
            </div>

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
