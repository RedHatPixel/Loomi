# AGENTS.md — Loomi

You are an expert Laravel and React engineer helping me build **Loomi**.

Write clean, simple, maintainable code. Prioritize clarity over unnecessary abstraction.
Think like a senior full-stack developer.

---

## Project Overview

We are building **Loomi**, an e-commerce platform for clothing brands across all market sectors.

Loomi allows independent clothing brands to open one or more storefronts and sell directly to customers. Every registered user is a customer by default. A user may create one or more stores, becoming a seller for each. Admins operate independently with full platform access and sell under the official **Loomi Sellers** brand.

The platform includes:

- Customer-facing storefront (browse, search, cart, checkout, orders)
- Seller dashboard (store creation and management, product listings, order fulfillment)
- Admin panel (full data access, platform-wide controls, Loomi Sellers storefront)
- Multi-store support per user account
- Role-based access: Customer, Seller, Admin

Keep the implementation simple and readable.

---

## Tech Stack

- **Laravel 13** — Full-stack backend (routes, controllers, Inertia responses)
- **Inertia.js v2** — Glue between Laravel and React (no separate REST API)
- **React 18 + TypeScript** — Frontend, scaffolded via Laravel Breeze
- **TailwindCSS v3** — Styling
- **MySQL** — Primary database
- **Vite** — Asset bundling (via `laravel-vite-plugin`)
- **Ziggy** (`tightenco/ziggy`) — Named Laravel routes in JS (`route()` helper)
- **Headless UI** — Accessible UI primitives (already installed)

Do not introduce new major libraries unless there is a strong reason.
Ask before installing anything new.

---

## Development Philosophy

Build feature by feature.

For every feature:

1. Read this file first.
2. Keep the implementation simple.
3. Avoid overengineering.
4. Prefer readable code over clever code.
5. Build the smallest useful version first.
6. Refactor only when repetition appears.

---

## Decision Making

If something is unclear or could be improved, suggest a better approach.
If a new library would significantly help, recommend it, explain why, and ask before adding it.
Do not install new libraries without approval.

---

## Architecture

### Backend — Laravel 13 with Inertia

app/
    Console/
    Exceptions/
    Http/
        Controllers/
        Admin/
        Auth/          ← Breeze-generated, extend as needed
        Customer/
        Seller/
    Middleware/
    Requests/
    Models/
    Policies/
    Services/
database/
    migrations/
    seeders/
    factories/
routes/
    web.php            ← All routes go here (Inertia uses web routes, not api.php)
    auth.php           ← Breeze auth routes
storage/
tests/
resources/
    js/                ← All frontend source lives here (Breeze default)
        Components/
            Admin/
            Seller/
            Customer/
            Shared/
        Hooks/
        Layouts/
        Pages/
            Admin/
            Seller/
            Customer/
            Auth/          ← Breeze-generated auth pages
        Store/
        Types/
        Utils/
        Constants/
        app.ts
        bootstrap.ts
    css/
        app.css

