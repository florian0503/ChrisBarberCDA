# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Symfony 6.4 application for ChrisBarber CDA, a barber shop booking system. The application includes user authentication, barber management, appointment reservations, and payment processing via Stripe.

## Core Architecture

### Backend Stack
- **Framework**: Symfony 6.4 LTS with PHP 8.1+
- **Database**: Doctrine ORM with MariaDB/PostgreSQL support
- **Authentication**: Symfony Security component with User entity
- **Admin Panel**: EasyAdmin Bundle for backend management
- **Payment**: Stripe integration for payment processing
- **Migrations**: Doctrine Migrations for database schema management

### Frontend Stack
- **Asset Management**: Webpack Encore with Sass support
- **CSS Framework**: Bootstrap 5.3.5
- **JavaScript**: Stimulus framework for interactive components
- **Template Engine**: Twig templating

### Key Entities
- **User**: Customer accounts with email authentication
- **Barber**: Staff members who provide services
- **Reservation**: Appointment bookings linking users to barbers with Stripe session tracking
- **Contact**: Contact form submissions

### Architecture Patterns
- Standard Symfony MVC architecture
- Repository pattern for data access
- Form types for data validation
- Attribute-based routing and ORM mapping
- Service container with autowiring

## Development Commands

### Composer (PHP Dependencies)
```bash
composer install          # Install PHP dependencies
composer update           # Update dependencies
```

### Asset Management
```bash
npm install               # Install Node.js dependencies
npm run dev              # Build assets for development
npm run watch            # Watch and rebuild assets automatically
npm run build            # Build assets for production
npm run dev-server       # Start Webpack dev server
```

### Symfony Console
```bash
php bin/console          # Show available commands
php bin/console cache:clear              # Clear application cache
php bin/console doctrine:migrations:migrate  # Run database migrations
php bin/console doctrine:schema:update --force  # Update database schema
php bin/console make:controller         # Generate new controller
php bin/console make:entity            # Generate/update entity
```

### Database Management
```bash
php bin/console doctrine:database:create     # Create database
php bin/console doctrine:migrations:diff     # Generate migration from schema changes
php bin/console doctrine:fixtures:load       # Load sample data (if fixtures exist)
```

### Docker Development Environment
```bash
docker-compose up -d      # Start all services (MariaDB, PHPMyAdmin, MailDev, RabbitMQ)
docker-compose down       # Stop all services
```

#### Docker Services
- **Database**: MariaDB on port 3306
- **PHPMyAdmin**: Database management on port 8081
- **MailDev**: Email testing on port 1080
- **RabbitMQ**: Message queue with management UI on port 15672

## Project Structure

### Controllers
- `HomeController`: Homepage rendering
- `ReservationController`: Booking system with hardcoded service offerings
- `PaymentController`: Stripe payment integration
- `SecurityController`: Authentication (login/register)
- `ProfileController`: User profile management
- `Admin/DashboardController`: EasyAdmin backend
- `WebhookController`: Payment webhook handling

### Key Features
- Multi-language support (French/English) via translation files
- File uploads for barber profile images
- Responsive design with custom Sass styling
- Calendar-based appointment scheduling
- Email confirmation system

### Asset Organization
- `assets/js/`: JavaScript files with Stimulus controllers
- `assets/styles/`: Sass files organized by feature (home, login, reservation, etc.)
- `public/build/`: Compiled assets (auto-generated)
- `public/uploads/`: User-uploaded files

## Development Notes

### Database Configuration
The project supports both MariaDB (via Docker) and PostgreSQL configurations. Database connection settings are managed through environment variables.

### Styling Approach
Custom Sass architecture with feature-based organization. Bootstrap is integrated but heavily customized with brand-specific styling.

### Payment Integration
Stripe is integrated for payment processing with webhook support for handling payment confirmations.

### Security
- CSRF protection enabled
- Password hashing via Symfony Security
- Role-based access control (ROLE_USER, admin roles)