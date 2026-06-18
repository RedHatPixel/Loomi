import { useState, useRef, useCallback } from 'react';
import { PhotoIcon, XMarkIcon, ArrowUpTrayIcon } from '@heroicons/react/24/outline';

interface Props {
    /** Current image URL/path (for existing images) */
    value: string | null;
    /** Called when an image is uploaded or removed */
    onChange: (path: string | null) => void;
    /** Directory to store uploads in: 'stores' or 'products' */
    directory: 'stores' | 'products';
    /** Optional label */
    label?: string;
    /** Optional description */
    description?: string;
    /** Shape variant */
    shape?: 'circle' | 'rectangle';
    /** Size class for the preview area */
    previewSize?: string;
    /** Aspect ratio for rectangle shape */
    aspectRatio?: string;
}

export default function ImageUpload({
    value,
    onChange,
    directory,
    label,
    description,
    shape = 'rectangle',
    previewSize = 'w-full h-28',
    aspectRatio,
}: Props) {
    const [uploading, setUploading] = useState(false);
    const [error, setError] = useState<string | null>(null);
    const inputRef = useRef<HTMLInputElement>(null);

    const handleFileSelect = useCallback(async (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0];
        if (!file) return;

        // Validate file type
        if (!file.type.startsWith('image/')) {
            setError('Please select an image file.');
            return;
        }

        // Validate file size (5MB)
        if (file.size > 5 * 1024 * 1024) {
            setError('Image must be under 5MB.');
            return;
        }

        setError(null);
        setUploading(true);

        try {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('directory', directory);

            const response = await fetch(route('api.uploads.image'), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '',
                },
                body: formData,
            });

            if (!response.ok) {
                throw new Error('Upload failed');
            }

            const data = await response.json();
            onChange(data.url);
        } catch (err) {
            setError('Failed to upload image. Please try again.');
        } finally {
            setUploading(false);
            // Reset file input so the same file can be re-selected
            if (inputRef.current) {
                inputRef.current.value = '';
            }
        }
    }, [directory, onChange]);

    const handleRemove = useCallback(() => {
        onChange(null);
    }, [onChange]);

    return (
        <div>
            {label && <label className="label">{label}</label>}

            <div className="flex flex-col gap-3">
                {/* Preview */}
                <div
                    className={`${previewSize} rounded-lg bg-gradient-to-br from-brand-50 to-brand-100/50 border-2 border-dashed border-border flex-center shrink-0 overflow-hidden relative ${
                        shape === 'circle' ? 'rounded-full' : 'rounded-lg'
                    } ${aspectRatio ? aspectRatio : ''}`}
                    style={value ? { backgroundImage: `url(${value})`, backgroundSize: 'cover', backgroundPosition: 'center' } : undefined}
                >
                    {!value && !uploading && (
                        <PhotoIcon className="w-6 h-6 text-content-muted" />
                    )}
                    {uploading && (
                        <div className="flex items-center gap-1.5 text-xs text-content-muted">
                            <svg className="animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none">
                                <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                                <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                            </svg>
                            Uploading...
                        </div>
                    )}
                    {value && !uploading && (
                        <button
                            type="button"
                            onClick={handleRemove}
                            className="absolute top-1 right-1 size-6 rounded-full bg-white/80 hover:bg-white shadow-sm flex-center text-content-muted hover:text-red-500 transition-colors"
                            aria-label="Remove image"
                        >
                            <XMarkIcon className="w-3.5 h-3.5" />
                        </button>
                    )}
                </div>

                {/* Upload button + description stacked below */}
                <div className="flex flex-wrap items-center gap-2">
                    <button
                        type="button"
                        onClick={() => inputRef.current?.click()}
                        disabled={uploading}
                        className="btn-secondary text-sm inline-flex items-center gap-1.5 shrink-0"
                    >
                        <ArrowUpTrayIcon className="w-4 h-4" />
                        {value ? 'Change image' : 'Upload image'}
                    </button>
                    <input
                        ref={inputRef}
                        type="file"
                        accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                        onChange={handleFileSelect}
                        className="hidden"
                    />
                    {error && <p className="text-xs text-status-danger w-full">{error}</p>}
                    {description && !error && (
                        <p className="text-xs text-content-muted">{description}</p>
                    )}
                </div>
            </div>
        </div>
    );
}
