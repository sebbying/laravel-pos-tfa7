# Laravel POS TFA7

This standalone Laravel 13 project implements the supplied TFA7 activity: create and edit customers and user accounts, validate forms, preserve old input, and prepare and display profile pictures. The TFA6 source was not supplied, so this is a runnable starting project with integration instructions below rather than a modification of your existing application.

## Requirements

- PHP 8.3 or later, Composer 2, and the usual Laravel PHP extensions.
- Enable GD, mbstring, fileinfo, PDO, and pdo_sqlite. For MySQL, also enable pdo_mysql.
- Intervention Image 3 is declared in composer.json. Confirm that your instructor approves this image package; the activity explicitly requires an instructor-approved package.
- No Node, npm, Vite, or external CSS/CDN is needed.

## Run locally

Extract the ZIP, open a terminal inside `laravel-pos-tfa7`, then run:

```bash
composer install
```

Create your local environment file:

```bash
# Windows Command Prompt
copy .env.example .env
# macOS / Linux / Git Bash (use this instead of copy)
cp .env.example .env
```

Then run:

```bash
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Open http://127.0.0.1:8000. A blank `database/database.sqlite` is included; Laravel migrations build its tables. If a Git checkout omits this ignored file, create it with the cross-platform command below before migrating:

```bash
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
```

To use MySQL, first create an empty database called `laravel_pos_tfa7`. Set `DB_CONNECTION=mysql`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` in `.env`, then run `php artisan config:clear` and `php artisan migrate --seed`. The migration and seeder files satisfy the assignment's database submission requirement; no SQL dump is necessary.

## Features and routes

| Method | Route | Purpose |
| --- | --- | --- |
| GET | /customers | Customer list |
| GET | /customers/create | Create customer form |
| POST | /customers | Validate and create customer |
| GET | /customers/{customer}/edit | Prefilled customer form |
| PUT/PATCH | /customers/{customer} | Validate and update customer |
| GET | /users | User Accounts list with avatars |
| GET | /users/create | Create user form |
| POST | /users | Validate and create user |
| GET | /users/{user}/edit | Prefilled user form and avatar upload |
| PUT/PATCH | /users/{user} | Validate and update user and avatar |

Customers require `full_name` and a valid `email`; phone and address are optional. Users require a unique `username` and `full_name`; email is optional, but must be valid if entered. Updates permit the user's existing username while rejecting another user's username. All forms use CSRF protection; edit forms use PUT method spoofing. Database writes use only validated fields. Blade escapes all displayed user input.

The assignment specifies account records, not login/password management. This standalone demo has no login screen; new user records have a nullable password and cannot authenticate until a separate password workflow is added. Use fictional records for a hosted classroom demo. If your TFA6 project has authentication, retain its existing passwords, routes, and access controls when integrating.

## Avatar processing

Edit a user to upload a JPG, PNG, or WebP image. Validation requires a real image no larger than 2048 KB (2 MiB) and no larger than 4096 × 4096 pixels. Intervention Image uses GD to crop and resize the picture to 256 × 256, then re-encode it as JPEG at quality 85. Uploaded SVG, animated GIF, and unsupported formats are rejected. The static SVG placeholder is bundled with the app, not uploaded by users.

`AvatarService` stores the prepared bytes through `Storage::disk('public')->put()`. Only a relative path such as `avatars/<uuid>.jpg` goes into `users.avatar`; the original file and original filename are not stored. Files live in `storage/app/public/avatars`. `php artisan storage:link` exposes them at `/storage/avatars`. Blank upload input preserves the existing image; replacement removes the previous image after the database write succeeds. A failed database write removes the new image. Missing avatar files also fall back to the placeholder.

A browser cannot restore file input values after validation fails. Text fields are restored using `old()` and the user is asked to select the picture again. If an image cannot be decoded, the form shows a validation error.

## Integrating with your existing TFA6 project

Back up your project and database first. Keep your existing framework version and bootstrap/config files; do not overwrite your entire project with this standalone scaffold.

