// resources/js/Components/Shared/Footer.tsx
import Logo from '../Logo';

export default function Footer() {
    return (
        <footer className="border-t border-border bg-surface mt-16">
            <div className="border-t border-border">
                <div className="page-container py-6 flex-between flex-col sm:flex-row gap-2">
                    <Logo />
                    <p className="text-xs text-content-muted">
                        © {new Date().getFullYear()} Loomi. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    );
}
