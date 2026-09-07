# ESA Learning & Training Center System (LMS)

A standalone Learning Management System designed to handle multi-principal environments with a modular monolith architecture. Built with **Laravel 11**, **Vue 3**, **Inertia.js**, and **Tailwind CSS**.

## ?? Progress & Features Built So Far

### 1. Database & Architecture (100% Complete)
- Fully migrated PostgreSQL database covering **Phase 1 to Phase 10** of the business requirements.
- Core tables implemented: Users, Principals, Roles (Spatie), Courses, Modules, Lessons, Assessments, Certificates, Notifications, and Import/Audit logs.

### 2. UI & Frontend Milestones
- **Milestone 1**: Admin Dashboard layout & UI structure.
- **Milestone 2**: Course Builder UI.
- **Milestone 3**: Learner Portal & Progress UI.
- **Milestone 4**: Assessment / Post-Test UI.

### 3. Backend Integrations
- **Integration 1 (User Management)**: 
  - Full CRUD operations for Users (Admin, Learner, Manager). 
  - Automated password generation and NIK validation.
- **Integration 2 (Course Builder)**:
  - Full CRUD operations for Courses.
  - Interactive Course Builder allowing Admins to manage Modules and Lessons (Video URLs, text content, durations).

---

## ?? Tech Stack
- **Backend**: Laravel 11.x (PHP 8.2+)
- **Frontend**: Vue.js 3 (Composition API) + Inertia.js
- **Styling**: Tailwind CSS
- **Build Tool**: Vite
- **Database**: PostgreSQL
- **Role Management**: Spatie Laravel Permission

---

## ?? Getting Started (Local Development)

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & npm
- PostgreSQL

### Installation Steps

1. **Clone the repository:**
   ```bash
   git clone https://github.com/ianjulian18/Esa-Learning-And-Training-Center-System.git
   cd Esa-Learning-And-Training-Center-System
   ```

2. **Install PHP Dependencies:**
   ```bash
   composer install
   ```

3. **Install Node Dependencies:**
   ```bash
   npm install
   ```

4. **Environment Setup:**
   Copy the `.env.example` file and configure your database settings:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Run Migrations & Seeders:**
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Create Storage Link:**
   ```bash
   php artisan storage:link
   ```

7. **Compile Frontend Assets:**
   ```bash
   npm run dev
   ```

8. **Serve the Application:**
   ```bash
   php artisan serve
   ```
   *Note: If you are using Laragon, you can access the app directly via `http://esa-lms.test`.*

---
*Maintained by Antigravity AI.*
