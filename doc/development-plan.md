# Laravel Test Project - Development Plan

## Requirements Analysis

### Current Project Status
- ✅ Laravel 12 with Inertia.js + Vue 3 + Tailwind CSS already set up
- ✅ Basic authentication system exists
- ❌ Need to modify authentication (remove register, add specific users)
- ❌ Need to add Companies & Employees CRUD
- ❌ Need to integrate Ant Design Vue
- ❌ Need server-side pagination with datatables

### Key Requirements Breakdown

## 1. Authentication System Modifications

### Current State:
- Laravel Breeze with Inertia + Vue 3 + Tailwind ✅
- Full authentication flow exists ✅

### Required Changes:
- **Remove register functionality** from routes and UI
- **Create database seeders** for specific users:
  - `admin@grtech.com` / `password`
  - `user@grtech.com` / `password`
- **Add role-based middleware** to restrict `user@grtech.com` from Companies & Employees routes

## 2. Database Schema & Migrations

### Companies Table:
```sql
- id (primary key)
- name (required, string)
- email (nullable, string)
- logo (nullable, string) 
- website (nullable, string)
- timestamps
```

### Employees Table:
```sql
- id (primary key)
- first_name (required, string)
- last_name (required, string)
- company_id (foreign key to companies)
- email (nullable, string)
- phone (nullable, string)
- timestamps
```

### Users Table Modification:
- Add `role` field to distinguish admin vs user

## 3. Backend Development Targets

### Controllers to Create:
- `app/Http/Controllers/CompanyController.php` (Resource Controller)
- `app/Http/Controllers/EmployeeController.php` (Resource Controller)

### Request Validation Classes:
- `app/Http/Requests/StoreCompanyRequest.php`
- `app/Http/Requests/UpdateCompanyRequest.php`
- `app/Http/Requests/StoreEmployeeRequest.php`
- `app/Http/Requests/UpdateEmployeeRequest.php`

### Models to Create:
- `app/Models/Company.php`
- `app/Models/Employee.php`
- Modify `app/Models/User.php` (add role)

### API Resources:
- `app/Http/Resources/CompanyResource.php`
- `app/Http/Resources/EmployeeResource.php`
- `app/Http/Resources/CompanyCollection.php`
- `app/Http/Resources/EmployeeCollection.php`

### Routes to Add:
```php
// routes/web.php
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('companies', CompanyController::class);
    Route::resource('employees', EmployeeController::class);
});
```

### Middleware to Create:
- `app/Http/Middleware/AdminMiddleware.php` (restrict user@grtech.com)

### Database Files:
- `database/migrations/xxxx_create_companies_table.php`
- `database/migrations/xxxx_create_employees_table.php`
- `database/migrations/xxxx_add_role_to_users_table.php`
- `database/seeders/UserSeeder.php`
- `database/seeders/CompanySeeder.php` (optional sample data)
- `database/seeders/EmployeeSeeder.php` (optional sample data)

## 4. Frontend Development Targets

### Package Installation:
- Install **Ant Design Vue**: `npm install ant-design-vue`
- Configure Ant Design Vue with existing Tailwind CSS

### Vue Pages to Create:
- `resources/js/pages/Companies/Index.vue` (Datatable with server-side pagination)
- `resources/js/pages/Companies/Create.vue` (Create form modal)
- `resources/js/pages/Companies/Edit.vue` (Edit form modal)
- `resources/js/pages/Employees/Index.vue` (Datatable with server-side pagination)
- `resources/js/pages/Employees/Create.vue` (Create form modal)
- `resources/js/pages/Employees/Edit.vue` (Edit form modal)

### Vue Components to Create:
- `resources/js/components/DataTable.vue` (Reusable datatable component)
- `resources/js/components/CompanyModal.vue` (Company details modal)
- `resources/js/components/ConfirmDelete.vue` (Delete confirmation modal)
- `resources/js/components/FileUpload.vue` (Logo upload component)

### Layout Modifications:
- Update navigation to include Companies & Employees menu
- Add role-based menu visibility

### Datatable Requirements:

#### Companies Datatable Columns:
- Index
- Name
- Email
- Logo (image preview)
- Website (clickable link)
- Action (Edit/Delete buttons)

#### Employees Datatable Columns:
- Index
- Full Name (first_name + last_name)
- Company (clickable link → shows company modal)
- Email
- Phone
- Action (Edit/Delete buttons)

## 5. File Storage Configuration

### Storage Setup:
- Configure `storage/app/public` for company logos
- Create symbolic link: `php artisan storage:link`
- Add file upload validation and processing

## 6. Bonus Feature

### Email Notifications:
- `app/Notifications/NewEmployeeNotification.php`
- Send email to company when new employee is added
- Configure mail settings in `.env`

## 7. Implementation Priority

### Phase 1: Database & Backend (High Priority)
1. Create migrations for Companies & Employees tables
2. Create Models with relationships
3. Create Resource Controllers
4. Create Request validation classes
5. Create API Resources
6. Add routes with middleware protection
7. Create user seeders
8. Create admin middleware

### Phase 2: Frontend Integration (High Priority)
1. Install and configure Ant Design Vue
2. Remove register functionality from auth
3. Create Companies CRUD pages with Ant Design components
4. Create Employees CRUD pages with Ant Design components
5. Implement server-side pagination
6. Add file upload for company logos

### Phase 3: Advanced Features (Medium Priority)
1. Add company details modal for employees datatable
2. Implement delete confirmations
3. Add proper error handling and loading states
4. Style integration between Ant Design and Tailwind

### Phase 4: Bonus Features (Low Priority)
1. Email notifications for new employees
2. Additional UI enhancements
3. Testing and optimization

## Key Challenges & Considerations

### 1. UI Library Integration:
- **Challenge**: Mixing Ant Design Vue with existing Tailwind CSS
- **Solution**: Use Ant Design components for complex elements (datatables, modals) and Tailwind for layout/spacing

### 2. Server-Side Pagination:
- **Challenge**: Implementing efficient server-side pagination with search/filter
- **Solution**: Use Laravel's built-in pagination with API resources

### 3. File Upload:
- **Challenge**: Handling logo uploads with proper validation
- **Solution**: Use Laravel's file storage with validation rules

### 4. Role-Based Access:
- **Challenge**: Restricting specific user from certain routes
- **Solution**: Custom middleware checking user email/role

## Success Criteria

- ✅ Authentication works with specified users
- ✅ Admin can access all features, user cannot access Companies/Employees
- ✅ CRUD operations work for both Companies and Employees
- ✅ Datatables show data with server-side pagination
- ✅ File upload works for company logos
- ✅ Modals work for edit/delete/company details
- ✅ Validation works on all forms
- ✅ Email notifications sent for new employees (bonus)

## Estimated Timeline
- **Phase 1**: 2-3 days
- **Phase 2**: 3-4 days  
- **Phase 3**: 1-2 days
- **Phase 4**: 1 day
- **Total**: 7-10 days

This plan provides a clear roadmap for implementing all requirements while maintaining code quality and following Laravel/Vue.js best practices.
