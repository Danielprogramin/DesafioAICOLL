# Sistema de Gestión de Empresas - Prueba Técnica

Este proyecto implementa un sistema de gestión de empresas utilizando Laravel y PHP, cumpliendo con todos los requerimientos técnicos y funcionales especificados.

## Características Implementadas

### ✅ Funcionalidades Requeridas
- [x] Agregar nuevas empresas
- [x] Actualizar los datos de una empresa
- [x] Consultar las empresas por NIT
- [x] Consultar todas las empresas registradas
- [x] Borrar las empresas con estado inactivo

### ✅ Requerimientos Técnicos
- [x] Estado 'Activo' por defecto al crear empresas
- [x] NIT único (no duplicado)
- [x] Validación de datos de entrada (tipo, valores permitidos, longitud)
- [x] Implementado con Laravel y PHP
- [x] Base de datos SQLite incluida

### ✅ Campos de la Tabla Empresas
- [x] `nit` (string, único, requerido)
- [x] `nombre` (string, requerido)
- [x] `direccion` (string, requerido)
- [x] `telefono` (string, requerido)
- [x] `estado` (enum: Activo/Inactivo, default: Activo)

### ✅ Campos Actualizables
- [x] `nombre`
- [x] `direccion`
- [x] `telefono`
- [x] `estado`

## Instalación y Configuración

### Requisitos
- PHP 8.1 o superior
- Composer
- SQLite (incluido con PHP)

### Pasos de Instalación

1. **Clonar/acceder al proyecto:**
```bash
https://github.com/Danielprogramin/DesafioAICOLL.git
cd DesafioAICOLL
```

2. **Instalar dependencias:**
```bash
composer install
```

3. **Configurar el archivo .env:**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Ejecutar migraciones:**
```bash
php artisan migrate
```

5. **Poblar con datos de ejemplo (opcional):**
```bash
php artisan db:seed --class=EmpresaSeeder
```

6. **Iniciar el servidor:**
```bash
php artisan serve
```

El servidor estará disponible en: `http://127.0.0.1:8000`

## Testing

```bash
# Ejecutar todas las pruebas
php artisan test

# Ejecutar pruebas específicas
php artisan test --filter EmpresaApiTest
php artisan test --filter EmpresaModelTest

# Ejecutar con coverage (requiere Xdebug)
php artisan test --coverage
```

## Endpoints de la API

### Base URL: `http://127.0.0.1:8000/api`

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/empresas` | Obtener todas las empresas |
| POST | `/empresas` | Crear nueva empresa |
| GET | `/empresas/{nit}` | Obtener empresa por NIT |
| PUT | `/empresas/{nit}` | Actualizar empresa |
| DELETE | `/empresas/inactive` | Eliminar empresas inactivas |

## Ejemplos de Uso

### 1. Obtener todas las empresas
```bash
curl -X GET http://127.0.0.1:8000/api/empresas
```

### 2. Crear nueva empresa
```bash
curl -X POST http://127.0.0.1:8000/api/empresas \
  -H "Content-Type: application/json" \
  -d '{
    "nit": "900111222-3",
    "nombre": "Mi Nueva Empresa S.A.S.",
    "direccion": "Calle 50 # 25-30, Bogotá",
    "telefono": "601-111-2222"
  }'
```

### 3. Buscar empresa por NIT
```bash
curl -X GET http://127.0.0.1:8000/api/empresas/900123456-7
```

### 4. Actualizar empresa
```bash
curl -X PUT http://127.0.0.1:8000/api/empresas/900123456-7 \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "Empresa Actualizada S.A.",
    "estado": "Inactivo"
  }'
```

### 5. Eliminar empresas inactivas
```bash
curl -X DELETE http://127.0.0.1:8000/api/empresas/inactive
```

## Arquitectura del Código

### Estructura de Directorios
```
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       └── EmpresaController.php   # Controlador API principal
│   └── Requests/
│       ├── StoreEmpresaRequest.php     # Validación creación
│       └── UpdateEmpresaRequest.php    # Validación actualización
├── Models/
│   └── Empresa.php                     # Modelo Eloquent
├── Repositories/
│   ├── Interfaces/
│   │   └── EmpresaRepositoryInterface.php
│   └── EmpresaRepository.php           # Implementación repositorio
├── Services/
│   └── EmpresaService.php              # Lógica de negocio
└── Exceptions/
    ├── EmpresaNotFoundException.php    # Excepción personalizada
    └── DuplicateNitException.php       # Excepción NIT duplicado
```

### Patrones de Diseño Implementados

1. **Repository Pattern**: Abstracción del acceso a datos
2. **Service Layer**: Centralización de lógica de negocio  
3. **Dependency Injection**: Inyección de dependencias
4. **Factory Pattern**: Creación de objetos para testing
5. **Form Request Pattern**: Validaciones centralizadas
6. **API Resource Routes**: Uso de rutas RESTful para APIs

## Base de Datos

La aplicación utiliza SQLite como base de datos (archivo `database/database.sqlite`).

### Esquema de la tabla `empresas`:
```sql
CREATE TABLE empresas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nit VARCHAR(255) UNIQUE NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    telefono VARCHAR(255) NOT NULL,
    estado VARCHAR(255) DEFAULT 'Activo' CHECK(estado IN ('Activo', 'Inactivo')),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

## Validaciones Implementadas

### Crear Empresa (POST):
- **nit**: Requerido, string, máximo 20 caracteres, único
- **nombre**: Requerido, string, máximo 255 caracteres
- **direccion**: Requerida, string, máximo 500 caracteres
- **telefono**: Requerido, string, máximo 20 caracteres

### Actualizar Empresa (PUT):
- **nombre**: Opcional, string, máximo 255 caracteres
- **direccion**: Opcional, string, máximo 500 caracteres
- **telefono**: Opcional, string, máximo 20 caracteres
- **estado**: Opcional, enum ('Activo', 'Inactivo')

## Logging y Debugging

Los logs se almacenan en `storage/logs/laravel.log` e incluyen:
- Errores de creación/actualización de empresas
- Excepciones capturadas
- Operaciones de eliminación de empresas inactivas

## Performance y Seguridad

### Optimizaciones:
- Índice único en columna `nit`
- Uso de transacciones para operaciones críticas
- Validación a nivel de base de datos y aplicación

### Seguridad:
- Sanitización de inputs mediante Form Requests
- Validación de tipos de datos
- Manejo seguro de excepciones

## Documentación Adicional

Ver `API_DOCUMENTATION.md` para documentación detallada de la API con ejemplos de respuestas.

