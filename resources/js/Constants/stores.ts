import {
    ArrowPathIcon,
    BuildingStorefrontIcon,
    ChartBarIcon,
    ChatBubbleLeftRightIcon,
    CurrencyDollarIcon,
    ShieldCheckIcon,
    TruckIcon,
} from '@heroicons/react/24/outline';

export const STORE_INDEX_SORT = [
    { value: 'name',    label: 'Name A–Z' },
    { value: 'newest',  label: 'Newest first' },
    { value: 'popular', label: 'Most products' },
] as const;

export const STORE_SHOW_SORT = [
    { value: 'latest',     label: 'Newest' },
    { value: 'price_asc',  label: 'Price: Low to High' },
    { value: 'price_desc', label: 'Price: High to Low' },
] as const;

export const SELLER_TRUST_BADGES = [
    {
        icon: BuildingStorefrontIcon,
        title: 'Free to open',
        description: 'Create your storefront at no cost. List unlimited products and start selling right away.',
    },
    {
        icon: ChartBarIcon,
        title: 'Real-time insights',
        description: 'Track views, orders, and revenue from a clean dashboard built for independent sellers.',
    },
    {
        icon: CurrencyDollarIcon,
        title: 'Fair fees',
        description: 'Keep more of what you earn. Our fees are transparent and among the lowest in the industry.',
    },
    {
        icon: ShieldCheckIcon,
        title: 'Seller protection',
        description: 'Every transaction is secured. We handle fraud prevention so you can focus on making.',
    },
] as const;

export const STORE_TRUST_BADGES = [
    {
        icon: TruckIcon,
        title: 'Ships from the brand',
        description: 'Every order ships directly from this store to your door.',
    },
    {
        icon: ShieldCheckIcon,
        title: 'Secure checkout',
        description: 'Your payment info is encrypted and protected end to end.',
    },
    {
        icon: ArrowPathIcon,
        title: 'Easy returns',
        description: 'Not happy? Reach out to the store within 14 days.',
    },
    {
        icon: ChatBubbleLeftRightIcon,
        title: 'Direct support',
        description: 'Message the store owner directly with any questions.',
    },
] as const;
