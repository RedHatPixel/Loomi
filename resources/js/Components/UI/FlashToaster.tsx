import { useEffect } from 'react';
import { usePage } from '@inertiajs/react';
import { PageProps } from '@/Types';
import { toast } from '@/Components/UI/Toast';

export default function FlashToaster() {
    const { flash } = usePage<PageProps>().props;

    useEffect(() => {
        if (flash?.success) {
            toast(flash.success, 'success');
        }
        if (flash?.error) {
            toast(flash.error, 'error');
        }
    }, [flash]);

    return null;
}
