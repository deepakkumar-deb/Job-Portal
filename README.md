# Job Portal

A Laravel 12 based job portal where employers can post jobs and job seekers can browse, save, and apply for jobs.

---

## Features

- User registration and login
- Profile management with picture upload and thumbnail generation
- Password change
- Job listing with filters — keyword, location, category, job type, sort order
- Job details page
- Apply to jobs
- Save jobs
- Email notification to employer on application
- **Employer dashboard:**
  - Create, edit, delete jobs
  - View own job listings
- **Applicant dashboard:**
  - View applied jobs
  - Remove job applications
  - Manage saved jobs

---

## Tech Stack

| Layer | Technology |
|-------|------------|
| Language | PHP 8.2+ |
| Framework | Laravel 12 |
| Database | MySQL |
| Frontend Build | Vite |
| CSS | Tailwind CSS 4 |
| Image Processing | Intervention Image |
| Dev Tools | Laravel Debugbar |

---

## Requirements

- PHP 8.2 or later
- Composer
- Node.js & npm
- MySQL (or any Laravel-supported DB driver)
- XAMPP / WAMP / LAMP or similar local server

---

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/your-username/job-portal.git
cd job-portal
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install frontend dependencies

```bash
npm install
```

### 4. Create environment file

```bash
cp .env.example .env
```

### 5. Generate application key

```bash
php artisan key:generate
```

### 6. Configure database in `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=job_portal
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 7. Run migrations

```bash
php artisan migrate
```

### 8. (Optional) Seed the database

```bash
php artisan db:seed
```

### 9. Build frontend assets

```bash
npm run build
```

---

## Development

Run the backend and frontend dev servers separately:

```bash
# Laravel backend
php artisan serve

# Vite frontend (hot reload)
npm run dev
```

Or use the Composer dev script which starts the server, queue listener, logs, and Vite together:

```bash
composer run dev
```

---

## Mail Configuration

