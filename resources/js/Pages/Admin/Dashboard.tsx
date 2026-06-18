import { Head } from '@inertiajs/react';
import { PageProps } from '@/Types';
import AdminLayout from '@/Layouts/AdminLayout';
import {
    UserGroupIcon,
    BuildingStorefrontIcon,
    CubeIcon,
    ShoppingBagIcon,
    CurrencyDollarIcon,
    ClockIcon,
    ExclamationCircleIcon,
} from '@heroicons/react/24/outline';
import StatCard from '@/Components/Admin/StatCard';
import SalesChart from '@/Components/Seller/SalesChart';
import OrderStatusChart from '@/Components/Seller/OrderStatusChart';
import Reveal from '@/Components/Shared/Reveal';

interface AdminStats {
    total_users: number;
    total_stores: number;
    total_products: number;
    total_orders: number;
    total_revenue: number;
    pending_orders: number;
    pending_stores: number;
}

interface SalesDay {
    date: string;
    total: number;
}

interface StatusItem {
    status: string;
    count: number;
}

interface UserRow {
    id: number;
    name: string;
    email: string;
    created_at: string;
}

interface OrderRow {
    id: number;
    status: string;
    total: number;
    customer: string;
    created_at: string;
}

interface Props extends PageProps {
    stats: AdminStats;
    recentUsers: UserRow[];
    recentOrders: OrderRow[];
    weeklySales: SalesDay[];
    orderStatuses: StatusItem[];
}

const statusColors: Record<string, string> = {
    pending: 'bg-amber-100 text-amber-800',
    confirmed: 'bg-blue-100 text-blue-800',
    shipped: 'bg-sky-100 text-sky-800',
    delivered: 'bg-green-100 text-green-800',
    cancelled: 'bg-red-100 text-red-800',
};

export default function AdminDashboard({ stats, recentUsers, recentOrders, weeklySales, orderStatuses }: Props) {
    return (
        <>
            <Head title="Admin Dashboard" />
            <AdminLayout header="Dashboard">
                <div className="page-container py-6 sm:py-8 space-y-8">

                    {/* Stats grid */}
                    <Reveal>
                        <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
                            <StatCard icon={UserGroupIcon} label="Total Users" value={stats.total_users} color="text-purple-600 bg-purple-50" />
                            <StatCard icon={BuildingStorefrontIcon} label="Total Stores" value={stats.total_stores} color="text-blue-600 bg-blue-50" />
                            <StatCard icon={CubeIcon} label="Total Products" value={stats.total_products} color="text-green-600 bg-green-50" />
                            <StatCard icon={ShoppingBagIcon} label="Total Orders" value={stats.total_orders} color="text-amber-600 bg-amber-50" />
                        </div>
                    </Reveal>

                    {/* Secondary stats */}
                    <Reveal delay={100}>
                        <div className="grid grid-cols-2 lg:grid-cols-3 gap-4">
                            <StatCard icon={CurrencyDollarIcon} label="Total Revenue" value={`PHP ${stats.total_revenue.toLocaleString()}`} color="text-emerald-600 bg-emerald-50" />
                            <StatCard icon={ClockIcon} label="Pending Orders" value={stats.pending_orders} subtext="Needs attention" color="text-orange-600 bg-orange-50" />
                            <StatCard icon={ExclamationCircleIcon} label="Pending Stores" value={stats.pending_stores} subtext="Awaiting activation" color="text-red-600 bg-red-50" />
                        </div>
                    </Reveal>

                    {/* Charts */}
                    <Reveal delay={150}>
                        <div className="grid sm:grid-cols-2 gap-4">
                            <SalesChart data={weeklySales} />
                            <OrderStatusChart data={orderStatuses} />
                        </div>
                    </Reveal>

                    {/* Recent sections */}
                    <div className="grid lg:grid-cols-2 gap-6">
                        {/* Recent users */}
                        <Reveal delay={200}>
                            <section>
                                <h2 className="text-base font-semibold text-content mb-3">Recent Users</h2>
                                <div className="card !p-0 overflow-hidden">
                                    {recentUsers.length === 0 ? (
                                        <div className="p-6 text-center text-sm text-content-muted">No users yet.</div>
                                    ) : (
                                        <div className="divide-y divide-border">
                                            {recentUsers.map((user) => (
                                                <div key={user.id} className="flex items-center gap-3 px-4 sm:px-5 py-3">
                                                    <div className="size-8 rounded-full bg-brand-100 flex-center text-xs font-bold text-brand-700 shrink-0">
                                                        {user.name.charAt(0).toUpperCase()}
                                                    </div>
                                                    <div className="flex-1 min-w-0">
                                                        <p className="text-sm font-medium text-content truncate">{user.name}</p>
                                                        <p className="text-xs text-content-muted truncate">{user.email}</p>
                                                    </div>
                                                    <span className="text-[10px] text-content-muted shrink-0">{user.created_at}</span>
                                                </div>
                                            ))}
                                        </div>
                                    )}
                                </div>
                            </section>
                        </Reveal>

                        {/* Recent orders */}
                        <Reveal delay={250}>
                            <section>
                                <h2 className="text-base font-semibold text-content mb-3">Recent Orders</h2>
                                <div className="card !p-0 overflow-hidden">
                                    {recentOrders.length === 0 ? (
                                        <div className="p-6 text-center text-sm text-content-muted">No orders yet.</div>
                                    ) : (
                                        <div className="divide-y divide-border">
                                            {recentOrders.map((order) => (
                                                <div key={order.id} className="flex items-center justify-between px-4 sm:px-5 py-3">
                                                    <div className="flex items-center gap-3">
                                                        <span className="text-sm font-medium text-content">#{order.id}</span>
                                                        <span className={`px-2 py-0.5 rounded-full text-[10px] font-medium capitalize ${statusColors[order.status] ?? 'bg-gray-100 text-gray-700'}`}>
                                                            {order.status}
                                                        </span>
                                                    </div>
                                                    <div className="flex items-center gap-4 text-sm">
                                                        <span className="text-content-muted hidden sm:inline">{order.customer}</span>
                                                        <span className="font-semibold text-content">PHP {order.total.toLocaleString()}</span>
                                                        <span className="text-[10px] text-content-muted hidden sm:inline">{order.created_at}</span>
                                                    </div>
                                                </div>
                                            ))}
                                        </div>
                                    )}
                                </div>
                            </section>
                        </Reveal>
                    </div>
                </div>
            </AdminLayout>
        </>
    );
}
