# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Technology Stack

- **Laravel 12** with PHP 8.2+ backend
- **Vue.js 3** with TypeScript frontend 
- **Inertia.js** for SPA-like navigation without API complexity
- **Tailwind CSS v4** with Vite integration
- **SQLite** database (default configuration)
- **Pest PHP** for backend testing

## Key Development Commands

### Development
```bash
# Start all development servers concurrently (Laravel, Queue, Logs, Vite)
composer run dev

# Start with SSR enabled
composer run dev:ssr

# Run frontend development server only
npm run dev

# Build frontend assets
npm run build

# Build with SSR
npm run build:ssr
```

### Testing & Code Quality
```bash
# Run PHP tests with Pest
composer run test

# Run a specific test file
php artisan test tests/Feature/Auth/AuthenticationTest.php

# Run a specific test method
php artisan test --filter="test_example_method"

# Format code with Prettier
npm run format

# Check formatting without fixing
npm run format:check

# Lint and fix with ESLint
npm run lint

# Laravel code formatting with Pint
./vendor/bin/pint
```

### Database & Migrations
```bash
# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Fresh migration (drop all tables and re-run)
php artisan migrate:fresh

# Seed database
php artisan db:seed
```

## Architecture Overview

### Inertia.js Flow
1. **Routes** in `routes/web.php` return Inertia responses via `Inertia::render('PageName')`
2. **Page components** in `resources/js/pages/` receive props from Laravel controllers
3. **Shared data** configured in `app/Http/Middleware/HandleInertiaRequests.php`
4. **Client-side routing** handled by Inertia with server-side route generation via Ziggy

### Directory Structure

#### Backend (Laravel)
- `app/Http/Controllers/` - HTTP controllers organized by feature (Auth, Settings)
- `app/Http/Middleware/HandleInertiaRequests.php` - Inertia middleware for shared props
- `app/Models/` - Eloquent models
- `routes/` - Route definitions split by concern (web, auth, settings)
- `database/migrations/` - Database schema migrations
- `tests/` - Pest test files (Feature and Unit tests)

#### Frontend (Vue/Inertia)
- `resources/js/pages/` - Inertia page components (mapped to routes)
- `resources/js/components/` - Reusable Vue components
- `resources/js/components/ui/` - Reka UI component library
- `resources/js/layouts/` - Layout wrapper components
- `resources/js/composables/` - Vue composition functions
- `resources/js/app.ts` - Main application entry point

### Authentication System
- Complete auth flow implemented in `routes/auth.php`
- Controllers in `app/Http/Controllers/Auth/`
- Frontend pages in `resources/js/pages/auth/`
- Protected routes use `auth` and `verified` middleware

### Frontend Components
- UI components built with **Reka UI** (headless component library)
- Icons from **Lucide Vue Next**
- Styling with **Tailwind CSS v4** and **class-variance-authority**
- Dark/light theme support via `useAppearance` composable

### Key Configuration Files
- `vite.config.ts` - Vite bundler configuration with Laravel plugin
- `tailwind.config.js` - Tailwind CSS configuration
- `tsconfig.json` - TypeScript configuration
- `.env` - Environment variables (copy from `.env.example`)
- `components.json` - UI component configuration

## Important Patterns

### Creating New Pages
1. Add route in appropriate route file (`routes/web.php`, etc.)
2. Return Inertia response: `return Inertia::render('PageName', ['prop' => $value])`
3. Create Vue component in `resources/js/pages/PageName.vue`
4. Page automatically receives props from controller

### Using Ziggy Routes in Vue
```typescript
import { route } from 'ziggy-js';

// Generate URLs
route('dashboard'); // /dashboard
route('profile.show', { id: 1 }); // /profile/1

// Navigation with Inertia
import { router } from '@inertiajs/vue3';
router.visit(route('dashboard'));
```

### Shared Props Access
Props shared via `HandleInertiaRequests` middleware are available in all components:
```typescript
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const user = page.props.auth.user;
```

### Form Handling with Inertia
```typescript
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
});

form.post(route('login'), {
    onFinish: () => form.reset('password'),
});
```

## Database Configuration
- Default: SQLite at `database/database.sqlite`
- Session and cache use database drivers
- Queue uses database driver (jobs table)

## Testing Approach
- Feature tests for HTTP requests and user flows
- Unit tests for isolated component testing
- Test database automatically configured for testing environment
- Authentication tests cover full auth flow