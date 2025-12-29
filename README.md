# Work Tracker

A modern, Laravel-based work tracker application to help you monitor daily work, track progress, and analyze productivity.

---

## Features

-   **Daily Work Targets** - Set your target work hours for each day
-   **Start/Pause Timer** - Track time with the ability to pause for breaks
-   **Real-time Progress** - Live countdown and progress bar updates
-   **Detailed Time Logs** - View all your work sessions with break durations
-   **Statistics & Analytics** - Track your performance over 30 days
-   **Flexible Targets** - Update daily goals even after starting
-   **Persistent Data** - All data survives computer shutdowns
-   **Timezone Support** - Accurate time tracking in your local timezone

---

## Tech Stack

-   **Backend:** Laravel 11.x
-   **Frontend:** Blade Templates, Tailwind CSS, Alpine.js
-   **Database:** MySQL 8.0
-   **Architecture:** Component-based Blade structure

---

## Installation (Local Development)

1. Clone the repository:
   git clone https://github.com/yourusername/work-tracker.git
   cd work-tracker

2. Install PHP dependencies:
   composer install

3. Install front-end dependencies:
   npm install
   npm run dev

4. Copy .env.example to .env and configure your environment:
   cp .env.example .env

    Update the database credentials:
    Copy code
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=work_tracker
    DB_USERNAME=root
    DB_PASSWORD=your_password

5. Generate the application key:
   php artisan key:generate

6. Run migrations:
   php artisan migrate

7. Start the development server:
   php artisan serve
8. Open your browser at http://127.0.0.1:8000.
