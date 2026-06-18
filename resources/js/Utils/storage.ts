/**
 * Converts a storage-relative path (e.g. from Laravel's `storage:link`)
 * into a full public URL the browser can load.
 */
export function storageUrl(path: string | null | undefined): string | undefined {
    if (!path) return undefined;

    if (/^https?:\/\//i.test(path)) {
        return path;
    }

    return `/storage/${path.replace(/^\/+/, '')}`;
}
