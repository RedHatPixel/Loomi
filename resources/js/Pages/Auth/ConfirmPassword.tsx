import { Head, useForm } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import InputError from '@/Components/Form/InputError';
import { FormEventHandler } from 'react';

export default function ConfirmPassword() {
    const { data, setData, post, processing, errors, reset } = useForm({ password: '' });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('password.confirm'), { onFinish: () => reset('password') });
    };

    return (
        <GuestLayout>
            <Head title="Confirm Password" />

            <div className="mb-6">
                <h1 className="text-xl font-semibold text-content">Confirm your password</h1>
                <p className="text-sm text-content-secondary mt-1">
                    This is a secure area. Please confirm your password to continue.
                </p>
            </div>

            <form onSubmit={submit} className="space-y-4">
                <div>
                    <label htmlFor="password" className="label">Password</label>
                    <input
                        id="password"
                        type="password"
                        value={data.password}
                        onChange={(e) => setData('password', e.target.value)}
                        className="input"
                        autoFocus
                        required
                    />
                    <InputError message={errors.password} className="mt-1.5" />
                </div>

                <button type="submit" disabled={processing} className="btn-primary w-full py-2.5">
                    {processing ? 'Confirming…' : 'Confirm password'}
                </button>
            </form>
        </GuestLayout>
    );
}
