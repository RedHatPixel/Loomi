import { ChartBarIcon, ShoppingCartIcon, ShoppingBagIcon, UserIcon } from '@heroicons/react/24/outline';

/* ── MainNavbar (authenticated) ── */
export const AUTH_NAV_LINKS = [
    { label: 'Products', href: route('products.index'), routeName: 'products.index' },
    { label: 'Stores', href: route('stores.index'), routeName: 'stores.index' },
] as const;

/* ── AuthNavbar ── */
export const AUTH_NAVBAR_LINKS = [
    { label: 'Home', href: route('home'), routeName: 'home' },
    { label: 'Products', href: route('products.index'), routeName: 'products.index' },
    { label: 'Stores', href: route('stores.index'), routeName: 'stores.index' },
] as const;

/* ── GuestNavbar ── */
export const GUEST_NAV_LINKS = [
    { label: 'Products', href: route('products.index') },
    { label: 'Stores', href: route('stores.index') },
] as const;

/* ── UserDropdown ── */
export const USER_DROPDOWN_ITEMS = [
    { label: 'Profile', href: route('profile.edit'), icon: UserIcon, badgeKey: undefined },
    { label: 'My Orders', href: route('orders.index'), icon: ShoppingBagIcon, badgeKey: 'active_orders' },
    { label: 'Cart', href: route('cart'), icon: ShoppingCartIcon, badgeKey: 'cart_count' },
] as const;

export const SELLER_DROPDOWN_ITEM = {
    label: 'Seller Dashboard',
    href: route('seller.dashboard'),
    icon: ChartBarIcon,
    badgeKey: 'seller_pending_orders',
} as const;

/* ── Admin ── */
export const ADMIN_DROPDOWN_ITEM = {
    label: 'Admin Panel',
    href: route('admin.dashboard'),
    icon: ChartBarIcon,
    badgeKey: 'pending_stores',
} as const;

/* ── NavFooter ── */
export type FooterLink = { label: string; href: string };

export function getFooterLinks(isLoggedIn: boolean) {
    return {
        shop: [
            { label: 'All products', href: route('products.index') },
            { label: 'Browse stores', href: route('stores.index') },
            { label: 'My Orders', href: route('orders.index') },
            { label: 'View Cart', href: route('cart') },
            { label: 'Become a seller', href: isLoggedIn ? route('seller.create') : route('seller.landing') },
        ],
        support: [
            { label: 'Help center', href: '#' },
            { label: 'Shipping & returns', href: '#' },
            { label: 'Contact us', href: '#' },
        ],
        company: [
            { label: 'About Loomi', href: '#' },
            { label: 'Terms of service', href: '#' },
            { label: 'Privacy policy', href: '#' },
        ],
    };
}
