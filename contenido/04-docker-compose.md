
# Docker-compose

En el contexto de Docker, `networking` se refiere a cómo los contenedores pueden comunicarse entre sí y con el mundo exterior. Docker proporciona varios tipos de redes que permiten diferentes grados de aislamiento y conectividad entre contenedores

Por otra parte, `Docker Compose` es una herramienta que facilita la definición y gestión de aplicaciones multi-contenedor en Docker. Permite definir toda la configuración de una aplicación en un archivo YAML llamado "docker-compose.yml". Este archivo contiene información sobre los contenedores que forman parte de la aplicación, sus configuraciones, volúmenes, variables de entorno y también puede incluir configuraciones de redes

El objetivo principal de utilizar Docker Compose en conjunto con la configuración de redes es simplificar la creación y administración de aplicaciones que constan de múltiples contenedores. Algunos de los beneficios clave incluyen:

`Definición declarativa`: Docker Compose permite definir la arquitectura de la aplicación y sus dependencias en un archivo YAML, lo que facilita la creación y replicación del entorno de desarrollo y producción.

`Facilita la comunicación`: Mediante la configuración de redes, Docker Compose facilita la comunicación entre los contenedores de la aplicación, lo que es esencial para las aplicaciones distribuidas.

`Gestión de recursos`: Docker Compose proporciona un mecanismo para asignar recursos de red a cada contenedor, controlando cómo se comunican y comparten información.

`Portabilidad`: La combinación de Docker Compose y las configuraciones de redes permite a los desarrolladores construir aplicaciones en entornos locales y luego desplegarlas de manera consistente en diferentes entornos, como entornos de prueba, etapas de preparación y producción.


# Ventajas de usar Docker-compose

* Hacer todo de manera **declarativa** para que no tenga que repetir todo el proceso cada vez que construyo el escenario.
* Poner en funcionamiento todos los contenedores que necesita mi aplicación de una sola vez y debidamente configurados.
* Garantizar que los contenedores **se arrancan en el orden adecuado**. Por ejemplo: mi aplicación no podrá funcionar debidamente hasta que no esté el servidor de bases de datos funcionando en marcha.
* Asegurarnos de que hay **comunicación** entre los contenedores que pertenecen a la aplicación.

# Instalación Docker-compose

