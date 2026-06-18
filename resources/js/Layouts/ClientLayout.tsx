import { PropsWithChildren } from 'react';
import { usePage } from '@inertiajs/react';
import { PageProps } from '@/Types';
import MainNavbar from '@/Components/Shared/MainNavbar';
import NavFooter from '@/Components/Shared/NavFooter';
import FlashToaster from '@/Components/UI/FlashToaster';
import NotificationPanel from '@/Components/UI/NotificationPanel';

export default function ClientLayout({ children }: PropsWithChildren) {
    const auth = usePage<PageProps>().props.auth;

    return (
        <div className="min-h-screen bg-surface-page font-sans">
            <FlashToaster />
            <MainNavbar user={auth.user} />

            <main>{children}</main>

            <NavFooter />

            <NotificationPanel />
        </div>
    );
}
