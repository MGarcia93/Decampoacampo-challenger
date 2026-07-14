# Challenge PHP — Gestión de Productos

## Stack

* PHP 8.5 nativo
* PHP-FPM + Nginx
* MySQL 8.4
* PDO
* Composer para autoload PSR-4
* Pest para tests

## Configuración

```bash
cp .env.example .env
cp backend/.env.example backend/.env
```

## Instalación

```bash
docker compose up -d --build
docker compose run --rm backend-php composer install
```

## Base de datos

La base de datos se inicializa automáticamente desde:

```text
backend/database/migrations/
backend/database/seeders/
```

Para reiniciar los datos:

```bash
docker compose down -v
docker compose up -d --build
```

## API

Base URL:

```text
http://localhost:8080
```

Endpoints:

```text
GET    /productos
GET    /productos/{id}
POST   /productos
PUT    /productos/{id}
DELETE /productos/{id}
```

## Payload

Payload utilizado para crear y actualizar productos:

```json
{
  "nombre": "Yerba mate",
  "descripcion": "Yerba mate orgánica",
  "precio": 3500
}
```

## Respuesta

```json
{
  "data": {
    "id": 1,
    "nombre": "Yerba mate",
    "descripcion": "Yerba mate orgánica",
    "precio": 3500,
    "precio_usd": 3.5,
    "created_at": "2026-07-14 18:00:00",
    "updated_at": "2026-07-14 18:00:00"
  }
}
```

Ejemplo de error:

```json
{
  "error": "El nombre del producto es obligatorio",
  "code": 422
}
```

## Tests

```bash
docker compose run --rm backend-php composer test
```

Los tests se encuentran organizados en:

```text
backend/tests/Unit/
backend/tests/Feature/
```

## Arquitectura

El backend sigue una organización MVC pragmática:

```text
Route → Controller → Service → Repository → MySQL
```

La conversión a dólares se realiza en la capa de aplicación utilizando la variable `PRECIO_USD`.
