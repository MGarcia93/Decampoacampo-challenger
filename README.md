# Challenge PHP — Gestión de Productos

API REST y frontend web para gestionar un catálogo de productos. El proyecto permite listar, crear, editar y eliminar productos, mostrando el precio en pesos argentinos y su conversión a dólares mediante la variable de entorno `PRECIO_USD`.

## Stack

- PHP 8.5 nativo
- PHP-FPM + Nginx
- MySQL 8.4
- PDO
- Composer con autoload PSR-4
- Pest para tests
- HTML, CSS y JavaScript puro para el frontend
- Docker Compose

## Requisitos

- Docker
- Docker Compose

No es necesario instalar PHP, MySQL ni Nginx localmente: todo corre dentro de contenedores.

## Configuración

Copiar los archivos de entorno:

```bash
cp .env.example .env
cp backend/.env.example backend/.env
```

En `backend/.env` se configura el valor del dólar utilizado para calcular `precio_usd`:

```env
PRECIO_USD=1000
```

También se configuran las credenciales de conexión a MySQL usadas por el backend.

## Instalación

Levantar los contenedores:

```bash
docker compose up -d --build
```

Instalar dependencias de PHP:

```bash
docker compose run --rm backend-php composer install
```

Servicios disponibles:

| Servicio | URL |
| --- | --- |
| Frontend | http://localhost:5173 |
| API Backend | http://localhost:8080 |
| MySQL | Puerto configurado en `.env` |

## Base de datos

La base de datos se inicializa automáticamente al crear el contenedor de MySQL.


```text
backend/database/migrations/create.sql
backend/database/seeders/seed.sql
```

Para reiniciar la base de datos desde cero:

```bash
docker compose down -v
docker compose up -d --build
```

## API Backend

Base URL:

```text
http://localhost:8080
```

Endpoints disponibles:

| Método | Endpoint | Descripción |
| --- | --- | --- |
| GET | `/productos` | Lista todos los productos |
| GET | `/productos/{id}` | Obtiene un producto por ID |
| POST | `/productos` | Crea un producto |
| PUT | `/productos/{id}` | Actualiza un producto |
| DELETE | `/productos/{id}` | Elimina un producto |

### Payload para crear o actualizar

```json
{
  "nombre": "Yerba mate",
  "descripcion": "Yerba mate orgánica",
  "precio": 3500
}
```

### Respuesta de ejemplo

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

### Respuesta de error

```json
{
  "error": "El nombre del producto es obligatorio",
  "code": 422
}
```

## Frontend

El frontend se ejecuta en:

```text
http://localhost:5173
```

Desde la interfaz web se puede:

- listar productos en una tabla;
- ver el precio en pesos argentinos y en dólares;
- agregar productos mediante un formulario;
- editar productos existentes;
- eliminar productos con confirmación;
- ver mensajes de éxito o error según la respuesta de la API.

El frontend consume la API usando `fetch` desde JavaScript puro. La URL base de la API se define en:

```text
frontend/config.js
```

Valor por defecto:

```js
export const config = {
  url_api: 'http://localhost:8080',
};
```

## Cómo probar manualmente

1. Levantar el proyecto con Docker:

   ```bash
   docker compose up -d --build
   ```

2. Abrir el frontend:

   ```text
   http://localhost:5173
   ```

3. Verificar que se listen los productos cargados por el seed.

4. Crear un producto desde el botón **Agregar Producto**.

5. Editar el producto creado.

6. Eliminar el producto y confirmar que desaparece del listado.

7. Opcionalmente, probar la API directamente en:

   ```text
   http://localhost:8080/productos
   ```

## Tests

Ejecutar la suite de tests:

```bash
docker compose run --rm backend-php composer test
```

Los tests están organizados en:

```text
backend/tests/Unit/
backend/tests/Feature/
```

## Arquitectura

El backend sigue una organización MVC pragmática, separando responsabilidades entre rutas, controladores, servicios y repositorios:

```text
Route → Controller → Service → Repository → MySQL
```


El frontend está organizado por responsabilidades:

```text
frontend/js/api.js          # Comunicación con la API
frontend/js/product/        # Lógica de productos y acciones de pantalla
frontend/js/ui/             # Componentes simples de UI
frontend/css/style.css      # Estilos de la interfaz
```

## Referencia visual

El frontend toma como referencia visual una interfaz tipo dashboard/listado administrativo:

https://dribbble.com/shots/16308515-Ticker-Cardiology-Patient-List

