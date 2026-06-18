import { useState } from 'react';
import Reveal from '@/Components/Shared/Reveal';

const PLACEHOLDER_IMAGE = 'https://placehold.co/600x750/e7e2d8/3a3a3a?text=No+Image';

interface Props {
    images: string[];
    productName: string;
}

export default function ProductGallery({ images, productName }: Props) {
    const allImages = images.length > 0 ? images : [PLACEHOLDER_IMAGE];
    const [activeImage, setActiveImage] = useState(0);

    return (
        <Reveal>
            <div className="w-full lg:w-[440px] shrink-0">
            <div className="aspect-[4/5] rounded-2xl overflow-hidden bg-surface-raised">
                <img
                    src={allImages[activeImage]}
                    alt={productName}
                    className="w-full h-full object-cover"
                />
            </div>

            {allImages.length > 1 && (
                <div className="flex gap-2 mt-3">
                    {allImages.map((src, i) => (
                        <button
                            key={i}
                            type="button"
                            onClick={() => setActiveImage(i)}
                            className={`w-16 h-20 rounded-lg overflow-hidden border-2 transition-colors ${
                                i === activeImage ? 'border-brand-600' : 'border-transparent'
                            }`}
                        >
                            <img src={src} alt="" className="w-full h-full object-cover" />
                        </button>
                    ))}
                </div>
            )}
        </div>
        </Reveal>
    );
}
