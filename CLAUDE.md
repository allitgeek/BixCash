# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

BixCash is a "Shop to Earn" platform with a modern single-page website architecture:
- **Frontend**: Laravel Blade-based single-page website with Tailwind CSS and JavaScript
- **Backend**: Laravel 12 API server with MySQL database
- **Structure**: Unified Laravel application with both frontend views and API endpoints

## Development Commands

### Backend (Laravel API in `/backend` directory)

```bash
# Development server with full stack (server, queue, logs, vite)
cd backend && composer run dev

# Individual services
cd backend && php artisan serve          # Laravel development server
cd backend && php artisan queue:listen   # Queue worker
cd backend && php artisan pail           # Real-time log viewer
cd backend && npm run dev                 # Vite development server

# Database operations
cd backend && php artisan migrate        # Run migrations
cd backend && php artisan migrate:fresh --seed  # Fresh database with sample data
cd backend && php artisan db:seed        # Seed database only

# Testing and code quality
cd backend && composer run test          # PHPUnit tests (clears config first)
cd backend && ./vendor/bin/pint          # Laravel Pint code formatter

# Frontend assets
cd backend && npm run build              # Production build
cd backend && npm run dev                # Development with hot reload
```

### Frontend (Blade View)

The main frontend is served through Laravel's Blade templating:
- **Route**: `GET /` serves `welcome.blade.php`
- **View**: `resources/views/welcome.blade.php` (single-page application)
- **Assets**: Compiled via Vite from `resources/css/app.css`
- **Scripts**: Inline JavaScript for API integration and UI interactions

## Architecture

### Database Schema

### Core Content Entities
- **Slides**: Hero carousel content with scheduling and targeting features
- **Categories**: Brand categorization with activation status and metadata
- **Brands**: Partner brand information with commission rates and partner associations

### Admin System Entities (Phase 2)
- **Roles**: Role-based permission system (super_admin, admin, moderator, partner, customer)
- **Users**: Extended with role relationships, activity tracking, and status management
- **AdminProfile**: Admin-specific data (department, permissions, login history)
- **CustomerProfile**: Customer data (earnings, referrals, verification status)
- **PartnerProfile**: Business partner information (approvals, commission rates, sales data)

### API Endpoints

#### Public API Endpoints (`/backend/routes/api.php`)
- `GET /api/slides` - Hero carousel data
- `GET /api/categories` - Brand categories with icons
- `GET /api/brands` - Brand information and logos

#### Admin Panel Routes (`/backend/routes/admin.php`)
- `GET /admin/login` - Admin login page
- `POST /admin/login` - Admin authentication
- `GET /admin/dashboard` - Main admin dashboard (protected)
- `POST /admin/logout` - Admin logout
- **Content Management**: `/admin/slides`, `/admin/categories`, `/admin/brands` (CRUD operations)
- **User Management**: `/admin/users` (super admin only)
- **Analytics**: `/admin/analytics`, `/admin/reports` (view permissions required)

### Models and Controllers

#### Core Models
- **Slide**, **Category**, **Brand**: Enhanced with admin features, relationships, and scopes
- **User**: Extended with role-based access control and profile relationships
- **Role**: Permission-based access control system

#### Admin Models (Phase 2)
- **AdminProfile**, **CustomerProfile**, **PartnerProfile**: User-specific profile data
- All models include proper relationships, fillable attributes, and helper methods

