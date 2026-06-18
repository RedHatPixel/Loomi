import { HTMLAttributes } from 'react';

export default function InputError({
    message,
    className = '',
    ...props
}: HTMLAttributes<HTMLParagraphElement> & { message?: string }) {
    return message ? (
        <p
            {...props}
            className={'text-sm text-red-500 mt-1.5 ' + className}
        >
            {message}
        </p>
    ) : null;
}
