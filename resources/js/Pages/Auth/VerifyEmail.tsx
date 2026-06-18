import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import GuestLayout from '@/Layouts/GuestLayout';
import ConfirmDialog from '@/Components/UI/ConfirmDialog';

export default function VerifyEmail({ status }: { status?: string }) {
    const [sending, setSending] = useState(false);
    const [confirmLogout, setConfirmLogout] = useState(false);

    const resend = () => {
        setSending(true);
        router.post(route('verification.send'), {}, {
            onFinish: () => setSending(false),
        });
    };

    const doLogout = () => {
        setConfirmLogout(false);
        router.post(route('logout'));
    };

    return (
        <GuestLayout>
            <Head title="Email Verification" />

            <div className="mb-6">
                <h1 className="text-xl font-semibold text-content">Verify your email</h1>
                <p className="text-sm text-content-secondary mt-1">
                    We sent a verification link to your email address. Click it to activate your account.
                </p>
            </div>

            {status === 'verification-link-sent' && (
                <div className="mb-4 px-4 py-3 rounded-lg bg-status-success-subtle text-status-success-content text-sm">
                    A new verification link has been sent to your email address.
                </div>
            )}

            <div className="space-y-3">
                <button onClick={resend} disabled={sending} className="btn-primary w-full py-2.5">
                    {sending ? 'Sending…' : 'Resend verification email'}
                </button>

                <button
                    onClick={() => setConfirmLogout(true)}
                    className="btn-ghost w-full py-2.5 text-sm"
                >
                    Log out
                </button>
            </div>

            {/* Confirm logout */}
            <ConfirmDialog
                open={confirmLogout}
                onClose={() => setConfirmLogout(false)}
                onConfirm={doLogout}
                title="Log out?"
                message="Are you sure you want to log out of your account?"
                confirmText="Log out"
                variant="danger"
            />
        </GuestLayout>
    );
}
