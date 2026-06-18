import { Head, Link, useForm } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import InputError from '@/Components/Form/InputError';
import { FormEventHandler } from 'react';

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('register'), {
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    return (
        <GuestLayout>
            <Head title="Register" />

            <div className="mb-6">
                <h1 className="text-xl font-semibold text-content">Create an account</h1>
                <p className="text-sm text-content-secondary mt-1">Join Loomi and start shopping or selling</p>
            </div>

            <form onSubmit={submit} className="space-y-4">
                <div>
                    <label htmlFor="name" className="label">Full name</label>
                    <input
                        id="name"
                        type="text"
                        value={data.name}
                        onChange={(e) => setData('name', e.target.value)}
                        className="input"
                        autoComplete="name"
                        autoFocus
                        required
                    />
                    <InputError message={errors.name} className="mt-1.5" />
                </div>

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
                    <label htmlFor="password" className="label">Password</label>
                    <input
                        id="password"
                        type="password"
                        value={data.password}
                        onChange={(e) => setData('password', e.target.value)}
                        className="input"
                        autoComplete="new-password"
                        required
                    />
                    <InputError message={errors.password} className="mt-1.5" />
                </div>

                <div>
                    <label htmlFor="password_confirmation" className="label">Confirm password</label>
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

                <button type="submit" disabled={processing} className="btn-primary w-full py-2.5 mt-2">
                    {processing ? 'Creating account…' : 'Create account'}
                </button>
            </form>

            <p className="text-center text-xs text-content-muted mt-1">
                I advice you to use the demo account as data is stored in database.
            </p>

            <p className="text-center text-sm text-content-secondary mt-6">
                Already have an account?{' '}
                <Link href={route('login')} className="text-content-link font-medium hover:underline">
                    Sign in
                </Link>
            </p>
        </GuestLayout>
    );
}
