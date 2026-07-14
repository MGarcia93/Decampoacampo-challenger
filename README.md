# Challenge PHP — Gestión de Productos

Challenger para Decampoacampo

## Estructura general

```text
challenge/
├── backend/
│   ├── database/
│   │   └── migrations/
│   ├── docker/
│   │   └── nginx/
│   ├── public/
│   │   └── index.php
│   ├── src/
│   ├── .env.example
│   ├── composer.json
│   └── Dockerfile
├── frontend/
├── docker-compose.yml
├── .env.example
├── .gitignore
└── README.md
```

## Requisitos

Para ejecutar el proyecto es necesario tener instalado:

* Docker
* Docker Compose

## Configuración inicial

### 1. Crear el archivo de entorno principal

Desde la raíz del proyecto:

```bash
cp .env.example .env
```

Este archivo contiene la configuración utilizada por Docker Compose, como el puerto expuesto de MySQL y las credenciales iniciales de la base de datos.

### 2. Crear el archivo de entorno del backend

```bash
cp backend/.env.example backend/.env
```

Ejemplo de `backend/.env`:

```env
APP_ENV=local
APP_DEBUG=true

DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=challenge
DB_USERNAME=challenge
DB_PASSWORD=challenge

PRECIO_USD=1000
```

Dentro de la red de Docker, el host de la base de datos es el nombre del servicio:

```text
mysql
```

No debe utilizarse `localhost` para conectar el contenedor PHP con MySQL.

`PRECIO_USD` representa el valor en pesos argentinos de un dólar y se utiliza para calcular el precio convertido de cada producto.

## Levantar el entorno

Construir las imágenes e iniciar los contenedores:

```bash
docker compose up -d --build
```

Instalar las dependencias de Composer dentro del contenedor PHP:

```bash
docker compose run --rm backend-php composer install
```

Composer solamente instala y genera el autoload PSR-4 utilizado por la aplicación.

Al crear por primera vez el volumen de MySQL, Docker ejecuta automáticamente la migración de esquema y el seed inicial desde `backend/database`.


## Backend

El backend utiliza:

* PHP 8.5
* PHP-FPM

## Comandos útiles

Ver el estado de los contenedores:

```bash
docker compose ps
```

Ver los logs:

```bash
docker compose logs -f
```

Ver únicamente los logs del backend PHP:

```bash
docker compose logs -f backend-php
```

Acceder al contenedor PHP:

```bash
docker compose exec backend-php sh
```

Regenerar el autoload de Composer:

```bash
docker compose run --rm backend-php composer dump-autoload
```

## Apagar el entorno

Detener y eliminar los contenedores:

```bash
docker compose down
```

Detener los contenedores y eliminar también el volumen persistente de MySQL:

```bash
docker compose down -v
```

El segundo comando elimina todos los datos almacenados en la base de datos local.

Al volver a ejecutar `docker compose up -d --build`, MySQL inicializará nuevamente el esquema y los datos de ejemplo.

## Tests

El proyecto utiliza Pest como herramienta de testing para el backend.

```bash
docker compose run --rm backend-php composer test
```
