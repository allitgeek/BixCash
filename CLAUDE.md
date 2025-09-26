# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

BixCash is a "Shop to Earn" platform with a hybrid architecture:
- **Frontend**: Static HTML landing page with Tailwind CSS and vanilla JavaScript
- **Backend**: Laravel 12 API server with MySQL database
- **Structure**: Frontend and backend are separate but related components

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

### Frontend (Static HTML)

The main frontend is a single `index.html` file. For development:
- Open directly in browser or use a simple HTTP server
- Uses CDN Tailwind CSS (no build process required)
- Custom fonts loaded from `./fonts/` directory

## Architecture

### Database Schema

Three main entities with established relationships:
- **Slides**: Hero carousel content (`slides` table)
- **Categories**: Brand categorization (`categories` table)
- **Brands**: Partner brand information (`brands` table)

### API Endpoints

RESTful API structure in `/backend/routes/api.php`:
- `GET /api/slides` - Hero carousel data
- `GET /api/categories` - Brand categories with icons
- `GET /api/brands` - Brand information and logos

### Models and Controllers

Laravel API-only architecture:
- Models: `Slide`, `Category`, `Brand` (basic Eloquent models)
- Controllers: Located in `App\Http\Controllers\Api\` namespace
- All controllers extend base `Controller` class

### Frontend-Backend Integration

The static HTML frontend fetches data from Laravel API endpoints. JavaScript in `index.html` handles:
- Dynamic slideshow content from `/api/slides`
- Category and brand data rendering
- Carousel navigation and smooth scrolling

## Database Configuration

MySQL database setup:
- **Database**: `bixcash_db`
- **User**: `bixcash` (configured in `.env`)
- **Connection**: Standard Laravel database configuration
- **Storage**: Database sessions, cache, and queue

## Asset Management

### Backend Assets
- Vite configuration for Laravel asset compilation
- Tailwind CSS 4.0 integration via Vite plugin
- Assets located in `resources/css/` and `resources/js/`

### Frontend Assets
- Mockup images in `/mockups/` directory
- Custom fonts in `/fonts/` directory (referenced but files not present)
- Static logo and brand assets expected in `/mockups/`

## Development Workflow

1. **Backend Development**: Work in `/backend` directory using Laravel conventions
2. **Frontend Updates**: Modify `index.html` directly or work within Laravel's Blade views
3. **Database Changes**: Use Laravel migrations and seeders
4. **API Development**: Add controllers in `Api` namespace, update routes

## Known Issues

- Missing logo and brand icon assets (referenced but files not provided)
- Custom font files referenced but not present in `/fonts/` directory
- Frontend expects specific image assets from mockups

## Testing

- PHPUnit configured for backend testing
- Tests located in `/backend/tests/` directory
- Use `composer run test` for full test suite
- Database configuration cleared before each test run