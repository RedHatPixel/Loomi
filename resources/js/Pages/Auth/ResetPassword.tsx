import { Head, useForm } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import InputError from '@/Components/Form/InputError';
import { FormEventHandler } from 'react';

export default function ResetPassword({ token, email }: { token: string; email: string }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        token,
        email,
        password: '',
        password_confirmation: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('password.store'), {
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    return (
        <GuestLayout>
            <Head title="Reset Password" />

            <div className="mb-6">
                <h1 className="text-xl font-semibold text-content">Choose a new password</h1>
                <p className="text-sm text-content-secondary mt-1">Make it something you'll remember.</p>
            </div>

            <form onSubmit={submit} className="space-y-4">
                <div>
                    <label htmlFor="email" className="label">Email</label>
                    <input
                        id="email"
                        type="email"
                        value={data.email}
                        onChange={(e) => setData('email', e.target.value)}
                        className="input"
                        autoComplete="username"
                        required
                    />
                    <InputError message={errors.email} className="mt-1.5" />
                </div>

                <div>
                    <label htmlFor="password" className="label">New password</label>
                    <input
                        id="password"
                        type="password"
                        value={data.password}
                        onChange={(e) => setData('password', e.target.value)}
                        className="input"
                        autoComplete="new-password"
                        autoFocus
                        required
                    />
                    <InputError message={errors.password} className="mt-1.5" />
                </div>

                <div>
                    <label htmlFor="password_confirmation" className="label">Confirm new password</label>
                    <input
                        id="password_confirmation"
                        type="password"
                        value={data.password_confirmation}
                        onChange={(e) => setData('password_confirmation', e.target.value)}
                        className="input"
                        autoComplete="new-password"
                        required
                    />
                    <InputError message={errors.password_confirmation} className="mt-1.5" />
                </div>

                <button type="submit" disabled={processing} className="btn-primary w-full py-2.5">
                    {processing ? 'Saving…' : 'Reset password'}
                </button>
            </form>
        </GuestLayout>
    );
}
