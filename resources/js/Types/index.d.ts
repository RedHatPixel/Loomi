export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string;
    is_seller?: boolean;
    is_admin?: boolean;
}

export interface AppNotification {
    id: string;
    type: string;
    data: {
        type: string;
        title: string;
        message: string;
        link: string | null;
    };
    read_at: string | null;
    created_at: string;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>,> = T & {
    auth: {
        user: User | null;
    };
    flash?: {
        success?: string;
        error?: string;
    };
    cart_count: number;
    active_orders: number;
    notifications: AppNotification[];
    unread_notification_count: number;
    seller_pending_orders: number;
    pending_stores: number;
    pending_orders_admin: number;
};

export interface Category {
    id: number;
    name: string;
    slug: string;
}

export interface ProductImage {
    id: number;
    path: string;
}

export interface Store {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    logo: string | null;
    background_image: string | null;
    is_active: boolean;
    user: User;
    products_count?: number;
}

export interface Product {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    price: number;
    stock: number;
    is_published: boolean;
    store_id: number;
    category_id: number | null;
    store: Store;
    category: Category | null;
    images: ProductImage[];
}

export interface CartProduct {
    id: number;
    name: string;
    slug: string;
    price: number;
    stock: number;
    store: { name: string };
    image: string | null;
}

export interface CartItem {
    id: number;
    quantity: number;
    product: CartProduct;
}

export interface Address {
    id: number;
    label: string;
    recipient_name: string;
    phone: string;
    line1: string;
    line2: string | null;
    city: string;
    province: string;
    postal_code: string;
    country: string;
    is_default: boolean;
}

export type OrderStatus = 'pending' | 'confirmed' | 'shipped' | 'delivered' | 'cancelled';

export interface OrderItem {
    id: number;
    product_name: string;
    unit_price: number;
    quantity: number;
    subtotal: number;
    status: OrderStatus;
    store_id: number;
    store_name: string;
    image: string | null;
    slug: string | null;
    product?: {
        slug: string;
        images: ProductImage[];
    };
}

export interface Order {
    id: number;
    status: OrderStatus;
    total: number;
    notes: string | null;
    payment_method: string | null;
    payment_details: string | null;
    created_at: string;
    address: Address | null;
    items: OrderItem[];
    customer?: string;
    user?: User;
}

export interface PaginatedData<T> {
    data: T[];
    meta: {
        current_page: number;
        last_page: number;
        total: number;
        per_page: number;
    };
    links?: {
        next: string | null;
        prev: string | null;
        first?: string | null;
        last?: string | null;
    };
}
