# COPOMEX Demo

Prueba técnica desarrollada con Laravel para el consumo del servicio COPOMEX.

## Stack
- Laravel 12
- PHP 8.4
- MySQL
- Bootstrap 4
- DataTables

## Funcionalidad
- Sincronización de estados desde COPOMEX
- Persistencia en base de datos (idempotente)
- Listado con búsqueda, orden y paginación
- Consulta de municipios por estado en tiempo real

## Instalación
1. Clonar repositorio
2. Configurar `.env`
3. Ejecutar migraciones
4. Ejecutar `php artisan serve`

## Notas
- El token de pruebas de COPOMEX devuelve datos simulados.