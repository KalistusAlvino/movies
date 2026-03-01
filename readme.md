# Movie Application - Laravel 5

Aplikasi Movie berbasis Laravel 5 yang memungkinkan pengguna untuk mencari film melalui OMDb API, melihat detail film, dan mengelola film favorit.

## Libraries Used

### Backend Libraries
- **Laravel Framework 5.8** - PHP framework untuk membangun aplikasi
- **Laravel Tinker 1.0** - REPL untuk Laravel
- **Guzzle HTTP Client** (included in Laravel) - Untuk membuat HTTP requests ke OMDb API

### Frontend Technologies
- **Blade Templates** - Template engine bawaan Laravel
- **Vanilla JavaScript** - Untuk infinite scroll, lazy loading, dan interaksi
- **CSS3** - Styling dengan gradient backgrounds, animations, dan responsive design
- **Intersection Observer API** - Untuk lazy loading gambar

## Architecture

### Architecture Pattern
- **MVC (Model-View-Controller)** - Pattern utama yang digunakan
- **Repository Pattern** - Tidak digunakan (langsung menggunakan Eloquent ORM)
- **Service Layer** - Logic business langsung di Controller

### Project Structure
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php          - Handle login/logout
│   │   ├── MovieController.php          - OMDb API integration, search, detail
│   │   ├── FavoriteController.php       - Manage favorite movies
│   │   └── LanguageController.php       - Handle language switching
│   └── Middleware/
│       └── SetLocale.php                - Middleware untuk set language
├── Models/
│   ├── User.php                         - User model dengan relasi favorites
│   └── FavoriteMovie.php               - Favorite movie model
resources/
├── views/
│   ├── layouts/
│   │   └── app.blade.php               - Main layout
│   ├── auth/
│   │   └── login.blade.php             - Login page
│   ├── movies/
│   │   ├── index.blade.php             - Movie list with infinite scroll
│   │   └── show.blade.php              - Movie detail page
│   └── favorites/
│       └── index.blade.php             - Favorite movies list
└── lang/
    ├── en/
    │   └── messages.php                 - English translations
    └── id/
        └── messages.php                 - Indonesian translations
database/
├── migrations/
│   ├── 2014_10_12_000000_create_users_table.php
│   └── 2024_01_01_000000_create_favorite_movies_table.php
└── seeds/
    └── TestUserSeeder.php               - Seeder untuk test user
```

### Data Flow
1. User Login → AuthController → Validasi credentials → Session
2. Search Movie → MovieController → OMDb API → Display results (infinite scroll)
3. View Detail → MovieController → OMDb API → Display full details
4. Add Favorite → FavoriteController → Store to database
5. View Favorites → FavoriteController → Query database → Display list
6. Language Switch → LanguageController → Update session → Refresh page

### Key Features Implementation

#### Authentication
- Custom login dengan username dan password
- Session-based authentication
- Middleware protection untuk semua routes kecuali login
- Auto-redirect ke login jika belum authenticated

#### OMDb API Integration
- Search by title dengan pagination support
- Filter by type (movie, series, episode)
- Get detail by IMDb ID
- Error handling untuk API responses

#### Multi-Language Support
- English (EN) sebagai default language
- Indonesian (ID) sebagai alternate language
- Session-based language storage
- Language switcher di navbar
- Translation files di `resources/lang/`

#### Infinite Scroll
- JavaScript-based pagination
- "Load More" button untuk load halaman berikutnya
- Track current page dan total results
- Auto-hide button jika semua data sudah loaded

#### Lazy Loading
- Intersection Observer API
- Placeholder display sebelum gambar loaded
- Smooth fade-in transition saat gambar loaded
- Optimized untuk performance

#### Favorite Movies
- Add/remove dari list movie atau detail page
- Persistent storage di database
- Visual indicator (gold heart) untuk favorited movies
- Delete dengan confirmation modal

## Installation & Setup

### Prerequisites
- PHP >= 7.1.3
- Composer
- MySQL
- Web server (Apache/Nginx) atau PHP built-in server

### Installation Steps

1. Clone atau extract project:
```bash
cd d:\Coding\test_lamar_kerja\movie
```

2. Install dependencies:
```bash
composer install
```

3. Setup environment:
```bash
copy .env.example .env
php artisan key:generate
```

4. Configure database di `.env`:
```
DB_DATABASE=movie_app
DB_USERNAME=root
DB_PASSWORD=your_password
```

5. Run migrations:
```bash
php artisan migrate
```

6. Run seeder:
```bash
php artisan db:seed
```

7. Start development server:
```bash
php artisan serve
```

8. Access aplikasi di: `http://localhost:8000`

### Login Credentials
- **Username:** aldmic
- **Password:** 123abc123

## Screenshots

### Login Page
![Login Page](screenshot_login.png)

Login page dengan username dan password fields. Desain modern dengan gradient background.

### Movie List Page
![Movie List Page](screenshot_movies.png)

List movie dengan infinite scroll, lazy loading untuk gambar, dan filter by type.

### Movie Detail Page
![Movie Detail Page](screenshot_detail.png)

Detail movie lengkap dengan poster, plot, cast, dan tombol add to favorite.

### Favorite Movies Page
![Favorite Movies Page](screenshot_favorites.png)

List favorite movies dengan opsi delete dan confirmation modal.

### Language Switch
![Language Switch](screenshot_language.png)

Bahasa dapat di-switch antara English dan Indonesian melalui tombol di navbar.

## API Configuration

Untuk menggunakan OMDb API dengan full features:

1. Daftar gratis di [OMDb API](http://www.omdbapi.com/)
2. Dapatkan API Key
3. Update `.env` file:
```
OMDB_API_KEY=your_api_key_here
```

Default key `trilogy` akan tetap berfungsi dengan batasan tertentu.

## Demo URL

### Live Demo
Demo aplikasi dapat diakses di:
**http://your-demo-url.com**

### Source Code
Source code tersedia di:
**https://drive.google.com/your-source-code-link**

## Features

### Core Features
- ✅ Login system dengan custom authentication
- ✅ Movie search dengan OMDb API
- ✅ Movie detail view
- ✅ Favorite movies management (add, view, delete)
- ✅ Multi-language support (English/Indonesian)
- ✅ Infinite scroll untuk movie list
- ✅ Lazy loading untuk images
- ✅ Responsive design
- ✅ Modern UI/UX dengan animations
- ✅ Error handling dan validation

### Technical Highlights
- RESTful API integration
- Session-based authentication
- CSRF protection
- Database relationships
- Middleware for authentication dan language
- Blade templating
- AJAX requests untuk dynamic content
- Intersection Observer API untuk lazy loading

## Security Implementation

- Password hashing dengan Laravel's bcrypt
- CSRF token protection untuk semua form submissions
- Session-based authentication
- Middleware untuk protected routes
- SQL injection prevention dengan Eloquent ORM
- XSS prevention dengan Blade escaping

## Browser Compatibility

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## License

This project is created for test purposes only.

---

**Note:** Please update the screenshot URLs and demo URLs with your actual deployment URLs.