#### Controllers
- **API Controllers**: Located in `App\Http\Controllers\Api\` (public endpoints)
- **Admin Controllers**: Located in `App\Http\Controllers\Admin\` (protected admin functionality)
  - `AuthController`: Admin authentication and session management
  - `DashboardController`: Admin dashboard and analytics

### Frontend-Backend Integration

The Blade frontend fetches data from Laravel API endpoints via JavaScript. The main view handles:
- Dynamic hero slideshow content from `/api/slides`
- Category and brand data rendering from `/api/categories` and `/api/brands`
- Swiper.js carousels for hero slides and brand showcase
- Smart image loading for promotion brand logos

## Database Configuration

MySQL database setup:
- **Database**: `bixcash_db`
- **User**: `bixcash` (configured in `.env`)
- **Connection**: Standard Laravel database configuration
- **Storage**: Database sessions, cache, and queue

## Asset Management

### Backend Assets
- Vite configuration for Laravel asset compilation
- Tailwind CSS with custom BixCash color variables
- Main stylesheet: `resources/css/app.css`
- Assets compiled and served through Laravel

### Image Assets
- **Mockups**: `/backend/mockups/` directory (design references)
- **Brand Logos**: `/backend/public/images/brands/` (dynamically loaded)
- **Category Icons**: `/backend/public/images/categories/` (UI elements)
- **Dashboard Elements**: `/backend/public/images/elements/` (UI components)
- **Promotions**: `/backend/public/images/promotions/` (brand promotion logos)

## Development Workflow

1. **Backend Development**: Work in `/backend` directory using Laravel conventions
2. **Frontend Updates**: Modify `resources/views/welcome.blade.php` and `resources/css/app.css`
3. **Database Changes**: Use Laravel migrations and seeders
4. **API Development**: Add controllers in `Api` namespace, update routes
5. **Image Management**: Add brand/promotion images to respective `/public/images/` folders

## Website Sections

The single-page website includes the following sections in order:

1. **Header**: Navigation bar with BixCash logo
2. **Hero Slider**: Dynamic carousel powered by Swiper.js (API: `/api/slides`)
3. **Brands Section**: Category icons and brand carousel (API: `/api/categories`, `/api/brands`)
4. **How It Works**: Two-column explanation for customers and vendors
5. **Why BixCash**: Benefits breakdown for both user types
6. **Customer Dashboard**: Interactive mockup with monitor interface and navigation icons
7. **Promotions**: 4x2 grid of brand promotions with smart image loading

## Current Features

### Navigation Icons (Customer Dashboard)
- **Cash Back**: Uses exact image from `/images/elements/dashboard icons.jpg`
- **Wallet**: CSS-created 3D wallet icon with flap
- **Transaction**: Dollar sign with document lines
- **Receipt**: Paper with perforated edges and text lines
- **Withdrawal**: Stacked money bills with down arrow

### Promotions System
- **Smart Loading**: Tries `.png` first, falls back to `.jpg`, shows placeholder if neither exists
- **Easy Management**: Drop brand images in `/backend/public/images/promotions/`
- **Responsive Design**: 4 columns → 2 columns → 1 column based on screen size
- **Hover Effects**: Cards lift and enhance on hover

## Admin Panel System (Phase 2)

### Authentication & Security
- **Middleware**: AdminAuth and RolePermission for secure access control
- **Session Management**: Secure login/logout with session regeneration
- **Role-Based Access**: 5-tier role system (super_admin, admin, moderator, partner, customer)
- **Admin Dashboard**: Statistics overview with user management and recent activity

### Demo Accounts
- **Super Admin**: admin@bixcash.com / admin123
- **Manager**: manager@bixcash.com / manager123

### Permission System
Roles are defined with JSON permissions for granular access control:
- `super_admin`: Full system access
- `admin`: User and content management
- `moderator`: Content management only
- `partner`: Own profile and sales data
- `customer`: Profile and transaction history

### Security Features
- Account status validation (active/inactive users)
- Admin privilege verification for panel access
- Last login tracking and display
- Secure credential validation with proper error messages
- Session invalidation on logout

## Image Management

### Adding Promotional Brand Images
```
/backend/public/images/promotions/
├── saya.png (or saya.jpg)
├── junaid-jamshed.png
├── gul-ahmed.png
├── bata.png
├── tayto.png
├── kfc.png
├── joyland.png
└── sapphire.png
```

- Images automatically replace placeholders when present
- Supports PNG, JPG, JPEG formats
- Recommended size: 150-300px width, 80-120px height
- PNG with transparency preferred for logos

## Testing

- PHPUnit configured for backend testing
- Tests located in `/backend/tests/` directory
- Use `composer run test` for full test suite
- Database configuration cleared before each test run