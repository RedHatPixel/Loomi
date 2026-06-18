import {
    ChartBarIcon,
    ShoppingBagIcon,
    CubeIcon,
    UserGroupIcon,
    BuildingStorefrontIcon,
    TagIcon,
} from '@heroicons/react/24/outline';

export const ADMIN_SIDEBAR_LINKS = [
    { label: 'Dashboard',    href: route('admin.dashboard'),          icon: ChartBarIcon,            routeName: 'admin.dashboard',       badgeKey: undefined },
    { label: 'Users',        href: route('admin.users.index'),        icon: UserGroupIcon,           routeName: 'admin.users.*',         badgeKey: undefined },
    { label: 'Stores',       href: route('admin.stores.index'),       icon: BuildingStorefrontIcon,   routeName: 'admin.stores.*',       badgeKey: 'pending_stores' },
    { label: 'Products',     href: route('admin.products.index'),     icon: CubeIcon,                routeName: 'admin.products.*',      badgeKey: undefined },
    { label: 'Orders',       href: route('admin.orders.index'),       icon: ShoppingBagIcon,         routeName: 'admin.orders.*',        badgeKey: 'pending_orders_admin' },
    { label: 'Categories',   href: route('admin.categories.index'),   icon: TagIcon,                 routeName: 'admin.categories.*',    badgeKey: undefined },
] as const;

export const ADMIN_DROPDOWN_ITEM = {
    label: 'Admin Panel',
    href: route('admin.dashboard'),
    icon: ChartBarIcon,
    badgeKey: 'pending_stores',
} as const;

export const ROLE_COLORS: Record<string, string> = {
    admin: 'bg-purple-100 text-purple-800',
    seller: 'bg-brand-100 text-brand-800',
    customer: 'bg-gray-100 text-gray-800',
};

export const STATUS_COLORS: Record<string, string> = {
    pending:   'bg-amber-100 text-amber-800',
    confirmed: 'bg-blue-100 text-blue-800',
    shipped:   'bg-sky-100 text-sky-800',
    delivered: 'bg-green-100 text-green-800',
    cancelled: 'bg-red-100 text-red-800',
};

export const ADMIN_STATS_ICONS = {
    users:   UserGroupIcon,
    stores:  BuildingStorefrontIcon,
    products: CubeIcon,
    orders:  ShoppingBagIcon,
};
