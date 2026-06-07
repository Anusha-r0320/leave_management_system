# Leave Management System

A role-based Leave Management System built using Laravel that allows Employees to apply for leave, Managers to approve or reject requests, and Administrators to manage users, leave types, and leave balances.

## Features

* Employee Leave Application
* Leave Balance Management
* Leave Approval Workflow
* Role-Based Access Control
* Leave History Tracking
* Leave Overlap Validation
* Dashboard Statistics

## Technology Stack

### Backend

* PHP 8.3.6
* Laravel 12
* MySQL

### Frontend

* HTML5
* CSS3
* JavaScript
* Bootstrap 5
* jQuery

### Database

* MySQL

## Setup Instructions

### Prerequisites

* PHP 8.3+
* WAMP Server
* Composer
* Node.js & npm

### Database Setup

Create a database named:

```text
leave_management_system
```

### Environment Configuration

```bash
copy .env.example .env
```

Update the database settings:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=leave_management_system
DB_USERNAME=root
DB_PASSWORD=
```

### Install Dependencies

```bash
composer install
npm install
php artisan key:generate
```

### Run Migrations and Seeders

```bash
php artisan migrate --seed
```

### Run the Application

```bash
composer dev
```

or

```bash
php artisan serve
npm run dev
```

## Default Login Credentials

### Administrator

* Email: [admin@example.com](mailto:admin@example.com)
* Password: Admin@123

### Manager

* Email: [manager@example.com](mailto:manager@example.com)
* Password: Manager@123

### Employee

* Email: [employee@example.com](mailto:employee@example.com)
* Password: Employee@123

## Application Overview

The system provides a complete leave management workflow where employees can submit leave requests, managers can review and approve requests, and administrators can manage organizational leave policies and user accounts.