**Pages/** — Inertia page components. One file per route. They receive typed props from the controller, compose components, and submit forms via `useForm`. No large UI blocks or business logic inline.

**Components/** — Reusable UI. Create a component when it is reused, when it makes a page easier to read, or when it represents a clear UI concept. Do not create components too early.

- **One concern per component.** A component should do one thing well. If a component renders a list of items, extract the item into its own component. If a component handles both display and complex logic, split them.
- **Prefer existing components.** Before writing markup for a button, card, input, badge, or modal, check `Components/UI/` and `Components/Shared/` first. Reuse what's already there.
- **Compose, don't duplicate.** Use layout components (`ClientLayout`, `AuthenticatedLayout`) and partials to avoid repeating the same page shell in every page.
- **Keep pages thin.** Inertia page components should compose partials and shared components. Business logic and large UI blocks belong in partials or components, not in the page file.

**Layouts/** — Page shell wrappers. Use Breeze's `AuthenticatedLayout` and `GuestLayout` as the base; extend them for seller/admin shells.

**Hooks/** — Custom hooks for shared stateful logic (e.g., `useActiveStore`).

- Extract logic into a hook when it is reused across components or pages, or when it makes a component significantly easier to read.
- Hooks live in `resources/js/Hooks/` and follow the `useXxx` naming convention.
- Do not create a hook for logic that is only used in one place unless it improves readability.
- Look in `Hooks/` before writing inline `useEffect` or `useState` patterns that might already be abstracted (e.g., `useInView` for scroll-triggered animations).

**Store/** — Zustand for global client state that cannot live in Inertia shared props (e.g., cart, active store context).

**Types/** — TypeScript interfaces and enums. Co-locate with domain when not shared; put shared types in `Types/`.

**Constants/** — Hardcoded data that does not come from the server. Examples: sort option arrays, navigation links, footer link groups, static badge lists, image URL maps.

- Constants live in `resources/js/Constants/` and are imported where needed — never re-declared inline.
- Use `as const` or typed arrays so consumers get full type safety.
- Group by domain (e.g., `products.ts`, `navigation.ts`, `footer.ts`).
- Before hardcoding a value in a component or partial, check `Constants/` first — if it doesn't exist there, consider adding it.

**Utils/** — Pure helper functions used across the frontend (e.g., `storageUrl` for asset paths).

- Utils are stateless, testable functions. They import nothing from React or Inertia.
- A util belongs in `Utils/` when it is used in more than one place. If it is only used in one component, keep it co-located (or inline).
- Look in `Utils/` before writing inline string/number/date formatting logic.

---

## How Inertia Works — Key Rules

Inertia replaces the REST API + Axios fetch pattern. Follow these rules strictly:

1. **Data flows server → client via props.** Controllers call `Inertia::render('PageName', ['key' => $value])`. The page component receives these as typed TypeScript props. Never fetch data client-side with `axios.get` for initial page data.

2. **Forms use `useForm` from `@inertiajs/react`.** Do not use `axios.post` for form submissions. Use Inertia's `useForm` hook — it handles CSRF, loading state, and validation errors automatically.

```tsx
   import { useForm } from '@inertiajs/react';

   const { data, setData, post, errors, processing } = useForm({
     name: '',
     email: '',
   });

   const submit = (e: React.FormEvent) => {
     e.preventDefault();
     post(route('stores.create'));
   };
```

3. **Navigation uses `<Link>` or `router` from `@inertiajs/react`.** Never use `<a href>` for internal navigation — it causes full page reloads.

```tsx
   import { Link, router } from '@inertiajs/react';

   <Link href={route('dashboard')}>Dashboard</Link>

   // Programmatic navigation:
   router.visit(route('stores.index'));
```

4. **Routes use the `route()` Ziggy helper.** Never hardcode URL strings. Always use `route('route.name')` in both PHP and TypeScript.

5. **Shared data** (auth user, flash messages) is passed via `HandleInertiaRequests` middleware in `app/Http/Middleware/HandleInertiaRequests.php`. Add to `share()` for data every page needs.

6. **Validation errors** come back automatically via Inertia when the controller returns `back()->withErrors(...)` or when using `$request->validate()`. Access them via `errors.fieldName` in `useForm`.

7. **Axios is still available** (`bootstrap.ts` sets it up with CSRF) for non-navigation async calls (e.g., cart updates, search-as-you-type). But prefer Inertia for anything that changes a page or submits a form.

---

## User Roles

### Customer (default)
Every registered account is a customer. Customers can browse the marketplace, add items to cart, place orders, and manage their own orders and profile.

### Seller
A customer becomes a seller when they create a store. A single user account can own multiple stores. Seller capabilities are scoped to their own stores — they cannot access another seller's data. Seller routes and UI are separate from customer-facing pages.

### Admin
Admins are created outside the normal registration flow (seeded or CLI). They have full read and write access to all platform data. Admins also operate the **Loomi Sellers** store — the platform's own brand — from within the admin panel.

---

## Database Conventions

- Use snake_case for all table and column names.
- Every table has `id`, `created_at`, `updated_at`.
- Use soft deletes (`deleted_at`) for: users, stores, products, orders.
- Foreign keys must be named `{related_table_singular}_id` (e.g., `store_id`, `user_id`).
- Avoid polymorphic relations unless strictly necessary — prefer explicit foreign keys.

### Core Tables (reference)

| Table | Description |
|---|---|
| `users` | All accounts (customers, sellers, admins) |
| `roles` | `customer`, `seller`, `admin` |
| `user_roles` | Pivot — a user can have multiple roles |
| `stores` | Owned by a user; sellers can have many |
| `products` | Belong to a store |
| `categories` | Global product categories |
| `product_images` | Multiple images per product |
| `orders` | Placed by a customer |
| `order_items` | Line items within an order |
| `carts` | One per user session or account |
| `cart_items` | Products in a cart |
| `addresses` | Shipping/billing, belongs to user |

---

## Routing Conventions

All routes are in `routes/web.php` (and `routes/auth.php` for Breeze auth).

- Use named routes. Always. Example: `Route::get('/stores', ...)->name('stores.index')`.
- Group routes by role using middleware:

```php
// Public
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Authenticated (any role)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Customer
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/orders', [Customer\OrderController::class, 'index'])->name('orders.index');
});

// Seller
Route::middleware(['auth', 'role:seller'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/stores', [Seller\StoreController::class, 'index'])->name('stores.index');
});

// Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
});
```

- No `/api/v1/` prefix — this is not a REST API project. If a true JSON endpoint is needed (e.g., cart AJAX), use `/api/` in `routes/web.php` with `auth` middleware, and return `response()->json()`.

---

## Authentication

Laravel Breeze handles auth scaffolding (register, login, logout, password reset, email verification). Do not rewrite it.

- Auth routes and pages are already generated by Breeze. Extend them only when needed.
- Role checks via Middleware, Policies, and Gates — do not inline role checks in controllers.
- Never expose admin or seller routes to unauthorized users at the middleware level.
- The authenticated user is available in every Inertia page via the `auth` shared prop (set up in `HandleInertiaRequests`).

```tsx
// Accessing auth user in any page
import { usePage } from '@inertiajs/react';
import { PageProps } from '@/Types';

const { auth } = usePage<PageProps>().props;
```

---

## Frontend State

- **Inertia props** — primary data source. Server sends data; pages receive it as props. This replaces React Query for page-level data.
- **`useForm`** — for all form state and submission (replaces controlled forms + axios.post).
- **Zustand** — for global client-only state that doesn't come from the server: cart contents, active store context, UI state shared across components.
- **Local `useState`** — for temporary UI state: modals, dropdowns, toggles.
- Do **not** add React Query unless there is a specific need for client-side polling or infinite scroll that Inertia cannot handle. Ask first.

---

## TypeScript — Props Typing

Every Inertia page must have typed props.

```tsx
// resources/js/Types/index.d.ts — shared base
export interface User {
  id: number;
  name: string;
  email: string;
}

export interface PageProps {
  auth: {
    user: User;
  };
  flash?: {
    success?: string;
    error?: string;
  };
}
```

```tsx
// Page-specific props extend PageProps
import { PageProps } from '@/Types';

interface StoreIndexProps extends PageProps {
  stores: Store[];
}

export default function StoreIndex({ stores }: StoreIndexProps) { ... }
```

- Strict mode on. No `any`.
- Keep types simple and readable.
- Co-locate types with their domain when not shared. Put shared types in `resources/js/Types/`.

---

## Styling Rules

Use **TailwindCSS v3** utility classes. Do not write custom CSS unless Tailwind cannot express it.

- Follow a consistent spacing scale (use Tailwind defaults).
- Use semantic color tokens defined in `tailwind.config.js` — do not hardcode hex values in JSX.
- Responsive design is required. Mobile-first: base styles are mobile, `md:` and `lg:` for larger screens.
- Dark mode: optional for V1, but do not build anything that breaks if dark mode is added later.
- Use **Headless UI** (already installed) for accessible dropdowns, modals, and transitions.

### Tailwind Config — Reserved Token Names

Define these in `tailwind.config.js` before building UI:

```js
colors: {
  brand: { ... },    // Loomi primary palette
  surface: { ... },  // backgrounds, cards
  text: { ... },     // typography hierarchy
  border: { ... },   // dividers, outlines
  status: { ... },   // success, error, warning, info
}
```

---

## Image and Asset Rules

- Product images are uploaded to Laravel and stored in `storage/app/public/products/`.
- Serve via `php artisan storage:link` and the `/storage/` public path.
- On the frontend, never hardcode storage URLs. Use a helper:

```ts
// resources/js/Utils/storage.ts
export function storageUrl(path: string): string {
  return `/storage/${path}`;
}
```

- Brand and UI assets live in `resources/js/Constants/images.ts`.

```ts
// resources/js/Constants/images.ts
import logo from '@/Assets/images/logo.svg';
export const images = { logo };
```

---

## Security Rules

- Never expose secret keys in client code or version control.
- Use `.env` for all secrets. Provide `.env.example` with placeholder values.
- All mutations (POST, PUT, PATCH, DELETE) are CSRF-protected automatically via Inertia + Sanctum session cookies. Do not skip this.
- Validate every input server-side with Laravel Form Requests — never trust the client.
- Scope every query by the authenticated user's ownership. A seller must never be able to read or mutate another seller's store.

---

## Feature Implementation Checklist

When building a feature:

1. Read this file.
2. Identify the files to change — list them before writing code.
3. Write the migration first if the feature needs new tables or columns.
4. Write the model relationships.
5. Write the Form Request (validation).
6. Write the Service method (business logic).
7. Write the Controller — delegate to Service, return `Inertia::render()` or `redirect()->route()`.
8. Add the route (named) to the correct group in `routes/web.php`.
9. Build the Inertia page component in `resources/js/Pages/`.
10. Build any reusable components needed in `resources/js/Components/`.
11. Type all props. No `any`.
12. Use `useForm` for forms, `route()` for URLs, `<Link>` for navigation.
13. Hook up Zustand only if the state must persist across pages client-side.
14. Fix all lint and TypeScript errors before finishing.
15. Test the feature end to end.

---

## Communication

Be concise. Explain what changed and why. Always describe how to test the feature.

---

## Final Reminder

Before every feature:

- Read this file.
- Follow it strictly.
- This is an **Inertia.js** project — not a REST API. Data comes from controller props, not fetch calls. Forms use `useForm`. Navigation uses `<Link>` and `router`.
- Build clean, simple code.
- Replicate UI exactly when designs are provided.
- Scope data access by role — Customer sees their data, Seller sees their stores, Admin sees everything.
