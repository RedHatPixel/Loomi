import {
    BanknotesIcon,
    BuildingStorefrontIcon,
    ChartBarIcon,
    CircleStackIcon,
    CurrencyDollarIcon,
    GlobeAltIcon,
    PencilSquareIcon,
    PhotoIcon,
    RectangleGroupIcon,
    ShieldCheckIcon,
    ShoppingBagIcon,
    SparklesIcon,
    TagIcon,
    TruckIcon,
    UserGroupIcon,
} from '@heroicons/react/24/outline';

/* ── Landing page ── */

export const SELLER_STATS = [
    { value: '2,400+', label: 'Active stores' },
    { value: '18k+',   label: 'Products listed' },
    { value: '94k+',   label: 'Orders fulfilled' },
    { value: '100%',   label: 'Independent brands' },
] as const;

export const SELLER_STEPS = [
    {
        step: '01',
        title: 'Create an account',
        desc: 'Sign up for free. Every account comes with a customer profile built in.',
    },
    {
        step: '02',
        title: 'Open your storefront',
        desc: 'Name your store, add your brand details, and you\'re ready to list products.',
    },
    {
        step: '03',
        title: 'Start selling',
        desc: 'Publish products, manage orders, and grow your brand — all from your seller dashboard.',
    },
] as const;

export const SELLER_FEATURES = [
    {
        icon: RectangleGroupIcon,
        title: 'Multi-store support',
        desc: 'Run multiple brands under one account. Each store has its own products, orders, and identity.',
    },
    {
        icon: TruckIcon,
        title: 'Order management',
        desc: 'Track every order from placement to fulfillment with a clean, focused dashboard.',
    },
    {
        icon: ShoppingBagIcon,
        title: 'Customer storefront',
        desc: 'Customers can browse, search, and shop across all brands on the platform.',
    },
    {
        icon: ShieldCheckIcon,
        title: 'Role-based access',
        desc: 'Customer, seller, and admin roles keep every part of the platform properly scoped.',
    },
    {
        icon: CircleStackIcon,
        title: 'Product image uploads',
        desc: 'Add multiple images per product. Looks great on any device.',
    },
    {
        icon: GlobeAltIcon,
        title: 'Category browsing',
        desc: 'Products are organized by global categories so customers find what they\'re looking for.',
    },
] as const;

export const SELLER_BENEFITS = [
    {
        icon: CurrencyDollarIcon,
        title: 'Zero upfront cost',
        desc: 'Open your store for free. No listing fees, no monthly charges. You only pay when you sell.',
    },
    {
        icon: ChartBarIcon,
        title: 'Real-time analytics',
        desc: 'See exactly how your products are performing — views, orders, revenue, and trends.',
    },
    {
        icon: BanknotesIcon,
        title: 'Transparent pricing',
        desc: 'Know exactly what you\'ll earn on every sale. Our fees are clear and competitive.',
    },
    {
        icon: UserGroupIcon,
        title: 'Built-in audience',
        desc: 'Your store is discoverable alongside hundreds of other brands shoppers already trust.',
    },
] as const;

export const SELLER_TESTIMONIALS = [
    {
        quote: 'Setting up our store took under an hour. We listed our first collection and had an order the same day.',
        author: 'Marella Santos',
        role: 'Founder, Studio Mare',
    },
    {
        quote: 'The dashboard gives me a clear picture of what\'s selling and what\'s not. I\'ve never had this much control over my inventory.',
        author: 'Jasper Cruz',
        role: 'Owner, Iron Loom',
    },
    {
        quote: 'Finally — a platform built for clothing brands specifically. Every feature actually makes sense for what we do.',
        author: 'Tanya Reyes',
        role: 'Designer, Northbound Knits',
    },
] as const;

/* ── Create page ── */

export const CREATE_STEPS = [
    { key: 'brand',    icon: BuildingStorefrontIcon, title: 'Brand name',        subtitle: 'What should we call your store?' },
    { key: 'story',    icon: PencilSquareIcon,       title: 'Your story',        subtitle: 'Tell customers about your brand' },
    { key: 'look',     icon: PhotoIcon,               title: 'Brand identity',    subtitle: 'Add a logo and set the vibe' },
    { key: 'details',  icon: TagIcon,                 title: 'Store details',     subtitle: 'Category, contact & social links' },
    { key: 'review',   icon: SparklesIcon,            title: 'Review & launch',   subtitle: 'One last look before you go live' },
] as const;

export const STORE_CATEGORIES = [
    "Men's streetwear", "Women's apparel", "Unisex basics",
    "Denim & workwear", "Knitwear", "Outerwear",
    "Activewear", "Swimwear", "Footwear",
    "Accessories", "Vintage & archive", "Other",
] as const;

export const EXPERIENCE_LEVELS = [
    { value: 'new',       label: 'Just starting out' },
    { value: 'growing',   label: 'Been selling a while' },
    { value: 'established', label: 'Established brand' },
] as const;

export interface FormData {
    name: string;
    slug: string;
    description: string;
    story: string;
    categories: string[];
    experience: string;
    logo: string | null;
    website: string;
    instagram: string;
    tiktok: string;
    agree_terms: boolean;
}

export const EMPTY_FORM: FormData = {
    name: '',
    slug: '',
    description: '',
    story: '',
    categories: [],
    experience: '',
    logo: null,
    website: '',
    instagram: '',
    tiktok: '',
    agree_terms: false,
};
