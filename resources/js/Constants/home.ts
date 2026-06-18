import {
    BuildingStorefrontIcon,
    ChatBubbleLeftRightIcon,
    ShieldCheckIcon,
    TruckIcon,
} from '@heroicons/react/24/outline';

export const HOME_TRUST_BADGES = [
    { title: 'Independent brands', description: 'Every store on Loomi is run by a real independent clothing brand.', icon: BuildingStorefrontIcon },
    { title: 'Ships direct', description: "Orders ship straight from the seller's store to your door.", icon: TruckIcon },
    { title: 'Secure checkout', description: 'Your account and orders are protected end to end.', icon: ShieldCheckIcon },
    { title: 'Real support', description: 'Reach out to sellers or our team whenever you need help.', icon: ChatBubbleLeftRightIcon },
] as const;

export const HOME_TESTIMONIALS = [
    { quote: "Found three brands I'd never have discovered otherwise. My closet looks completely different now.", author: 'J.D., Verified buyer' },
    { quote: 'Ordering directly from the people who design and make the pieces feels different — every package feels considered.', author: 'A.K., Verified buyer' },
    { quote: 'The sizing notes from each brand saved me two returns. I trust the fit before I even order now.', author: 'S.P., Verified buyer' },
] as const;

export const HOW_IT_WORKS = [
    { title: 'Discover', description: "Browse independent stores and find pieces you won't see everywhere else." },
    { title: 'Buy direct', description: 'Order straight from the brand that made it — no marketplace markup.' },
    { title: 'Track & receive', description: 'Follow your order and manage everything from your dashboard.' },
] as const;

export const SPOTLIGHT_CAMPAIGNS = [
    { tag: 'Limited drop', title: 'Studio Mare — Summer Linen', description: 'Hand-cut linen pieces, dyed in small batches. Live for 72 hours.', accent: 'from-amber-500 to-orange-600' },
    { tag: 'New brand', title: 'Northbound Knits', description: 'Wool and recycled cotton knitwear from a two-person studio.', accent: 'from-sky-500 to-blue-600' },
    { tag: 'Sale', title: 'Reworn Archive — up to 40% off', description: 'Past-season pieces from brands you already follow, while stock lasts.', accent: 'from-rose-500 to-pink-600' },
] as const;
