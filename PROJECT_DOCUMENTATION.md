# Laravel + Vue.js Company Management System

## 📋 Project Overview

A full-stack web application for managing companies and their employees, built with Laravel 12, Vue.js 3, Inertia.js, and Ant Design Vue.

## ✅ Features Implemented

### Core Features
- **Authentication System**
  - Login-only system (registration disabled)
  - Two pre-seeded users with different roles
  - Role-based access control (Admin vs User)

- **Companies Management (Admin Only)**
  - Full CRUD operations
  - Logo upload functionality
  - Server-side pagination with search
  - Data validation

- **Employees Management (Admin Only)**
  - Full CRUD operations
  - Company association
  - Filter by company
  - Server-side pagination with search
  - Company details modal

### Bonus Features
- **Email Notifications**: Automatic email sent to company when new employee is added
- **Sample Data Seeders**: Pre-populated data for testing
- **Enhanced UI/UX**: Loading states, success messages, and error handling

## 🚀 Installation & Setup

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM
- SQLite (or MySQL/PostgreSQL)

### Installation Steps

1. **Clone the repository**
```bash
git clone [repository-url]
cd grtech-laravel-vue-test
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Environment setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure mail settings (optional)**
Edit `.env` file for email notifications:
```env
# For testing, use 'log' to see emails in storage/logs/laravel.log
MAIL_MAILER=log

# For production (example with Gmail)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD="your-app-specific-password"
MAIL_ENCRYPTION=tls
```

5. **Run migrations and seeders**
```bash
php artisan migrate:fresh --seed
```

6. **Create storage symlink**
```bash
php artisan storage:link
```

7. **Build frontend assets**
```bash
npm run build
```

8. **Start development server**
```bash
composer run dev
```

This will start:
- Laravel server on http://localhost:8000
- Vite dev server for hot-reloading
- Queue worker for notifications
- Log viewer (Pail)

## 👤 Login Credentials

### Admin User
- **Email**: admin@grtech.com
- **Password**: password
- **Access**: Full access to all features

### Regular User
- **Email**: user@grtech.com
- **Password**: password
- **Access**: Dashboard only (no access to Companies/Employees)

## 📊 Database Schema

### Users Table
- id, name, email, password, role, timestamps

### Companies Table
- id, name, email (nullable), logo (nullable), website (nullable), timestamps

### Employees Table
- id, first_name, last_name, company_id (foreign key), email (nullable), phone (nullable), timestamps

## 🗂️ Sample Data

The seeder creates:
- **5 Companies**: PT Global Tech Solutions, CV Maju Jaya Indonesia, Tech Innovators Ltd, etc.
- **13 Employees**: Distributed across the companies with complete contact information

## 🛠️ Available Commands

### Development
```bash
composer run dev        # Start all dev servers
npm run dev            # Start Vite only
npm run build          # Build for production
npm run build:ssr      # Build with SSR
```

### Testing
```bash
composer run test      # Run PHP tests
npm run lint          # ESLint check & fix
npm run format        # Prettier format
```

### Database
```bash
php artisan migrate:fresh --seed    # Reset and seed database
php artisan db:seed                 # Run seeders only
```

## 📁 Project Structure

```
├── app/
│   ├── Http/
│   │   ├── Controllers/        # Resource controllers
│   │   ├── Middleware/         # AdminMiddleware
│   │   └── Requests/          # Form validation
│   ├── Models/                # Eloquent models
│   └── Notifications/         # Email notifications
├── resources/
│   └── js/
│       ├── pages/
│       │   ├── Companies/    # Companies CRUD pages
│       │   └── Employees/    # Employees CRUD pages
│       └── components/       # Reusable Vue components
├── database/
│   ├── migrations/           # Database schema
│   └── seeders/             # Sample data
└── routes/
    └── web.php              # Application routes
```

## 🔧 Configuration

### Email Notifications
When a new employee is added, an email notification is automatically sent to the company's email address if configured. The email includes:
- Employee's full name
- Email address
- Phone number
- Link to view employee details

### File Storage
Company logos are stored in `storage/app/public/companies` and accessible via `/storage/companies/[filename]`

## 📝 API Endpoints

### Companies
- GET `/companies` - List with pagination
- POST `/companies` - Create new company
- PUT `/companies/{id}` - Update company
- DELETE `/companies/{id}` - Delete company

### Employees
- GET `/employees` - List with pagination
- POST `/employees` - Create new employee
- PUT `/employees/{id}` - Update employee
- DELETE `/employees/{id}` - Delete employee

## 🎨 UI Components

- **Ant Design Vue**: Primary UI library for complex components
- **Tailwind CSS**: Utility-first CSS for custom styling
- **Lucide Icons**: Icon library for navigation
- **Ant Design Icons**: Icons for datatables and actions

## 🔒 Security Features

- Role-based access control
- CSRF protection
- XSS protection via Vue.js
- SQL injection prevention via Eloquent ORM
- File upload validation
- Email verification for users

## 📌 Notes

- Email notifications require proper SMTP configuration
- Default mail driver is set to 'log' for testing
- All emails are logged to `storage/logs/laravel.log` when using log driver
- Maximum file upload size for logos: 2MB
- Supported image formats: JPEG, PNG, JPG, GIF

## 🤝 Contributing

This is a technical test project. For production use, consider:
- Implementing more robust error handling
- Adding comprehensive test coverage
- Implementing caching strategies
- Adding API rate limiting
- Implementing audit logging

## 📄 License

This project is created for technical assessment purposes.