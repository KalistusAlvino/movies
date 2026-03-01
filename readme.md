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
│   ├── Middleware/
│   │   └── SetLocale.php                - Middleware untuk set language
│   └── Requests/
│       ├── SearchMovieRequest.php      - Validasi search movie
│       ├── GetEpisodeRequest.php       - Validasi get episode
│       ├── GetByImdbRequest.php       - Validasi get by IMDb ID
│       ├── AddFavoriteRequest.php      - Validasi add favorite
│       └── CheckFavoriteRequest.php    - Validasi check favorite status
├── Models/
│   ├── User.php                         - User model dengan relasi favorites
│   └── FavoriteMovie.php               - Favorite movie model
├── Services/
│   └── OmdbService.php                 - Service untuk OMDb API integration
└── Helpers/
    └── ApiResponse.php                  - Helper untuk API response standard
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
public/
└── js/
    ├── api.js                          - API wrapper dengan fetchApi dan window.api
    ├── movies.js                        - Movie list logic (search, infinite scroll, lazy loading)
    ├── movie-detail.js                  - Movie detail logic
    ├── favorites.js                     - Favorites management logic
    └── modules/
        ├── FavoriteManager.js             - Favorite management class
        └── UIManager.js                  - UI utilities class
```

### Data Flow
1. User Login → AuthController → Validasi credentials → Session
2. Search Movie → MovieController → FormRequest validation → OmdbService → OMDb API → Display results (infinite scroll)
3. Search Episode → MovieController → GetEpisodeRequest validation → OmdbService → OMDb API → Display episode card
4. View Detail → MovieController → GetByImdbRequest validation → OmdbService → OMDb API → Display full details
5. Add Favorite → FavoriteController → AddFavoriteRequest validation → Store to database
6. Check Favorite Status → FavoriteController → CheckFavoriteRequest validation → Return favorite status
7. Remove Favorite → FavoriteController → Delete from database
8. View Favorites → FavoriteController → Query database → Display list
9. Language Switch → LanguageController → Update session → Refresh page

### Key Features Implementation

#### Authentication
- Custom login dengan username dan password
- Session-based authentication
- Middleware protection untuk semua routes kecuali login
- Auto-redirect ke login jika belum authenticated

#### OMDb API Integration
- Search by title dengan pagination support
- Filter by type (movie, series, episode)
- Episode search dengan Season dan Episode number
- Get detail by IMDb ID
- Error handling untuk API responses
- Service layer separation (OmdbService)

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
- Real-time favorite status check
- FavoriteManager class untuk reusable logic

#### JavaScript Architecture
- API wrapper dengan window.api object (GET, POST, PUT, DELETE)
- Module-based JavaScript structure
- FavoriteManager class untuk state management
- UIManager class untuk UI utilities
- CSRF token handling otomatis
- Error handling terpusat

## Installation & Setup

### Prerequisites
- PHP >= 7.1.3
- Composer
- MySQL
- Web server (Apache/Nginx) atau PHP built-in server

### Installation Steps

1. Clone atau extract project

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

8. Access aplikasi di: `https://bookingroom.my.id/`

## API Configuration

Untuk menggunakan OMDb API dengan full features:

1. Daftar gratis di [OMDb API](http://www.omdbapi.com/)
2. Dapatkan API Key
3. Update `.env` file:
```
OMDB_API_KEY=your_api_key_here
```

## Demo URL

### Live Demo
Demo aplikasi dapat diakses di:
**https://bookingroom.my.id/**


## Features

### Core Features
- ✅ Login system dengan custom authentication
- ✅ Movie search dengan OMDb API
- ✅ Episode search dengan Season dan Episode number
- ✅ Movie detail view
- ✅ Favorite movies management (add, view, delete)
- ✅ Multi-language support (English/Indonesian)
- ✅ Infinite scroll untuk movie list
- ✅ Lazy loading untuk images
- ✅ Responsive design
- ✅ Modern UI/UX dengan animations
- ✅ Error handling dan validation
- ✅ Form Request validation untuk semua endpoints
- ✅ Service layer untuk API integration

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
