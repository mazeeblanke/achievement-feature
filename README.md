# Back-end Developer Test

## Requirements
 - PHP == 8.2
 - Docker Desktop
 - Composer 

## Installation

1. Clone project and cd into project e.g `git clone https://github.com/mazeeblanke/achievement-feature.git && cd achievement-feature`

2. Copy `.env.example` to `.env` i.e `cp .env.example .env`

3. RUN `composer install`

4. RUN `sail up`

5. RUN `php artisan key:generate`

6. RUN `php artisan migrate --seed`

7. Application now running on `http://localhost:3000/`

8. Done


## Tests

Tests are found in the test directory.

RUN `php artisan test`

RUN `./vendor/bin/php-cs-fixer fix` to fix code

RUN `./vendor/bin/phpstan analyse --memory-limit=2G` for static analysis
