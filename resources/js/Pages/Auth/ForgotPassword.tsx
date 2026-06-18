import { Head, useForm } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import InputError from '@/Components/Form/InputError';
import { FormEventHandler } from 'react';

export default function ForgotPassword({ status }: { status?: string }) {
    const { data, setData, post, processing, errors } = useForm({ email: '' });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('password.email'));
    };

    return (
        <GuestLayout>
            <Head title="Forgot Password" />

            <div className="mb-6">
                <h1 className="text-xl font-semibold text-content">Reset your password</h1>
                <p className="text-sm text-content-secondary mt-1">
                    Enter your email and we'll send you a reset link.
                </p>
            </div>

            {status && (
                <div className="mb-4 px-4 py-3 rounded-lg bg-status-success-subtle text-status-success-content text-sm">
                    {status}
                </div>
            )}

            <form onSubmit={submit} className="space-y-4">
                <div>
                    <label htmlFor="email" className="label">Email</label>
                    <input
                        id="email"
                        type="email"
                        value={data.email}
                        onChange={(e) => setData('email', e.target.value)}
                        className="input"
                        autoFocus
                        required
                    />
                    <InputError message={errors.email} className="mt-1.5" />
                </div>

                <button type="submit" disabled={processing} className="btn-primary w-full py-2.5">
                    {processing ? 'Sending…' : 'Send reset link'}
                </button>
            </form>
        </GuestLayout>
    );
}
