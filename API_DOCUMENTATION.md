# API de Gestión de Empresas

Esta API permite gestionar datos de empresas a través de Web Services RESTful.

## Endpoints Disponibles

### 1. Obtener todas las empresas
```
GET /api/empresas
```

**Respuesta exitosa:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "nit": "900123456-7",
            "nombre": "Empresa de Tecnología S.A.",
            "direccion": "Calle 100 # 45-67, Bogotá",
            "telefono": "601-234-5678",
            "estado": "Activo",
            "created_at": "2025-09-04T13:49:45.000000Z",
            "updated_at": "2025-09-04T13:49:45.000000Z"
        }
    ],
    "message": "Empresas obtenidas exitosamente."
}
```

### 2. Crear una nueva empresa
```
POST /api/empresas
Content-Type: application/json
```

**Cuerpo de la petición:**
```json
{
    "nit": "900111222-3",
    "nombre": "Mi Nueva Empresa S.A.S.",
    "direccion": "Calle 50 # 25-30, Bogotá",
    "telefono": "601-111-2222"
}
```

**Respuesta exitosa (201):**
```json
{
    "success": true,
    "data": {
        "id": 6,
        "nit": "900111222-3",
        "nombre": "Mi Nueva Empresa S.A.S.",
        "direccion": "Calle 50 # 25-30, Bogotá",
        "telefono": "601-111-2222",
        "estado": "Activo",
        "created_at": "2025-09-04T13:49:45.000000Z",
        "updated_at": "2025-09-04T13:49:45.000000Z"
    },
    "message": "Empresa creada exitosamente."
}
```

**Error NIT duplicado (409):**
```json
{
    "success": false,
    "message": "El NIT '900111222-3' ya está registrado en el sistema."
}
```

**Error de validación (422):**
```json
{
    "message": "El NIT es obligatorio. (and 3 more errors)",
    "errors": {
        "nit": ["El NIT es obligatorio."],
        "nombre": ["El nombre es obligatorio."],
        "direccion": ["La dirección es obligatoria."],
        "telefono": ["El teléfono es obligatorio."]
    }
}
```

### 3. Consultar empresa por NIT
```
GET /api/empresas/{nit}
```

**Ejemplo:**
```
GET /api/empresas/900123456-7
```

**Respuesta exitosa:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "nit": "900123456-7",
        "nombre": "Empresa de Tecnología S.A.",
        "direccion": "Calle 100 # 45-67, Bogotá",
        "telefono": "601-234-5678",
        "estado": "Activo",
        "created_at": "2025-09-04T13:49:45.000000Z",
        "updated_at": "2025-09-04T13:49:45.000000Z"
    },
    "message": "Empresa encontrada exitosamente."
}
```

**Error empresa no encontrada (404):**
```json
{
    "success": false,
    "message": "Empresa con identificador 'NIT-INEXISTENTE' no encontrada."
}
```

### 4. Actualizar datos de una empresa
```
PUT /api/empresas/{nit}
Content-Type: application/json
```

**Cuerpo de la petición (campos opcionales):**
```json
{
    "nombre": "Empresa de Tecnología Actualizada S.A.",
    "direccion": "Nueva Calle 200 # 45-67, Bogotá",
    "telefono": "601-999-8888",
    "estado": "Inactivo"
}
```

**Respuesta exitosa:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "nit": "900123456-7",
        "nombre": "Empresa de Tecnología Actualizada S.A.",
        "direccion": "Nueva Calle 200 # 45-67, Bogotá",
        "telefono": "601-999-8888",
        "estado": "Inactivo",
        "created_at": "2025-09-04T13:49:45.000000Z",
        "updated_at": "2025-09-04T14:15:30.000000Z"
    },
    "message": "Empresa actualizada exitosamente."
}
```

### 5. Eliminar empresas inactivas
```
DELETE /api/empresas/inactive
```

**Respuesta exitosa:**
```json
{
    "success": true,
    "data": {
        "deleted_count": 2,
        "message": "Se eliminaron 2 empresas inactivas."
    },
    "message": "Se eliminaron 2 empresas inactivas."
}
```

## Validaciones Implementadas

### Para crear empresa:
- `nit`: Requerido, string, máximo 20 caracteres, debe ser único
- `nombre`: Requerido, string, máximo 255 caracteres
- `direccion`: Requerida, string, máximo 500 caracteres
- `telefono`: Requerido, string, máximo 20 caracteres

### Para actualizar empresa:
- `nombre`: Opcional, string, máximo 255 caracteres
- `direccion`: Opcional, string, máximo 500 caracteres
- `telefono`: Opcional, string, máximo 20 caracteres
- `estado`: Opcional, debe ser "Activo" o "Inactivo"

## Reglas de Negocio

1. **Estado por defecto**: Las empresas se crean con estado "Activo" por defecto
2. **NIT único**: No puede haber empresas con el mismo NIT
3. **Campos actualizables**: Solo se pueden actualizar nombre, dirección, teléfono y estado
4. **Eliminación selectiva**: Solo se pueden eliminar empresas con estado "Inactivo"

## Arquitectura del Código

### Patrones Implementados:
- **Repository Pattern**: Separación de la lógica de acceso a datos
- **Service Layer**: Lógica de negocio centralizada
- **Dependency Injection**: Inyección de dependencias para testing
- **Form Requests**: Validaciones centralizadas

### Estructura de directorios:
```
app/
├── Http/
│   ├── Controllers/
│   │   └── EmpresaController.php
│   └── Requests/
│       ├── StoreEmpresaRequest.php
│       └── UpdateEmpresaRequest.php
├── Models/
│   └── Empresa.php
├── Repositories/
│   ├── Interfaces/
│   │   └── EmpresaRepositoryInterface.php
│   └── EmpresaRepository.php
├── Services/
│   └── EmpresaService.php
└── Exceptions/
    ├── EmpresaNotFoundException.php
    └── DuplicateNitException.php
```

## Testing

Se incluyen pruebas unitarias y de integración:

```bash
# Ejecutar todas las pruebas
php artisan test

# Ejecutar pruebas específicas
php artisan test --filter EmpresaApiTest
php artisan test --filter EmpresaModelTest
```

## Ejemplos de uso con cURL

```bash
# Obtener todas las empresas
curl -X GET http://localhost:8000/api/empresas

# Crear nueva empresa
curl -X POST http://localhost:8000/api/empresas \
  -H "Content-Type: application/json" \
  -d '{
    "nit": "900999888-7",
    "nombre": "Test Company",
    "direccion": "Test Address",
    "telefono": "555-1234"
  }'

# Consultar por NIT
curl -X GET http://localhost:8000/api/empresas/900123456-7

# Actualizar empresa
curl -X PUT http://localhost:8000/api/empresas/900123456-7 \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "Updated Company Name",
    "estado": "Inactivo"
  }'

# Eliminar empresas inactivas
curl -X DELETE http://localhost:8000/api/empresas/inactive
```
