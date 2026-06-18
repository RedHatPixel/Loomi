import { Link, router, usePage } from '@inertiajs/react';
import { PageProps, AppNotification } from '@/Types';
import { BellIcon, CheckCircleIcon, ShoppingBagIcon, TruckIcon, ExclamationCircleIcon, SparklesIcon, ArrowLeftIcon, CheckIcon } from '@heroicons/react/24/outline';
import ClientLayout from '@/Layouts/ClientLayout';
import { useState } from 'react';

const typeIcons: Record<string, typeof BellIcon> = {
    welcome:         SparklesIcon,
    order_placed:    ShoppingBagIcon,
    order_confirmed: CheckCircleIcon,
    order_shipped:   TruckIcon,
    order_delivered: CheckCircleIcon,
    order_cancelled: ExclamationCircleIcon,
    store_created:   SparklesIcon,
    store_approved:  CheckCircleIcon,
    product_created: SparklesIcon,
    new_order:       ShoppingBagIcon,
};

function timeAgo(dateStr: string): string {
    const now = Date.now();
    const then = new Date(dateStr).getTime();
    const diff = now - then;
    const mins = Math.floor(diff / 60000);
    if (mins < 1) return 'just now';
    if (mins < 60) return `${mins}m ago`;
    const hours = Math.floor(mins / 60);
    if (hours < 24) return `${hours}h ago`;
    const days = Math.floor(hours / 24);
    if (days < 7) return `${days}d ago`;
    return new Date(dateStr).toLocaleDateString('en-PH', { month: 'short', day: 'numeric' });
}

interface NotificationsPageProps extends PageProps {
    all_notifications: AppNotification[];
    unread_count: number;
    total_count: number;
}

export default function NotificationsIndex() {
    const { all_notifications, unread_count, total_count } = usePage<NotificationsPageProps>().props;
    const [filter, setFilter] = useState<'all' | 'unread'>('all');

    const filtered = filter === 'unread'
        ? all_notifications.filter((n) => !n.read_at)
        : all_notifications;

    const markAsRead = (id: string) => {
        router.patch(route('api.notifications.read', id), {}, {
            preserveScroll: true,
            preserveState: true,
        });
    };

    const markAllRead = () => {
        if (unread_count === 0) return;
        router.patch(route('api.notifications.readAll'), {}, {
            preserveScroll: true,
            preserveState: true,
        });
    };

    return (
        <ClientLayout>
            <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                {/* Header */}
                <div className="mb-6">
                    <Link
                        href={route('home')}
                        className="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 mb-4 transition-colors"
                    >
                        <ArrowLeftIcon className="w-4 h-4" />
                        Back
                    </Link>
                    <div className="flex items-center justify-between">
                        <div>
                            <h1 className="text-xl font-bold text-gray-900">Notifications</h1>
                            <p className="text-sm text-gray-500 mt-1">
                                {total_count} total{unread_count > 0 ? ` · ${unread_count} unread` : ''}
                            </p>
                        </div>
                        {unread_count > 0 && (
                            <button
                                type="button"
                                onClick={markAllRead}
                                className="inline-flex items-center gap-1.5 text-sm font-medium text-brand-600 hover:text-brand-700 px-3 py-1.5 rounded-lg hover:bg-brand-50 transition-colors"
                            >
                                <CheckIcon className="w-4 h-4" />
                                Mark all read
                            </button>
                        )}
                    </div>
                </div>

                {/* Filters */}
                <div className="flex items-center gap-2 mb-6">
                    <button
                        type="button"
                        onClick={() => setFilter('all')}
                        className={`px-3 py-1.5 text-sm rounded-lg font-medium transition-colors ${
                            filter === 'all'
                                ? 'bg-brand-600 text-white'
                                : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                        }`}
                    >
                        All ({total_count})
                    </button>
                    <button
                        type="button"
                        onClick={() => setFilter('unread')}
                        className={`px-3 py-1.5 text-sm rounded-lg font-medium transition-colors ${
                            filter === 'unread'
                                ? 'bg-brand-600 text-white'
                                : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                        }`}
                    >
                        Unread ({unread_count})
                    </button>
                </div>

                {/* List */}
                {filtered.length === 0 ? (
                    <div className="text-center py-16">
                        <div className="size-16 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-4">
                            <BellIcon className="w-8 h-8 text-gray-300" />
                        </div>
                        <p className="text-sm font-medium text-gray-700">
                            {filter === 'unread' ? 'No unread notifications' : 'No notifications yet'}
                        </p>
                        <p className="text-xs text-gray-400 mt-1">
                            {filter === 'unread'
                                ? 'You are all caught up!'
                                : "We'll let you know when something happens."}
                        </p>
                    </div>
                ) : (
                    <div className="space-y-2">
                        {filtered.map((notification) => {
                            const data = notification.data;
                            const Icon = typeIcons[data.type] || BellIcon;
                            const isUnread = !notification.read_at;

                            return (
                                <div
                                    key={notification.id}
                                    className={`group flex items-start gap-4 p-4 rounded-xl border transition-all ${
                                        isUnread
                                            ? 'bg-brand-50/30 border-brand-100'
                                            : 'bg-white border-gray-100 hover:border-gray-200'
                                    }`}
                                >
                                    <div className={`shrink-0 size-10 rounded-full flex items-center justify-center ${
                                        isUnread ? 'bg-brand-100 text-brand-600' : 'bg-gray-50 text-gray-400'
                                    }`}>
                                        <Icon className="w-5 h-5" />
                                    </div>
                                    <div className="flex-1 min-w-0">
                                        <div className="flex items-start justify-between gap-3">
                                            <div>
                                                <p className={`text-sm ${isUnread ? 'font-semibold text-gray-900' : 'font-medium text-gray-700'}`}>
                                                    {data.title}
                                                </p>
                                                <p className="text-sm text-gray-500 mt-0.5">{data.message}</p>
                                            </div>
                                            <span className="text-[11px] text-gray-400 shrink-0 whitespace-nowrap">
                                                {timeAgo(notification.created_at)}
                                            </span>
                                        </div>
                                        <div className="flex items-center gap-3 mt-3">
                                            {data.link && (
                                                <Link
                                                    href={data.link}
                                                    className="text-xs font-medium text-brand-600 hover:text-brand-700 transition-colors"
                                                    onClick={() => markAsRead(notification.id)}
                                                >
                                                    View details →
                                                </Link>
                                            )}
                                            {isUnread && (
                                                <button
                                                    type="button"
                                                    onClick={() => markAsRead(notification.id)}
                                                    className="text-xs font-medium text-gray-400 hover:text-gray-600 transition-colors"
                                                >
                                                    Mark as read
                                                </button>
                                            )}
                                        </div>
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                )}
            </div>
        </ClientLayout>
    );
}
