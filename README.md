# frameworkX

A minimal PHP MVC framework for rapid web application development. This framework provides a simple structure for routing, controllers, middleware, and views.

## Features

- MVC architecture (Controllers, Views)
- Route grouping (API & Web)
- Middleware support (Auth, RBAC, Throttle)
- Simple autoloading
- Error handling
- Helper functions

## Directory Structure

```
config.php           # Main configuration
index.php            # Entry point
controllers/         # Controllers (api/web)
core/                # Core framework files (router, autoload, cors)
database/            # Database connection
helpers/             # Helper functions, response, view
middlewares/         # Middleware classes
public/              # Public assets
routes/              # Route definitions (api.php, web.php)
services/            # Error and request handlers
views/               # View templates (layout, components, error, pages)
```

## Getting Started

### 1. Requirements

- PHP 7.4+

### 2. Installation

Clone the repository:

```sh
git clone https://github.com/NahidHassanOfficial/frameworkX.git
cd frameworkX
```

### 3. Configure

Edit `config.php` and `database/database.php` as needed for your environment.

### 4. Define Routes

- API routes: `routes/api.php`
- Web routes: `routes/web.php`

Example route:

```php
$apiGroup = [
    'prefix' => '/api',
    'routes' => [
        ['GET', '/login', 'AuthenticationController@login', ['ThrottleMiddleware']],
    ]
];
```

### 5. Create Controllers

Controllers go in `controllers/api/` or `controllers/web/`.

Example:

```php
class HomeController {
    public function index() {
        // ...
    }
}
```

### 6. Use Middleware

Add middleware to routes as an array (e.g., `['AuthMiddleware']`).

### 7. Views, Layouts, and Components

**View Structure:**

- **Layouts:**
  - Main layout: `views/layout.php` (contains the HTML structure, header, footer, and a `<?= $content ?>` placeholder for page content)
- **Pages:**
  - Individual pages: `views/pages/` (e.g., `Home.php`, `About.php`)
- **Components:**
  - Reusable UI parts: `views/components/` (e.g., `Child.php`)

**How it works:**

1. When you render a page, the content is injected into the layout:
   ```php
   // In a controller:
   View::render('pages/Home', ['user' => $user]);
   ```
2. Inside `views/layout.php`:
   ```php
   <!DOCTYPE html>
   <html>
   <head>
       <title>My App</title>
   </head>
   <body>
       <header>...</header>
       <main>
           <?= $content ?> <!-- Page content goes here -->
       </main>
       <footer>...</footer>
   </body>
   </html>
   ```
3. Example page (`views/pages/Home.php`):
   ```php
   <p>This is the Home page</p>
   <?= $user->fname . ' ' . $user->lname ?>
   <?php View::render('components/Child', [], null); ?>
   <img src="/public/icon.png" alt="Test">
   ```
4. Example component (`views/components/Child.php`):
   ```php
   <p style="color: green; font-size: 20px;">This is the Child page</p>
   ```

**Error Views:**

- Custom error pages are in `views/error/` (e.g., `404.php`, `503.php`).

### 8. Run the App

Use PHP's built-in server for development:

```sh
php -S localhost:8000
```

Also you can put the project inside wamp/xampp folder. and access without any command running.

Visit [http://localhost:8000](http://localhost:8000)
