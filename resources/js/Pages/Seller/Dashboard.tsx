import { Head, Link } from '@inertiajs/react';
import { PageProps } from '@/Types';
import SellerLayout from '@/Layouts/SellerLayout';
import {
    CubeIcon,
    ShoppingBagIcon,
    CurrencyDollarIcon,
    ClockIcon,
    ArrowTrendingUpIcon,
    EyeIcon,
} from '@heroicons/react/24/outline';
import { Store, Order } from '@/Types';
import Reveal from '@/Components/Shared/Reveal';
import SalesChart from '@/Components/Seller/SalesChart';
import OrderStatusChart from '@/Components/Seller/OrderStatusChart';

interface DashboardStats {
    total_products: number;
    published: number;
    total_orders: number;
    total_revenue: number;
    pending_orders: number;
}

interface DashboardStore extends Store {
    orders_count: number;
    revenue: number;
}

interface SalesDay {
    date: string;
    total: number;
}

interface StatusItem {
    status: string;
    count: number;
}

interface Props extends PageProps {
    stores: DashboardStore[];
    stats: DashboardStats | null;
    orders: Order[];
    weeklySales: SalesDay[];
    orderStatuses: StatusItem[];
}

export default function SellerDashboard({ stores, stats, orders, weeklySales, orderStatuses }: Props) {
    const hasStores = stores.length > 0;

    return (
        <>
            <Head title="Seller Dashboard" />
            <SellerLayout header="Dashboard">
                <div className="page-container py-6 sm:py-8 space-y-8">
                    {!hasStores ? (
                        <div className="card text-center py-16">
                            <CubeIcon className="w-12 h-12 text-content-muted mx-auto mb-4" />
                            <h2 className="text-xl font-semibold text-content mb-2">No stores yet</h2>
                            <p className="text-content-secondary text-sm mb-6 max-w-md mx-auto">
                                Create your first store to start selling on Loomi. It takes just a few minutes.
                            </p>
                            <Link href={route('seller.create')} className="btn-primary px-5 py-2.5">
                                Create your store
                            </Link>
                        </div>
                    ) : (
                        <>
                            {/* Stats grid */}
                            <Reveal>
                            <div className="grid grid-cols-2 lg:grid-cols-5 gap-4">
                                <StatCard
                                    icon={CubeIcon}
                                    label="Total products"
                                    value={stats?.total_products ?? 0}
                                    color="text-blue-600 bg-blue-50"
                                />
                                <StatCard
                                    icon={EyeIcon}
                                    label="Published"
                                    value={stats?.published ?? 0}
                                    color="text-green-600 bg-green-50"
                                />
                                <StatCard
                                    icon={ShoppingBagIcon}
                                    label="Total orders"
                                    value={stats?.total_orders ?? 0}
                                    color="text-purple-600 bg-purple-50"
                                />
                                <StatCard
                                    icon={CurrencyDollarIcon}
                                    label="Revenue"
                                    value={`PHP ${(stats?.total_revenue ?? 0).toLocaleString()}`}
                                    color="text-amber-600 bg-amber-50"
                                />
                                <StatCard
                                    icon={ClockIcon}
                                    label="Pending"
                                    value={stats?.pending_orders ?? 0}
                                    color="text-orange-600 bg-orange-50"
                                />
                            </div>
                            </Reveal>

                            {/* Analytics charts */}
                            <Reveal>
                            <div className="grid sm:grid-cols-2 gap-4">
                                <SalesChart data={weeklySales} />
                                <OrderStatusChart data={orderStatuses} />
                            </div>
                            </Reveal>

                            {/* Stores overview */}
                            <Reveal>
                            <section>
                                <div className="flex items-center justify-between mb-4">
                                    <h2 className="text-lg font-semibold text-content">Your stores</h2>
                                    <Link href={route('seller.create')} className="text-sm text-content-link hover:underline">
                                        + New store
                                    </Link>
                                </div>
                                <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    {stores.map((store) => (
                                        <Link
                                            key={store.id}
                                            href={route('seller.settings')}
                                            className="card hover:border-border-strong transition-colors block overflow-hidden !p-0"
                                        >
                                            <div
                                                className="h-16 bg-cover bg-center relative"
                                                style={store.background_image ? { backgroundImage: `url(${store.background_image})` } : undefined}
                                            >
                                                {!store.background_image && (
                                                    <div className="absolute inset-0 bg-gradient-to-r from-brand-100 to-brand-50" />
                                                )}
                                                {store.background_image && (
                                                    <div className="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent" />
                                                )}
                                            </div>
                                            <div className="px-4 pb-3 -mt-5 relative z-10">
                                                <div className="size-10 rounded-full bg-white shadow-sm flex-center ring-2 ring-white overflow-hidden mb-1.5">
                                                    {store.logo ? (
                                                        <img src={store.logo} alt={store.name} className="size-full object-cover" />
                                                    ) : (
                                                        <span className="text-sm font-bold text-brand-700">
                                                            {store.name.charAt(0).toUpperCase()}
                                                        </span>
                                                    )}
                                                </div>
                                                <div className="flex items-start justify-between">
                                                    <div>
                                                        <h3 className="text-sm font-semibold text-content">{store.name}</h3>
                                                        <p className="text-xs text-content-muted">loomi.com/stores/{store.slug}</p>
                                                    </div>
                                                    <span className={`badge shrink-0 ${store.is_active ? 'badge-success' : 'badge-warning'}`}>
                                                        {store.is_active ? 'Active' : 'Inactive'}
                                                    </span>
                                                </div>
                                                <div className="flex items-center gap-3 mt-2 pt-2 border-t border-border text-xs text-content-secondary">
                                                    <span className="flex items-center gap-1">
                                                        <CubeIcon className="w-3.5 h-3.5" />
                                                        {store.products_count} products
                                                    </span>
                                                    <span className="flex items-center gap-1">
                                                        <ShoppingBagIcon className="w-3.5 h-3.5" />
                                                        {store.orders_count} orders
                                                    </span>
                                                    <span className="flex items-center gap-1">
                                                        <CurrencyDollarIcon className="w-3.5 h-3.5" />
                                                        PHP {store.revenue.toLocaleString()}
                                                    </span>
                                                </div>
                                            </div>
                                        </Link>
                                    ))}
                                </div>
                            </section>
                            </Reveal>

                            {/* Recent orders */}
                            <Reveal>
                            <section>
                                <div className="flex items-center justify-between mb-4">
                                    <h2 className="text-lg font-semibold text-content">Recent orders</h2>
                                    <Link href={route('seller.orders.index')} className="text-sm text-content-link hover:underline">
                                        View all
                                    </Link>
                                </div>
                                {orders.length === 0 ? (
                                    <div className="card text-center py-8">
                                        <ShoppingBagIcon className="w-8 h-8 text-content-muted mx-auto mb-2" />
                                        <p className="text-sm text-content-secondary">No orders yet</p>
                                    </div>
                                ) : (
                                    <div className="card !p-0 overflow-hidden">
                                        <div className="divide-y divide-border">
                                            {orders.map((order) => (
                                                <div key={order.id} className="flex items-center justify-between px-4 sm:px-6 py-3 text-sm">
                                                    <div className="flex items-center gap-3">
                                                        <span className="text-content-muted">#{order.id}</span>
                                                        <StatusBadge status={order.status} />
                                                    </div>
                                                    <div className="flex items-center gap-4 text-content-secondary">
                                                        <span>{order.customer}</span>
                                                        <span className="font-medium text-content">
                                                            PHP {(order.total ?? 0).toLocaleString()}
                                                        </span>
                                                        <span className="text-content-muted hidden sm:inline">{order.created_at}</span>
                                                    </div>
                                                </div>
                                            ))}
                                        </div>
                                    </div>
                                )}
                            </section>
                            </Reveal>
                        </>
                    )}
                </div>
            </SellerLayout>
        </>
    );
}

function StatCard({ icon: Icon, label, value, color }: {
    icon: React.ComponentType<{ className?: string }>;
    label: string;
    value: string | number;
    color: string;
}) {
    return (
        <div className="card flex items-start gap-3">
            <span className={`flex-center size-10 rounded-lg shrink-0 ${color}`}>
                <Icon className="w-5 h-5" />
            </span>
            <div className="min-w-0">
                <p className="text-xs text-content-muted">{label}</p>
                <p className="text-lg font-semibold text-content mt-0.5">{value}</p>
            </div>
        </div>
    );
}

function StatusBadge({ status }: { status: string }) {
    const variants: Record<string, string> = {
        pending: 'badge-warning',
        confirmed: 'badge-info',
        shipped: 'badge-info',
        delivered: 'badge-success',
        cancelled: 'badge-danger',
    };
    return (
        <span className={variants[status] ?? 'badge'}>
            {status.charAt(0).toUpperCase() + status.slice(1)}
        </span>
    );
}
