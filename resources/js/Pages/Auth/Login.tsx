import { Head, Link, useForm } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import InputError from '@/Components/Form/InputError';
import { FormEventHandler } from 'react';

export default function Login({
    status,
    canResetPassword,
}: {
    status?: string;
    canResetPassword: boolean;
}) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false as boolean,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('login'), { onFinish: () => reset('password') });
    };

    return (
        <GuestLayout>
            <Head title="Log in" />

            <div className="mb-6">
                <h1 className="text-xl font-semibold text-content">Welcome back</h1>
                <p className="text-sm text-content-secondary mt-1">Sign in to your Loomi account</p>
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
                        autoComplete="username"
                        autoFocus
                        required
                    />
                    <InputError message={errors.email} className="mt-1.5" />
                </div>

                <div>
                    <div className="flex-between mb-1">
                        <label htmlFor="password" className="label mb-0">Password</label>
                        {canResetPassword && (
                            <Link
                                href={route('password.request')}
                                className="text-xs text-content-link hover:underline"
                            >
                                Forgot password?
                            </Link>
                        )}
                    </div>
                    <input
                        id="password"
                        type="password"
                        value={data.password}
                        onChange={(e) => setData('password', e.target.value)}
                        className="input"
                        autoComplete="current-password"
                        required
                    />
                    <InputError message={errors.password} className="mt-1.5" />
                </div>

                <div className="flex items-center gap-2">
                    <input
                        id="remember"
                        type="checkbox"
                        checked={data.remember}
                        onChange={(e) => setData('remember', e.target.checked as false)}
                        className="rounded border-border text-brand-600 focus:ring-border-focus size-4"
                    />
                    <label htmlFor="remember" className="text-sm text-content-secondary select-none">
                        Remember me
                    </label>
                </div>

                <button type="submit" disabled={processing} className="btn-primary w-full py-2.5 mt-2">
                    {processing ? 'Signing in…' : 'Sign in'}
                </button>
            </form>

            <div className='text-center'>
                <p className="text-xs text-content-muted mt-3">
                    admin: <span className="font-mono">admin@loomi.test</span> / <span className="font-mono">admin123</span>
                </p>
                <p className="text-xs text-content-muted mt-0.5">
                    visitor: <span className="font-mono">visitor@loomi.test</span> / <span className="font-mono">visitor123</span>
                </p>
            </div>


            <p className="text-center text-sm text-content-secondary mt-4">
                Don't have an account?{' '}
                <Link href={route('register')} className="text-content-link font-medium hover:underline">
                    Sign up
                </Link>
            </p>
        </GuestLayout>
    );
}
