import {
    BuildingStorefrontIcon,
    ChatBubbleLeftRightIcon,
    ShieldCheckIcon,
    TruckIcon,
} from '@heroicons/react/24/outline';

export const SORT_OPTIONS = [
    { value: 'latest',     label: 'Latest' },
    { value: 'price_asc',  label: 'Price: low to high' },
    { value: 'price_desc', label: 'Price: high to low' },
] as const;

export const PRODUCT_TRUST_BADGES = [
    {
        icon: BuildingStorefrontIcon,
        title: 'Direct from brands',
        description: 'Every product is sold directly by the brand that made it.',
    },
    {
        icon: TruckIcon,
        title: 'Fast shipping',
        description: 'Stores ship within 1–3 business days on average.',
    },
    {
        icon: ShieldCheckIcon,
        title: 'Protected purchases',
        description: 'Your order is covered by Loomi Buyer Protection.',
    },
    {
        icon: ChatBubbleLeftRightIcon,
        title: 'Ask the maker',
        description: 'Questions? Message the store before you buy.',
    },
] as const;
