# Disposer

German User-Video-Guide:  

[![Video-Guide für Nutzer](https://img.youtube.com/vi/dYo23MRtl-w/0.jpg)](https://www.youtube.com/watch?v=dYo23MRtl-w)

## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/)
2. Run `docker-compose build --pull --no-cache` to build fresh images
3. Run `docker-compose up` (the logs will be displayed in the current shell)
4. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
5. Run `docker-compose down --remove-orphans` to stop the Docker containers.

## Fist Start of Application

1. Create Schema by using Symfony-CLI inside PHP-Container: 
``docker exec -it disposer_php_1 php bin/console doctrine:schema:create``
2. Install JS-Dependencies: ``npm install``
3. Build JS & CSS dependencies with Webpack: ``npm run build``

## Deployment

### Build
Requires ``composer`` and ``node`` installed on Build-Server.

1. Install PHP-Dependencies  by running ``composer install`` in Root Directory
2. Install JS-Dependencies by running ``npm install`` in Root Directory
3. Build JS and Css with Webpack by running ``npm run build`` in Root Directory
4. Export DB-Create Scripts if necessary with command ``php bin/console doctrine:schema:create --dump-sql > schema.sql``
5. Export Migrations with command ``php bin/console doctrine:migrations:migrate --write-sql``
6. Add ``.env``-File with Configurations for you Target-System - clone and manipulate ``.env``-File from Repository
7. Clear caches by running ``php bin/console cache:clear --env=prod``
   1. alternatively clear ``var/cache/prod``-Directory

### Prepare Target-System

1. Move Files to Target-System
2. Create Schema with Dump if required
3. Run Migrations if required
4. Done

## Docs

1. [Build options](docs/build.md)
2. [Using Symfony Docker with an existing project](docs/existing-project.md)
3. [Support for extra services](docs/extra-services.md)
4. [Deploying in production](docs/production.md)
5. [Installing Xdebug](docs/xdebug.md)
6. [Using a Makefile](docs/makefile.md)
7. [Troubleshooting](docs/troubleshooting.md)