Job application notifications send an email to the employer. Configure mail settings in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_user
MAIL_PASSWORD=your_mailtrap_password
MAIL_FROM_ADDRESS=noreply@jobportal.com
MAIL_FROM_NAME="Job Portal"
```

> For local testing, [Mailtrap](https://mailtrap.io) or `log` mailer is recommended.
>
> To use log mailer: set `MAIL_MAILER=log` — emails appear in `storage/logs/laravel.log`.

---

## Queue

The Composer dev script starts a queue worker automatically:

```bash
php artisan queue:listen --tries=1 --timeout=0
```

If running the server manually, start the queue worker separately:

```bash
php artisan queue:work
```

---

## Project Structure

```
job_portal/
├── artisan                          # Laravel CLI tool
├── composer.json                    # PHP dependencies
├── package.json                     # Node dependencies
├── phpunit.xml                      # PHPUnit configuration
├── vite.config.js                   # Vite build configuration
│
├── app/
│   ├── Http/
│   │   └── Controllers/
│   ├── Mail/
│   │   └── JobNotificationEmail.php
│   ├── Models/
│   │   ├── Category.php
│   │   ├── Job.php
│   │   ├── JobApplication.php
│   │   ├── JobType.php
│   │   ├── SavedJobs.php
│   │   └── User.php
│   └── Providers/
│       └── AppServiceProvider.php
│
├── bootstrap/
│   ├── app.php
│   ├── providers.php
│   └── cache/
│
├── config/                          # Configuration files
│   ├── app.php
│   ├── auth.php
│   ├── cache.php
│   ├── database.php
│   ├── filesystems.php
│   ├── logging.php
│   ├── mail.php
│   ├── queue.php
│   ├── services.php
│   └── session.php
│
├── database/
│   ├── factories/                   # Model factories for testing
│   │   ├── CategoryFactory.php
│   │   ├── JobFactory.php
│   │   ├── JobTypeFactory.php
│   │   └── UserFactory.php
│   ├── migrations/                  # Database migrations
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 2026_03_26_180801_add_profile_picture_to_users_table.php
│   │   ├── 2026_03_26_184129_create_categories_table.php
│   │   ├── 2026_03_26_184201_create_job_types_table.php
│   │   ├── 2026_03_31_090648_create_jobs_table.php
│   │   ├── 2026_04_01_085140_create_job_applications_table.php
│   │   └── 2026_04_02_085237_create_saved_jobs_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
│
├── public/                          # Public root directory
│   ├── index.php
│   ├── robots.txt
│   ├── assets/
│   │   ├── css/
│   │   ├── fonts/
│   │   ├── images/
│   │   └── js/
│   └── profile_pictures/
│       └── thumb/
│
├── resources/                       # Frontend resources
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   ├── app.js
│   │   └── bootstrap.js
│   └── views/
│       ├── welcome.blade.php
│       ├── emails/
│       └── front/
│
├── routes/
│   ├── console.php
│   └── web.php
│
├── storage/                         # Application storage
│   ├── app/
│   ├── framework/
│   └── logs/
│
├── tests/                           # Test files
│   ├── Feature/
│   └── Unit/
│
└── vendor/                          # Composer packages
```

---

## Routes Overview

### Public Routes

| Method | URI | Controller | Purpose |
|--------|-----|------------|---------|
| GET | `/` | `HomeController@index` | Home page |
| GET | `/jobs` | `JobController@index` | Job listings |
| GET | `/jobs/details/{id}` | `JobController@details` | Job details |
| POST | `/apply-job` | `JobController@applyJob` | Apply for a job |
| POST | `/save-job` | `JobController@saveJob` | Save a job |

### Account Routes (`/account`)

#### Guest Only

| Method | URI | Controller | Purpose |
|--------|-----|------------|---------|
| GET | `/account/login` | `AccountController@login` | Login page |
| POST | `/account/authenticate` | `AccountController@authenticate` | Authenticate user |
| GET | `/account/register` | `AccountController@registration` | Registration form |
| POST | `/account/process-register` | `AccountController@processRegistration` | Process registration |

#### Authenticated Only

| Method | URI | Controller | Purpose |
|--------|-----|------------|---------|
| GET | `/account/profile` | `AccountController@profile` | View profile |
| POST | `/account/update-profile` | `AccountController@updateProfile` | Update profile info |
| POST | `/account/update-profile-picture` | `AccountController@updateProfilePicture` | Update profile picture |
| POST | `/account/update-password` | `AccountController@changePassword` | Change password |
| POST | `/account/logout` | `AccountController@logout` | Logout |

#### Job Management (Employer)

| Method | URI | Controller | Purpose |
|--------|-----|------------|---------|
| GET | `/account/create-job` | `AccountController@createJob` | Create job form |
| POST | `/account/save-job` | `AccountController@saveJob` | Post new job |
| GET | `/account/my-jobs` | `AccountController@myJobs` | View my jobs |
| GET | `/account/my-jobs/edit/{jobId}` | `AccountController@editJob` | Edit job form |
| POST | `/account/my-jobs/update/{jobId}` | `AccountController@updateJob` | Update job |
| POST | `/account/my-jobs/delete` | `AccountController@deleteJob` | Delete job |

#### Job Applications

| Method | URI | Controller | Purpose |
|--------|-----|------------|---------|
| GET | `/account/my-applications` | `AccountController@myJobApplications` | View applications |
| POST | `/account/remove-job-applications` | `AccountController@removeJobs` | Remove application |

#### Saved Jobs

| Method | URI | Controller | Purpose |
|--------|-----|------------|---------|
| GET | `/account/saved-jobs` | `AccountController@savedjobs` | View saved jobs |
| POST | `/account/remove-saved-job` | `AccountController@removeSavedJob` | Remove saved job |

---

## Notes

- Profile pictures are stored in `public/profile_pictures/` with thumbnails in `public/profile_pictures/thumb/`
- Email templates are located in `resources/views/emails/`
- Ensure `storage/` and `bootstrap/cache/` are writable in production:

```bash
chmod -R 775 storage bootstrap/cache
```

---

## Testing

```bash
php artisan test
```

---

## License

This project is open-sourced software licensed under the [MIT license](LICENSE).