1. Install the image dependency in your TFA6 project: `composer require intervention/image:^3.11`. Enable GD.
2. Copy the two controllers, two FormRequest classes, AvatarService, customer/user Blade views, the avatar placeholder, and CSS. Merge the layout and routes into your existing ones, preserving any authentication middleware.
3. Merge model fillable fields and methods. The form uses `full_name`; if TFA6 uses `name`, migrate/backfill or explicitly map that column. Keep existing authentication traits and fields. Make sure `username` has a database unique index and that email nullability matches the optional user email form.
4. Copy only `2026_11_07_000002_add_avatar_to_users_table.php` if your users/customers tables already exist. Do not copy the standalone create-table migrations over your existing migration history. If `avatar` already exists, do not run another add-column migration. Add any missing form columns through a separate migration appropriate to your existing schema.
5. If your existing users table requires a password or another field, retain its creation workflow and validation for those fields. This standalone user form only covers the fields required by this activity.
6. Run `php artisan migrate`, `php artisan storage:link`, and `php artisan optimize:clear`. Do not use `migrate:fresh` on a database with records you need.

## Verification

Automated feature tests are included for creating/editing records, validation and old input, unique usernames, avatar rejection, actual JPEG output/dimensions, replacement cleanup, placeholder display, and missing records.

```bash
composer test
```

The delivery environment did not have PHP or Composer, so these feature tests and full Laravel execution could not be run there. The package received static structure and archive checks only. Run the command above after installing dependencies. `composer.lock` and `vendor/` are not included because dependencies could not be resolved in that environment; `composer install` will resolve versions and generate the lock file. Commit that generated lock file to your repository.

Manual checks:

1. Create a customer with a valid email, then edit it and confirm the list changes.
2. Disable browser form validation temporarily in developer tools or submit via a request client to check missing/invalid required fields on the server. Check that text input is retained and errors display.
3. Create a user. Try another user with the same username; it must be rejected.
4. Edit the first user without changing its username; it must save.
5. Upload a valid picture; confirm a square avatar appears in User Accounts and the database contains only its relative path.
6. Reject a non-image or an image larger than 2 MB; the existing data/avatar must remain.
7. Replace the avatar and then edit text without a file; confirm replacement and preservation respectively.
8. Check another user without an avatar displays the placeholder.

## Hosting and submission

You must supply your actual GitHub repository URL and hosted application URL. No repository or hosting account was connected, so these links have not been created or fabricated.

1. Create a GitHub repository, commit this project's raw source, and push it. Include migrations, seeders, tests, README, and the generated composer.lock. Do not commit `.env`, `vendor`, real personal data, or uploaded images.
2. Choose PHP 8.3+ hosting with GD, Composer support (or upload dependencies built locally on a compatible PHP environment), a supported database, writable persistent storage, and public-disk linking support.
3. Upload the project and dependencies. Point the web document root to this project's `public` directory so `.env` and application source cannot be served. Configure HTTPS and a persistent writable database/storage location.
4. Create the server `.env`, set `APP_ENV=production`, `APP_DEBUG=false`, the exact HTTPS `APP_URL`, database settings, `SESSION_SECURE_COOKIE=true`, and a unique `APP_KEY`. Do not regenerate an existing application's key.
5. Run `php artisan migrate --force`, optionally `php artisan db:seed --force` for fictional demonstration records, `php artisan storage:link`, and `php artisan optimize`.
6. Make `storage` and `bootstrap/cache` writable by the PHP process. Set `upload_max_filesize=2M` or higher and `post_max_size` above 2M (for example 8M). Ensure PHP has sufficient memory for image decoding. Confirm avatar URLs work publicly.
7. Verify both create/edit flows and avatar upload on the live site. Submit the two actual links below.

GitHub repository URL: ____________________
Hosted application URL: ____________________

The demo focuses on account maintenance required by TFA7. Other POS modules such as products, sales, payments, and receipts are not reconstructed; retain those from TFA6 when integrating.

## References

- https://laravel.com/docs/13.x/validation
- https://laravel.com/docs/13.x/filesystem
- https://laravel.com/docs/13.x/releases
- https://image.intervention.io/v3/basics/configuration-drivers
- https://image.intervention.io/v3/basics/image-output
