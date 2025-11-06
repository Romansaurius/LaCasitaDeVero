# Guía de Instalación - La Casita de Vero

## Requisitos
- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web (Apache/Nginx) con mod_rewrite habilitado

## Pasos de Instalación

### 1. Configurar la Base de Datos

Ejecuta el archivo SQL para crear la base de datos y las tablas:

```bash
mysql -u u214138677_datasnap -p < database/schema.sql
```

O importa manualmente el archivo `database/schema.sql` desde phpMyAdmin.

### 2. Configurar la Conexión a la Base de Datos

Edita el archivo `config/database.php` con tus credenciales:

```php
<?php
return [
    'host' => 'escuelarobertoarlt.com',  // o 'localhost' para desarrollo local
    'dbname' => 'u214138677_datasnap',
    'username' => 'u214138677_datasnap',
    'password' => 'tu_contraseña',
    'charset' => 'utf8mb4',
    'port' => 3306
];
```

### 3. Configurar el Servidor Web

#### Apache
Asegúrate de que el archivo `.htaccess` esté en la raíz del proyecto y que `mod_rewrite` esté habilitado.

#### Nginx
Agrega esta configuración a tu archivo de configuración:

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### 4. Permisos de Archivos

Asegúrate de que el servidor web tenga permisos de lectura en todos los archivos:

```bash
chmod -R 755 .
```

### 5. Probar la Aplicación

Accede a tu aplicación en el navegador:

- Página principal: `http://tu-dominio.com/`
- Login: `http://tu-dominio.com/login`
- Registro: `http://tu-dominio.com/register`

## Estructura del Proyecto

```
LaCasitaDeVero/
├── config/              # Configuración y recursos estáticos
│   ├── css/
│   ├── images/
│   └── database.php
├── database/            # Scripts SQL
│   └── schema.sql
├── src/
│   ├── controllers/     # Controladores
│   ├── core/           # Clases core (Database)
│   ├── models/         # Modelos
│   └── views/          # Vistas HTML
├── .htaccess           # Configuración Apache
├── index.php           # Punto de entrada
└── README.md
```

## Rutas Disponibles

- `/` - Página principal
- `/login` - Iniciar sesión
- `/register` - Registrarse
- `/logout` - Cerrar sesión
- `/servicios` - Servicios
- `/sobre_nosotros` - Sobre nosotros
- `/contacto` - Contacto
- `/guarderia` - Guardería

## Solución de Problemas

### Error 500
- Verifica los logs de PHP
- Asegúrate de que la base de datos esté configurada correctamente
- Verifica que todas las clases estén en las rutas correctas

### Error 404
- Verifica que `.htaccess` esté en la raíz
- Asegúrate de que `mod_rewrite` esté habilitado en Apache

### No se puede conectar a la base de datos
- Verifica las credenciales en `config/database.php`
- Asegúrate de que el servidor MySQL esté corriendo
- Verifica que la base de datos exista

## Desarrollo Local

Para desarrollo local, puedes usar el servidor integrado de PHP:

```bash
php -S localhost:8000
```

Luego accede a `http://localhost:8000`
