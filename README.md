# Buking

Buking es una tienda online de busqueda de hoteles, vuelos y Actividades
## Pre-requisitos 📋

_Cosas necesarias para utilizar la app_

* Wkhtmltopdf - Se utiliza para pasar de HTML a PDF o Imagen.

* Un sitio en el que desplegar la aplicación - Ya sea en local para hacer pruebas o en la nube (como con GCloud)

* (OPCIONAL) un correo corporativo de AWS o alguna similar permite en la app enviar correos además de la factura (antes se utilizaba gmail pero han desactivado las aplicaciones para terceros)

## Instalación y despliegue 🔧

_Para desplegar la aplicacion en GCloud primero haremos un clone hacia nuestro servidor_

```
git clone https://gitlab.ujaen.es/mss00048/TBW2122_6.git
```

_Es necesario el cambio a modo producción para el despliegue sin errores, en caso de no estarlo, en el .env debes cambiar lo siguiente_

```
APP_ENV=dev
```
_por_

```
APP_ENV=prod
```
_Después desde la consola de GCloud pondremos lo siguiente_
```
gcloud app deploy
```
_Esto dejará la app funcionando y desplegada._


En caso de querer montarlo en un servidor local:

_Configura un VirtualHost de Apache y haz el git clone dentro_
```
<VirtualHost *:80>
	ServerName buking
	DocumentRoot "c:/xamp/www/ejemplo"
	<Directory  "c:/xamp/www/ejemplo/">
		Options +Indexes +Includes +FollowSymLinks +MultiViews
		AllowOverride All
		Require local
	</Directory>
</VirtualHost>
```
Solo debes cambiar el DocumentRoot y el Directory por el tuyo 

## Configuraciones necesarias para el uso de la app 💾

### Base de datos

Para empezar a utilizar la app debemos preparar la base de datos utilizando el archivo sql que va dentro de la carpeta BD en el proyecto, hay 2 archivos, uno va con datos y otro sin ellos, por si tienes tus propios inserts o quieres utilizar los que hay de prueba.

Despues de configurar tu BD debes ir al archivo .env y modificar la siguiente linea de codigo:

```
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7&charset=utf8mb4"
```
Con modificar esa linea substituyendo con tus datos tendras la conexion a la BD realizada y estará funcionando.

### Wkhtmltopdf

En este mismo archivo también se debe configurar en el .env la siguiente linea que dicta donde están los archivos bin de wkhtmltopdf

```
WKHTMLTOPDF_PATH="C:/Program Files/wkhtmltopdf/bin/wkhtmltopdf"
WKHTMLTOIMAGE_PATH="C:/Program Files/wkhtmltopdf/bin/wkhtmltoimage"
```
Solo debes sustituirlos con la ruta al programa en tu pc

### Mailer

En caso de disponer de un correo electrónico compatible con el uso de mailer de symfony, puedes cambiar esta linea de codigo configurando tu correo y el servidor smtp en el que lo uses

```
# MAILER_DSN=gmail://USERNAME:PASSWORD@defaults
```
Este es el caso de gmail (que ya no puede usarse) en caso de querer usar otro es la misma linea pero cambia un par de datos, puedes mirar la lista completa en [la documentacion oficial de Symfony](https://symfony.com/doc/current/mailer.html#using-a-3rd-party-transport).

### Directorio de Imagenes

Para cambiar el directorio predeterminado al que están subidas o se suben las imagenes, debes irte al siguiente archivo
```
Config/services.yaml
```
Dentro de este archivo tienes una linea de código dentro de _parameters_ que puedes modificar para cambiar el sitio predeterminado donde se guardan las mismas.
```
directorio_imagenes: '%kernel.project_dir%/public/imagenes'
```

## Construido con 🛠️

* [Symfony](https://symfony.com/) - El framework web usado
* [Google Cloud Services](https://wbt2122-6-mss.oa.r.appspot.com/) - Donde se ha desplegado la app
* [XAMPP](https://www.apachefriends.org/es/download.html) - Despliegue en local para el desarrollo
* [Wkhtmltopdf](https://wkhtmltopdf.org/) - Transformación de un texto HTML a un PDF (utilizado para la generación de la factura)

## Autores ✒️

* **Manuel Sánchez Salazar** - *Desarrollador* - [DRUBRoxas](https://github.com/DRUBRoxas)
* **Rafael Aznar Estrada** - *Desarrollador* - [RafaEstrada96](https://github.com/RafaEstrada96)
