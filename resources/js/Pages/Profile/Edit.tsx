import ClientLayout from '@/Layouts/ClientLayout';
import { PageProps } from '@/Types';
import { Head } from '@inertiajs/react';
import DeleteUserForm from './Partials/DeleteUserForm';
import UpdatePasswordForm from './Partials/UpdatePasswordForm';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm';
import Reveal from '@/Components/Shared/Reveal';

export default function Edit({
    mustVerifyEmail,
    status,
}: PageProps<{ mustVerifyEmail: boolean; status?: string }>) {
    return (
        <>
            <Head title="Profile" />
            <ClientLayout>
                <div className="page-container py-6 lg:py-10">
                    <Reveal>
                        <h1 className="text-2xl font-bold text-content mb-6">Profile</h1>
                    </Reveal>
                    <div className="max-w-2xl space-y-6">
                        <Reveal delay={100}>
                            <div className="card p-6">
                                <UpdateProfileInformationForm
                                    mustVerifyEmail={mustVerifyEmail}
                                    status={status}
                                    className="max-w-xl"
                                />
                            </div>
                        </Reveal>

                        <Reveal delay={150}>
                            <div className="card p-6">
                                <UpdatePasswordForm className="max-w-xl" />
                            </div>
                        </Reveal>

                        <Reveal delay={200}>
                            <div className="card p-6">
                                <DeleteUserForm className="max-w-xl" />
                            </div>
                        </Reveal>
                    </div>
                </div>
            </ClientLayout>
        </>
    );
}