Instalación de docker-compose  [Docker-compose](https://docs.docker.com/compose/install/)

# Día 4 - Docker Lab 

Intentemos conectar dos contenedores `sin docker-compose`:

Primero creamos el contenedor de mysql:

```bash
╰─ docker run -e MYSQL_DATABASE=database -e MYSQL_USER=mysql_user -e MYSQL_ALLOW_EMPTY_PASSWORD=true -e MYSQL_PASSWORD=mysql_password -p 9906:3606 -d --name db mysql
```

Segundo creamos la imagen de [php8apache](/extras/02-imagenes/v0.9.0/Dockerfile):

```bash
#Crear la imagen de myphp8-apache para que levante el index.php desde el
#Dockerfile 
╰─ docker build -t myphp8-apache .
Sending build context to Docker daemon  3.584kB
Step 1/2 : FROM php:7.2-apache
 ---> c61d277263e1
Step 2/2 : COPY src/ /var/www/html/
 ---> a911166a6ce3
Successfully built a911166a6ce3
Successfully tagged php7apache:latest

```

Tercero, creamos el contenedor de la imagen anterior y asignamos la opción `--link` para conectar ambos contenedores: 

```bash
╰─ docker run -v "$(pwd)"/src:/var/www/html -p 8000:80 --link db -d myphp8-apache
``` 

Acceder al puerto 8000 del host y comprobamos si es exitosa la conexión.
Sin embargo, cuál es la desventaja qué presenta está comunicación ?


Cada vez que necesitamos usar, actualizar, o instalar los contenedores en otro entorno deberíamos guardar y gestionar todas las instrucciones de nuevo, además tener que recordar que comandos deberíamos repetir y ver si hace falta crear o no las redes de nuevo; para evitar todo eso es donde entra en juego ***DOCKER COMPOSE***. 

Docker-compose  permite usar un archivo YAML (`docker-compose.yml ó compose.yml`) para definir cómo le gustaría que se estructurara su aplicación en **múltiples** contenedores. Se tomaría el archivo YAML y se automatizaría el lanzamiento de los contenedores tal como se definió. La ventaja de es que, debido a que es un archivo YAML, es muy fácil para los desarrolladores comenzar a enviar los archivos junto con sus archivos `Dockerfile` dentro de sus bases de código.

**OBSERVACIÓN: Prestar atención a la sintaxis, indentación y espaciado en el archivo YAML, es crucial para garantizar que el archivo sea válido y funcione correctamente.**

Realicemos el mismo ejercicio anterior pero esta vez utilizando `docker-compose`

Ir al directorio de [docker-compose](/extras/04-dockercompose/php8-apache-concompose/)  y observar el siguiente archivo `docker-compose.yml`

```bash
version: '3.8'
services:
  #docker run -e MYSQL_DATABASE=database -e MYSQL_USER=mysql_user -e MYSQL_PASSWORD=mysql_password -e MYSQL_ALLOW_EMPTY_PASSWORD=true -d --name db mysql
  db:
    #container_name: db
    image: mysql
      #restart: always
    environment:
      #MYSQL_ROOT_PASSWORD: MYSQL_ROOT_PASSWORD
      MYSQL_DATABASE: database
      MYSQL_USER: mysql_user
      MYSQL_PASSWORD: mysql_password
      MYSQL_ALLOW_EMPTY_PASSWORD: true
    ports:
      - "9906:3306"

  #phpmyadmin:
  #  image: phpmyadmin/phpmyadmin
  #  ports:
  #    - '8080:80'
  #  environment:
  #    PMA_HOST: db
  #  depends_on:
  #    - db

  # docker run -v "$(pwd)"/src:/var/www/html -p 8001:80 --link db -d myphp8-apache
  php8-apache:
    #container_name: php8-apache
    build:
      context: ./php
      dockerfile: Dockerfile
    volumes:
      - ./php/src:/var/www/html/
    depends_on:
      - db
    ports:
      - 8001:80
```

Luego parados en ese directorio ejecutar el siguiente comando
```bash
docker-compose up
. 
[+] Running 3/3
 ✔ Network php8-apache-concompose_default          Created                                                 0.0s 
 ✔ Container php8-apache-concompose-db-1           Created                                                 0.0s 
 ✔ Container php8-apache-concompose-php8-apache-1  Cre...                                                  0.0s 
Attaching to db-1, php8-apache-1
db-1           | 2024-03-07 01:50:06+00:00 [Note] [Entrypoint]: Entrypoint script for MySQL Server 8.3.0-1.el8 started.
db-1           | 2024-03-07 01:50:06+00:00 [Note] [Entrypoint]: Switching to dedicated user 'mysql'
db-1           | 2024-03-07 01:50:06+00:00 [Note] [Entrypoint]: Entrypoint script for MySQL Server 8.3.0-1.el8 started.
php8-apache-1  | AH00558: apache2: Could not reliably determine the server's fully qualified domain name, using 172.27.0.3. Set the 'ServerName' directive globally to suppress this message
php8-apache-1  | AH00558: apache2: Could not reliably determine the server's fully qualified domain name, using 172.27.0.3. Set the 'ServerName' directive globally to suppress this message
php8-apache-1  | [Thu Mar 07 01:50:06.599415 2024] [mpm_prefork:notice] [pid 1] AH00163: Apache/2.4.56 (Debian) PHP/8.0.30 configured -- resuming normal operations
php8-apache-1  | [Thu Mar 07 01:50:06.599449 2024] [core:notice] [pid 1] AH00094: Command line: 'apache2 -D FOREGROUND'
db-1           | 2024-03-07 01:50:06+00:00 [Note] [Entrypoint]: Initializing database files
db-1           | 2024-03-07T01:50:06.654139Z 0 [System] [MY-0150
```

Ir al navegador del host y probar la conexión al puerto 8001 (http://localhost:8001)

```bash
Connected to MySQL server successfully!
```

Próximo paso, jugar con la base de datos creada en el contenedor `db` modificando el archivo `docker-compose.yml` agregando el servicio de `phpmyadmin`.


## Docker Compose commands  [v1]

* `docker-compose up`: Crear los contenedores (servicios) que están descritos en el `docker-compose.yml`.
* `docker-compose up -d`: Crear en modo background los contenedores (servicios)en el `docker-compose.yml`.
* `docker-compose stop` : Detiene los contenedores que previamente se han lanzado con `docker-compose up`.
* `docker-compose run`  : Inicia los contenedores descritos en el `docker-compose.yml` que estén parados.
* `docker-compose rm`   : Borra los contenedores parados del escenario. Con las opción `-f` elimina también los contenedores en ejecución.
* `docker-compose pause`: Pausa los contenedores que previamente se han lanzado con `docker-compose up`.
* `docker-compose unpause`: Reanuda los contenedores que previamente se han pausado.
* `docker-compose restart`: Reinicia los contenedores. Orden ideal para reiniciar servicios con nuevas configuraciones.
* `docker-compose down`:  Para los contenedores, los borra  y también borra las redes que se han creado con `docker-compose up` (en caso de haberse creado).
* `docker-compose down -v`: Para los contenedores y borra contenedores, redes y volúmenes.
* `docker-compose logs`: Muestra los logs de todos los servicios del escenario. Con el parámetro `-f`podremos ir viendo los logs en "vivo".
* `docker-compose logs servicio1`: Muestra los logs del servicio llamado `servicio1` que estaba descrito en el `docker-compose.yml`.
* `docker-compose exec servicio1 /bin/bash`: Ejecuta una orden, en este caso `/bin/bash` en un contenedor llamado `servicio1` que estaba descrito en el `docker-compose.yml`
* `docker-compose build`: Ejecuta, si está indicado, el proceso de construcción de una imagen que va a ser usado en el `docker-compose.yml`  a partir de los archivos `Dockerfile` que se indican.

## Ejemplos Docker-compose
[Voting](https://github.com/dockersamples/example-voting-app)
[Wordpress](https://docs.docker.com/compose/wordpress/)
