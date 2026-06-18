import { Category } from '@/Types';
import { STORE_SHOW_SORT } from '@/Constants/stores';

interface Props {
    categories: Category[];
    currentCategory: number | null;
    currentSort: string;
    onApply: (key: string, value: string | number | null) => void;
}

export default function FiltersBar({ categories, currentCategory, currentSort, onApply }: Props) {
    return (
        <div className="border-b border-border bg-surface">
            <div className="page-container py-3 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div className="flex items-center gap-2 flex-wrap">
                    <button
                        onClick={() => onApply('category', null)}
                        className={`px-3 py-1.5 rounded-full text-xs font-medium border transition-all ${
                            !currentCategory
                                ? 'bg-brand-700 text-white border-brand-700'
                                : 'bg-surface text-content border-border hover:border-brand-300'
                        }`}
                    >
                        All
                    </button>
                    {categories.map((cat) => (
                        <button
                            key={cat.id}
                            onClick={() => onApply('category', cat.id)}
                            className={`px-3 py-1.5 rounded-full text-xs font-medium border transition-all ${
                                currentCategory === cat.id
                                    ? 'bg-brand-700 text-white border-brand-700'
                                    : 'bg-surface text-content border-border hover:border-brand-300'
                            }`}
                        >
                            {cat.name}
                        </button>
                    ))}
                </div>
                <select
                    value={currentSort}
                    onChange={(e) => onApply('sort', e.target.value)}
                    className="input w-auto text-sm py-1.5"
                >
                    {STORE_SHOW_SORT.map((opt) => (
                        <option key={opt.value} value={opt.value}>{opt.label}</option>
                    ))}
                </select>
            </div>
        </div>
    );
}
