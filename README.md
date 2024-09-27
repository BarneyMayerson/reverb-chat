## Launching in local environment

Clone the project

```sh
git clone https://github.com/BarneyMayerson/reverb-chat.git
cd reverb-chat
```

Install PHP dependencies:

```sh
composer install
```

Install NPM dependencies:

```sh
npm ci
```

Build assets:

```sh
npm run dev
```

Setup configuration:

```sh
cp .env.example .env
```

Generate application key:

```sh
php artisan key:generate
```

Create an SQLite database. You can also use another database (MySQL, Postgres), simply update your configuration accordingly.

```sh
touch database/database.sqlite
```

Run database migrations:

```sh
php artisan migrate
```

Run database seeder:

```sh
php artisan db:seed
```

Build assets:

```sh
npm run dev
```

Start reverb

```sh
php artisan reverb:start
```
