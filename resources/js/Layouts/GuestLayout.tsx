import { PropsWithChildren } from 'react';
import GuestNavbar from '@/Components/Shared/GuestNavbar';
import Footer from '@/Components/Shared/Footer';
import FlashToaster from '@/Components/UI/FlashToaster';
import { Link } from '@inertiajs/react';

export default function GuestLayout({ children }: PropsWithChildren) {
    return (
        <div className="min-h-screen bg-surface-page font-sans">
            <FlashToaster />
            <GuestNavbar />
            <main className="flex-center min-h-screen px-4">
                <div className="w-full max-w-md">
                    <div className="text-center mb-8">
                        <Link href={route('home')} className="inline-block text-2xl font-semibold text-content tracking-tight">
                            Loomi
                        </Link>
                    </div>
                    <div className="card p-8">
                        {children}
                    </div>
                </div>
            </main>
            <Footer />
        </div>
    );
}
