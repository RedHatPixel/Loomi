// resources/js/Components/Shared/NavFooter.tsx
import { FormEvent, useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { PageProps } from '@/Types';
import Logo from '../Logo';
import { FooterLink, getFooterLinks } from '@/Constants/navigation';

export default function NavFooter() {
    const { auth } = usePage<PageProps>().props;
    const [email, setEmail] = useState('');
    const [subscribed, setSubscribed] = useState(false);

    const isLoggedIn = !!auth.user;
    const footerLinks = getFooterLinks(isLoggedIn);

    const handleSubscribe = (e: FormEvent) => {
        e.preventDefault();
        if (!email) return;
        setSubscribed(true);
        setEmail('');
    };

    return (
        <div className="bg-surface">
            <div className="page-container py-12 grid grid-cols-2 gap-8 md:grid-cols-5">

                <div className="col-span-2 md:col-span-2">
                    <Logo />
                    <p className="text-sm text-content-muted mt-3 max-w-xs">
                        A marketplace for independent clothing brands to open a storefront
                        and sell directly to customers — no middleman, just makers.
                    </p>

                    <form onSubmit={handleSubscribe} className="mt-5 max-w-xs">
                        <label htmlFor="footer-email" className="label">
                            Get drop alerts
                        </label>
                        {subscribed ? (
                            <p className="text-sm text-status-success">You're on the list — thanks!</p>
                        ) : (
                            <div className="flex gap-2">
                                <input
                                    id="footer-email"
                                    type="email"
                                    value={email}
                                    onChange={(e) => setEmail(e.target.value)}
                                    placeholder="you@example.com"
                                    className="input"
                                />
                                <button type="submit" className="btn-primary shrink-0">
                                    Join
                                </button>
                            </div>
                        )}
                    </form>

                    <p className="text-xs text-content-muted mt-5">
                        Follow:{' '}
                        <a href="#" className="hover:text-content">Instagram</a>{' · '}
                        <a href="#" className="hover:text-content">TikTok</a>{' · '}
                        <a href="#" className="hover:text-content">X</a>
                    </p>
                </div>

                <FooterColumn title="Shop" links={footerLinks.shop} />
                <FooterColumn title="Support" links={footerLinks.support} />
                <FooterColumn title="Company" links={footerLinks.company} />
            </div>
        </div>
    );
}

function FooterColumn({ title, links }: { title: string; links: FooterLink[] }) {
    return (
        <nav>
            <h3 className="text-sm font-semibold text-content mb-3">{title}</h3>
            <ul className="space-y-2">
                {links.map((link) => (
                    <li key={link.label}>
                        <Link href={link.href} className="text-sm text-content-muted hover:text-content transition-colors">
                            {link.label}
                        </Link>
                    </li>
                ))}
            </ul>
        </nav>
    );
}
