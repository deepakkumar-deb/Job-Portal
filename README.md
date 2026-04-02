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
|-------|-----------|
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
> To use log mailer: set `MAIL_MAILER=log` — emails will appear in `storage/logs/laravel.log`.

---

## Queue

The Composer dev script starts a queue worker automatically:

```bash
php artisan queue:listen --tries=1 --timeout=0
```

If you run the server manually, start the queue worker separately:

```bash
php artisan queue:work
```

---

## Project Structure

```
job-portal/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AccountController.php   # Auth, profile, dashboard actions
│   │       ├── JobController.php       # Browse, apply, save jobs
│   │       └── HomeController.php      # Homepage
│   └── Models/
│       ├── User.php
│       ├── Job.php
│       ├── Category.php
│       ├── JobType.php
│       ├── JobApplication.php
│       └── SavedJobs.php
├── resources/
│   └── views/
│       └── front/
│           ├── home.blade.php
│           ├── jobs.blade.php
│           ├── account/
│           │   ├── profile.blade.php
│           │   ├── login.blade.php
│           │   ├── registration.blade.php
│           │   └── job/
│           └── emails/             # Email templates
├── routes/
│   └── web.php
└── public/
    └── profile_pictures/
        └── thumb/
```

---

## Routes Overview

### Public Routes

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/` | Homepage |
| GET | `/jobs` | Job listings with filters |
| GET | `/jobs/details/{id}` | Job detail page |
| POST | `/apply-job` | Apply for a job |
| POST | `/save-job` | Save a job |

### Account Routes (Auth Required)

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/account/login` | Login page |
| GET | `/account/register` | Registration page |
| GET | `/account/profile` | User profile |
| POST | `/account/update-profile` | Update profile info |
| POST | `/account/update-profile-picture` | Update profile picture |
| POST | `/account/update-password` | Change password |
| GET | `/account/create-job` | Create job form |
| POST | `/account/save-job` | Post a new job |
| GET | `/account/my-jobs` | My posted jobs |
| GET | `/account/my-applications` | My job applications |
| GET | `/account/saved-jobs` | Saved jobs |

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
