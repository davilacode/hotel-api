# Prueba Técnica Desarrollador Fullstack - Parte Backend

Backend desarrollado en PHP/Laravel para crear una API RESTful y consumir sus servicios con un frontend desacoplado.

# Funciones de la API

- Permite crear hoteles con datos básicos y la capacidad de habitaciones, validando que no se repitan hoteles según su nombre y nro NIT 
- Permite asignar acomodaciones a estos hoteles, validando por tipo, acomodación y la capacidad total que tiene cada hotel. También se valida que no exista mas de un tipo+acomodación por hotel.

## Requerimientos técnicos

- Conexión a una base de datos local o remota
- PHP 8.4
- Composer 2
- Laravel 12

## Pasos para su ejecución

- Luego de verificado los requisitos técnicos, debemos clonar el repositorio
- Nos ubicamos en la carpeta del proyecto con la terminal
- Creamos un nuevo archivo `.env` con `cp .env.example .env`
- Agregamos la configuración de la base de datos en el `.env` ya creado las siguientes variables
	- `DB_CONNECTION=pgsql`
	- `DB_HOST=`
	- `DB_PORT=`
	- `DB_DATABASE=`
	- `DB_USERNAME=`
	- `DB_PASSWORD=`
- Ejecutamos `composer install` para instalar las dependencias
- Ejecutamos `php artisan key:generate` para crear la `APP_KEY` dentro de las variables de entorno
- (opcional) Si la base de datos esta vacía, ejecutamos `php artisan migrate`
- Corremos `php artisan serve` para hacer funcionar el servicio en local

## Esquema de la base de datos
La base de datos tiene el siguiente esquema:

[![](https://i.postimg.cc/90BB2vwH/Captura-de-pantalla-2025-04-26-185806.png)](https://i.postimg.cc/90BB2vwH/Captura-de-pantalla-2025-04-26-185806.png)

## Documentación API 
La API cuenta con una documentación donde muestra todos los parámetros y datos que recibe cada ruta. Esta se puede ver en la siguiente ruta después de que el proyecto se esté ejecutando en local `https://127.0.0.1:8000/docs/api`.
