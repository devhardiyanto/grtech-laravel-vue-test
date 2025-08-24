# Laravel + Vue.js Test Project Documentation

## Project Overview

This is a modern full-stack web application built with **Laravel 12** and **Vue.js 3** using **Inertia.js** as the bridge between backend and frontend. The project implements a complete authentication system with modern UI components and follows best practices for both Laravel and Vue.js development.

## Technology Stack

### Backend (Laravel)
- **Laravel Framework**: ^12.0
- **PHP**: ^8.2
- **Inertia.js Laravel**: ^2.0 (Server-side adapter)
- **Ziggy**: ^2.4 (Route generation for JavaScript)

### Frontend (Vue.js)
- **Vue.js**: ^3.5.13
- **Inertia.js Vue3**: ^2.1.0 (Client-side adapter)
- **TypeScript**: ^5.2.2
- **Tailwind CSS**: ^4.1.1
- **Vite**: ^7.0.4 (Build tool)

### UI Components & Utilities
- **Reka UI**: ^2.2.0 (Headless UI components)
- **Lucide Vue Next**: ^0.468.0 (Icons)
- **VueUse**: ^12.8.2 (Vue composition utilities)
- **Class Variance Authority**: ^0.7.1 (CSS class management)

## Architecture & Mechanisms

### 1. Inertia.js Architecture

**Inertia.js** acts as a bridge between Laravel (backend) and Vue.js (frontend), enabling:

- **Single Page Application (SPA) behavior** without API complexity
- **Server-side routing** with client-side navigation
- **Shared data** between backend and frontend
- **Progressive enhancement** with JavaScript

#### How it works:
```php
// Laravel Controller
Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');
```

```typescript
// Vue.js App Setup (resources/js/app.ts)
createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
});
```

### 2. Authentication System

Complete authentication flow implemented with:

#### Routes (`routes/auth.php`):
- **Registration**: `GET/POST /register`
- **Login**: `GET/POST /login`
- **Password Reset**: `GET/POST /forgot-password`, `GET/POST /reset-password/{token}`
- **Email Verification**: `GET /verify-email`, `GET /verify-email/{id}/{hash}`
- **Logout**: `POST /logout`

#### Middleware Protection:
- **Guest routes**: Registration, login, password reset
- **Authenticated routes**: Dashboard, email verification, logout
- **Verified routes**: Dashboard (requires email verification)

### 3. Frontend Structure

#### Page Components (`resources/js/pages/`):
- **Welcome.vue**: Landing page
- **Dashboard.vue**: Authenticated user dashboard
- **auth/**: Authentication pages (login, register, etc.)
- **settings/**: User settings pages

#### Shared Components (`resources/js/components/`):
- Reusable UI components built with Reka UI
- Form components, buttons, modals, etc.

#### Layouts (`resources/js/layouts/`):
- Application layouts for different page types
- Navigation, sidebars, footers

### 4. Theme System

#### Dark/Light Mode Support:
```typescript
// Automatic theme detection
const appearance = '{{ $appearance ?? "system" }}';
if (appearance === 'system') {
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    if (prefersDark) {
        document.documentElement.classList.add('dark');
    }
}
```

#### CSS Variables:
```css
html {
    background-color: oklch(1 0 0); /* Light mode */
}
html.dark {
    background-color: oklch(0.145 0 0); /* Dark mode */
}
```

### 5. Build System & Development

#### Vite Configuration:
- **Hot Module Replacement (HMR)** for development
- **TypeScript support** out of the box
- **Tailwind CSS** integration
- **Asset bundling** and optimization

#### Development Scripts:
```json
{
    "dev": "vite",
    "build": "vite build",
    "build:ssr": "vite build && vite build --ssr"
}
```

#### Laravel Development:
```json
{
    "dev": "npx concurrently \"php artisan serve\" \"php artisan queue:listen\" \"php artisan pail\" \"npm run dev\"",
    "test": "@php artisan test"
}
```

### 6. Route Generation (Ziggy)

**Ziggy** generates JavaScript routes from Laravel routes:

```typescript
// Usage in Vue components
import { route } from 'ziggy-js';

// Navigate to named routes
route('dashboard'); // Returns: /dashboard
route('login'); // Returns: /login
```

### 7. Testing Framework

#### Backend Testing (Pest PHP):
- **Feature tests**: HTTP requests, authentication flows
- **Unit tests**: Individual component testing
- Located in `tests/` directory

#### Frontend Testing:
- **ESLint**: Code linting and formatting
- **Prettier**: Code formatting
- **TypeScript**: Type checking

## Project Structure

```
├── app/                          # Laravel application code
│   ├── Http/Controllers/         # HTTP controllers
│   ├── Models/                   # Eloquent models
│   └── Providers/               # Service providers
├── resources/
│   ├── js/                      # Vue.js frontend code
│   │   ├── components/          # Reusable Vue components
│   │   ├── pages/              # Inertia.js pages
│   │   ├── layouts/            # Page layouts
│   │   ├── composables/        # Vue composition functions
│   │   └── app.ts              # Main application entry
│   ├── css/                    # Stylesheets (Tailwind CSS)
│   └── views/                  # Blade templates
├── routes/                     # Route definitions
│   ├── web.php                # Web routes
│   ├── auth.php               # Authentication routes
│   └── settings.php           # Settings routes
├── database/                  # Database migrations, seeders
├── tests/                    # Test files
└── public/                   # Public assets
```

## Key Features

### 1. **Modern Stack**
- Laravel 12 with PHP 8.2+
- Vue.js 3 with Composition API
- TypeScript for type safety
- Tailwind CSS for styling

### 2. **Developer Experience**
- Hot reload development
- Type checking
- Code formatting and linting
- Concurrent development servers

### 3. **Authentication**
- Complete auth flow
- Email verification
- Password reset
- Session management

### 4. **UI/UX**
- Responsive design
- Dark/light theme support
- Modern component library
- Accessible components

### 5. **Performance**
- SPA-like navigation
- Optimized asset bundling
- Server-side rendering support
- Progressive enhancement

## Getting Started

1. **Install dependencies**:
   ```bash
   composer install
   npm install
   ```

2. **Environment setup**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Database setup**:
   ```bash
   php artisan migrate
   ```

4. **Start development**:
   ```bash
   composer run dev
   ```

This will start all development servers concurrently:
- Laravel server (PHP)
- Queue worker
- Log viewer (Pail)
- Vite dev server (Vue.js)

## Testing

```bash
# Run PHP tests
composer run test

# Run frontend linting
npm run lint

# Format code
npm run format
```

---

This documentation provides a comprehensive overview of the Laravel + Vue.js project architecture and mechanisms. The combination of Inertia.js, modern tooling, and best practices creates a robust foundation for full-stack web development.
