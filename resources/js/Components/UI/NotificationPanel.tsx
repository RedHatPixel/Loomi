import { Link, router, usePage } from '@inertiajs/react';
import { useEffect, useRef, useState } from 'react';
import { PageProps, AppNotification } from '@/Types';
import { BellIcon, XMarkIcon, CheckCircleIcon, ShoppingBagIcon, TruckIcon, ExclamationCircleIcon, SparklesIcon, EyeIcon } from '@heroicons/react/24/outline';

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

function NotificationItem({ notification, onMarkRead }: { notification: AppNotification; onMarkRead: (id: string) => void }) {
    const data = notification.data;
    const Icon = typeIcons[data.type] || BellIcon;
    const isUnread = !notification.read_at;

    const content = (
        <div
            className={`flex items-start gap-3 px-4 py-3 transition-colors cursor-pointer ${
                isUnread ? 'bg-brand-50/50 hover:bg-brand-50' : 'hover:bg-gray-50'
            }`}
            onClick={() => onMarkRead(notification.id)}
        >
            <div className={`shrink-0 mt-0.5 size-8 rounded-full flex items-center justify-center ${
                isUnread ? 'bg-brand-100 text-brand-600' : 'bg-gray-100 text-gray-400'
            }`}>
                <Icon className="w-4 h-4" />
            </div>
            <div className="flex-1 min-w-0">
                <div className="flex items-start justify-between gap-2">
                    <p className={`text-sm leading-snug ${isUnread ? 'font-semibold text-gray-900' : 'font-medium text-gray-700'}`}>
                        {data.title}
                    </p>
                    <span className="text-[10px] text-gray-400 shrink-0 mt-0.5">{timeAgo(notification.created_at)}</span>
                </div>
                <p className="text-xs text-gray-500 mt-0.5 line-clamp-2">{data.message}</p>
                {isUnread && <span className="inline-block mt-1.5 size-1.5 rounded-full bg-brand-600" />}
            </div>
        </div>
    );

    if (data.link) {
        return (
            <Link href={data.link} onClick={() => onMarkRead(notification.id)}>
                {content}
            </Link>
        );
    }
    return content;
}

export default function NotificationPanel() {
    const [open, setOpen] = useState(false);
    const rootRef = useRef<HTMLDivElement>(null);
    const { notifications, unread_notification_count } = usePage<PageProps>().props;
    const [localUnread, setLocalUnread] = useState(unread_notification_count);
    const [localNotifications, setLocalNotifications] = useState(notifications);
    const [newPulse, setNewPulse] = useState(false);

    // Sync from server props
    useEffect(() => {
        setLocalUnread(unread_notification_count);
        setLocalNotifications(notifications);
        if (unread_notification_count > localUnread) {
            setNewPulse(true);
            setTimeout(() => setNewPulse(false), 2000);
        }
    }, [notifications, unread_notification_count]);

    // Close on click outside / escape
    useEffect(() => {
        const handler = (e: MouseEvent) => {
            if (rootRef.current && !rootRef.current.contains(e.target as Node)) {
                setOpen(false);
            }
        };
        const escapeHandler = (e: KeyboardEvent) => {
            if (e.key === 'Escape') setOpen(false);
        };
        if (open) {
            document.addEventListener('mousedown', handler);
            document.addEventListener('keydown', escapeHandler);
        }
        return () => {
            document.removeEventListener('mousedown', handler);
            document.removeEventListener('keydown', escapeHandler);
        };
    }, [open]);

    const markAsRead = (id: string) => {
        router.patch(route('api.notifications.read', id), {}, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                setLocalNotifications((prev) =>
                    prev.map((n) => (n.id === id ? { ...n, read_at: new Date().toISOString() } : n))
                );
                setLocalUnread((prev) => Math.max(0, prev - 1));
            },
        });
    };

    const markAllRead = () => {
        router.patch(route('api.notifications.readAll'), {}, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                setLocalNotifications((prev) =>
                    prev.map((n) => (n.read_at ? n : { ...n, read_at: new Date().toISOString() }))
                );
                setLocalUnread(0);
            },
        });
    };

    return (
        <div ref={rootRef} className="fixed bottom-5 right-5 z-[60]">
            {/* Bell button */}
            <button
                type="button"
                onClick={() => setOpen((o) => !o)}
                aria-label="Notifications"
                className={`relative size-12 rounded-full shadow-lg flex items-center justify-center transition-all duration-300 ${
                    open
                        ? 'bg-brand-700 text-white scale-110'
                        : 'bg-white text-gray-700 hover:bg-gray-50 hover:shadow-xl border border-gray-200'
                } ${newPulse ? 'animate-pulse ring-4 ring-brand-300' : ''}`}
            >
                <BellIcon className="w-5 h-5" />
                {localUnread > 0 && (
                    <span className="absolute -top-1 -right-1 flex h-5 min-w-[20px] items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white ring-2 ring-white">
                        {localUnread > 9 ? '9+' : localUnread}
                    </span>
                )}
            </button>

            {/* Panel */}
            {open && (
                <div className="absolute bottom-16 right-0 w-[360px] sm:w-[400px] max-w-[calc(100vw-2.5rem)] rounded-xl bg-white shadow-2xl border border-gray-200 overflow-hidden transition-all duration-200">
                    {/* Header */}
                    <div className="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                        <div>
                            <h3 className="text-sm font-semibold text-gray-900">Notifications</h3>
                            {localNotifications.length > 0 && (
                                <p className="text-[11px] text-gray-400 mt-0.5">
                                    {localUnread > 0 ? `${localUnread} unread` : 'All caught up'}
                                </p>
                            )}
                        </div>
                        <div className="flex items-center gap-1">
                            {localUnread > 0 && (
                                <button
                                    type="button"
                                    onClick={markAllRead}
                                    className="text-xs font-medium text-brand-600 hover:text-brand-700 px-2 py-1 rounded-md hover:bg-brand-50 transition-colors"
                                >
                                    Mark all read
                                </button>
                            )}
                            <Link
                                href={route('notifications.index')}
                                className="text-xs font-medium text-gray-500 hover:text-gray-700 px-2 py-1 rounded-md hover:bg-gray-100 transition-colors"
                                onClick={() => setOpen(false)}
                            >
                                <EyeIcon className="w-3.5 h-3.5 inline mr-0.5" />
                                View all
                            </Link>
                            <button
                                type="button"
                                onClick={() => setOpen(false)}
                                className="p-1 rounded-md hover:bg-gray-100 transition-colors text-gray-400"
                            >
                                <XMarkIcon className="w-4 h-4" />
                            </button>
                        </div>
                    </div>

                    {/* List */}
                    <div className="max-h-[50vh] overflow-y-auto divide-y divide-gray-50">
                        {localNotifications.length === 0 ? (
                            <div className="flex flex-col items-center py-12 text-center px-6">
                                <div className="size-12 rounded-full bg-gray-50 flex items-center justify-center mb-3">
                                    <BellIcon className="w-6 h-6 text-gray-300" />
                                </div>
                                <p className="text-sm font-medium text-gray-700">No notifications yet</p>
                                <p className="text-xs text-gray-400 mt-1">
                                    We'll let you know when something happens with your orders.
                                </p>
                            </div>
                        ) : (
                            localNotifications.map((n) => (
                                <NotificationItem key={n.id} notification={n} onMarkRead={markAsRead} />
                            ))
                        )}
                    </div>
                </div>
            )}
        </div>
    );
}
