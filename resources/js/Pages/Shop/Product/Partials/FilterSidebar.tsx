import { Category } from '@/Types';

interface Filters {
    search: string;
    category: number | null;
    sort: string;
    min_price: number | null;
    max_price: number | null;
}

interface Props {
    filters: Filters;
    categories: Category[];
    onApply: (key: string, value: string | number | null) => void;
    onClearAll: () => void;
    hasActiveFilters: boolean;
}

export default function FilterSidebar({ filters, categories, onApply, onClearAll, hasActiveFilters }: Props) {
    return (
        <aside className="w-full lg:w-56 shrink-0 space-y-6">
            <div>
                <div className="flex-between mb-3">
                    <h3 className="text-sm font-semibold text-content">Filters</h3>
                    {hasActiveFilters && (
                        <button onClick={onClearAll} className="text-xs text-content-link hover:underline">
                            Clear all
                        </button>
                    )}
                </div>

                <div className="space-y-1">
                    <p className="text-xs font-medium text-content-muted uppercase tracking-wide mb-2">Category</p>
                    <button
                        onClick={() => onApply('category', null)}
                        className={`w-full text-left px-3 py-2 rounded-lg text-sm transition-colors ${
                            !filters.category
                                ? 'bg-brand-50 text-brand-700 font-medium'
                                : 'text-content-secondary hover:bg-surface-raised'
                        }`}
                    >
                        All categories
                    </button>
                    {categories.map((cat) => (
                        <button
                            key={cat.id}
                            onClick={() => onApply('category', cat.id)}
                            className={`w-full text-left px-3 py-2 rounded-lg text-sm transition-colors ${
                                filters.category === cat.id
                                    ? 'bg-brand-50 text-brand-700 font-medium'
                                    : 'text-content-secondary hover:bg-surface-raised'
                            }`}
                        >
                            {cat.name}
                        </button>
                    ))}
                </div>
            </div>

            <div>
                <p className="text-xs font-medium text-content-muted uppercase tracking-wide mb-3">Price range</p>
                <div className="flex items-center gap-2">
                    <input
                        type="number"
                        placeholder="Min"
                        min={0}
                        value={filters.min_price ?? ''}
                        onChange={(e) => onApply('min_price', e.target.value ? Number(e.target.value) : null)}
                        className="input text-sm py-1.5 w-full"
                    />
                    <span className="text-content-muted text-sm">–</span>
                    <input
                        type="number"
                        placeholder="Max"
                        min={0}
                        value={filters.max_price ?? ''}
                        onChange={(e) => onApply('max_price', e.target.value ? Number(e.target.value) : null)}
                        className="input text-sm py-1.5 w-full"
                    />
                </div>
            </div>
        </aside>
    );
}
