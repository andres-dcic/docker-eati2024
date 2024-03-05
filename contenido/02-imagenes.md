
# Layers 

Cuando creamos un contenedor a partir de una imagen de contenedor, todo en la imagen se trata como de solo lectura y hay una nueva capa superpuesta en la parte superior que es de lectura/escritura.


![layers](./img/container-filesystem.jpg)

1. Ver las imagenes que tenemos actualmente en nuestra máquina
``` shell
docker images
```

2. Buscar por imagenes en la Registry de DockerHub
``` shell
docker search ubuntu
```

3. Bajar una imagen de docker y listar las imagenes
``` shell
docker pull ubuntu:latest
docker pull nginx:latest
docker image ls
```

4. Crear un contenedor, instalar python y hacer un commit de la imagen
``` shell
docker run -it ubuntu:14.04
apt-get update -y && apt-get install python -y
exit
docker ps -a
docker commit <container id> ubuntu:python
```

5. Ver las imagenes y como fue creada la imagen
``` shell
docker image ls
docker history ubuntu:python
```

6. Crear un contenedor con la nueva imagen 
``` shell
docker run -it ubuntu:python
```


Por ejemplo:

```bash
# Creamos un contenedor desde una imagen de ubuntu
docker run --interactive --tty --rm ubuntu:22.04

# ping google.com
ping google.com -c 1 # `bash: ping: command not found`

# Install ping
apt update
apt install iputils-ping --yes

ping google.com -c 1 # This time it succeeds!
exit
#salimos y chau!
```

Volvemos a probar 
```bash
docker run -it --rm ubuntu:22.04
ping google.com -c 1 # It fails! 🤔
```


# Dockerfile

Antes de crear nuestros templates de `Docker` aprenderemos como personalizar nuestras propias imagenes de los contenedores:

- Dockerfile
- Cómo funciona Docker Build
- Variables de entorno
- Construyendo una imagen base usando el Dockerfile
- Juntamos todo


## Introducción a un DockerFile 

Un `Dockerfile´ es simplemente un archivo de texto plano que contiene un conjunto de comandos definidos por el usuario, que cuando se ejecutan mediante el comando *docker image build*, que lo que realiza es una "Compilacion" de esa imagen de docker.

Un Dockerfile de ejemplo:

```bash
# Usaremos alpine como base
FROM alpine:3.16
# Actualizamos apk, el package manager de alpine
RUN apk update
# Instalamos curl para poder hacer pruebas
RUN apk add curl
```

Alpine Linux es una distribución de Linux no comercial pequeña, desarrollada independientemente, diseñada para la seguridad, la eficiencia y la facilidad de uso. Para obtener más información sobre Alpine Linux, visite el sitio web del proyecto en su [web](https://www.alpinelinux.org/)


## Revisando el Dockerfile en profundidad

Comando por orden de uso en un fichero Dockerfile: 

    - FROM
    - LABEL
    - RUN
    - COPY and ADD
    - EXPOSE
    - ENTRYPOINT and CMD
    - Otros comandos Dockerfile

## FROM
 Selecciona la imagen base que se usa, se indica imagen:tag, en nuestro caso es __alpine:latest__
```bash
FROM <imagen>
FROM <imagen>:<tag>
```

## LABEL
 Permite añadir información extra sobre el container las etiquetas que se puede usar estan documentadas [aqui](http://label-schema.org/)
 Las etiquetas se pueden consultar usando __docker inspect__.

```bash
docker image inspect <IMAGEN_ID>
```

 O tambien:
 ```bash
docker image inspect -f {{.Config.Labels}} <IMAGE_ID>
 ```


## RUN
Con el comando RUN permite instalar sofware y correr scripts con el ejemplo siguiente:

```bash
RUN   apk add --update nginx && \
      rm -rf /var/cache/apk/* && \
      mkdir -p /tmp/nginx/ 
```

```bash
RUN apk add --update nginx
RUN rm -rf /var/cache/apk/*
RUN mkdir -p /tmp/nginx/
```

El resutado de los dos ejemplos no es identico  al  agregar varias etiquetas, esto crearía una capa individual para cada uno de los Comandos RUN, que en su mayor parte deberíamos intentar y evitar.


Tiene dos modos modo shell: /bin/sh -c
```bash
 RUN comando
```
Y el modo ejecución:
```bash
RUN ["ejecutable", "parámetro1", "parámetro2"]
RUN ["/bin/bash", "-c", "echo prueba"]
```


## COPY and ADD
Este comando copia archivos a nuestra imagen por ejemplo:

```bash
   COPY files/nginx.conf /etc/nginx/nginx.conf
```

Estos comandos sobrescriben cualquier archivo que existe tendriamos dentro del docker. 
ADD tiene mas capacidad,  ademas de agregar fichros carga y descomprime ficheros .tar y coloca las carpetas y archvos en la rutoa y puede usar rutas remotas como

```bash
   ADD http://www.myremotesource.com/files/html.tar.gz /usr/share/nginx/
```

## EXPOSE

El comando **EXPOSE** le permite a Docker saber que cuando se ejecuta la imagen, el puerto y El protocolo definido será expuesto en runtime. Este comando no asigna el puerto a la máquina host, pero en su lugar abre el puerto para permitir el acceso al servicio en el contenedor red. Por ejemplo, en nuestro Dockerfile, le estamos diciendo a Docker que abra el puerto 80 cada vez la imagen corre:
```bash
   EXPOSE 80 / tcp
```

## ENTRYPOINT and CMD

Un Dockerfile nos permite definir un comando a ejecutar por defecto, para cuando se inicien contenedores a partir de nuestra imagen.

Tenemos 2 instrucciones para este propósito: ENTRYPOINT y CMD.


Si sólo especificas un CMD, entonces Docker ejecutará dicho comando usando el ENTRYPOINT por defecto, que es `/bin/sh -c`.

Respecto al "entrypoint" (punto de entrada) y "cmd" (comando), puedes sobreescribir ninguno, ambos, o sólo uno de ellos.

Si especificas ambos, entonces:

- El `ENTRYPOINT` especifica el ejecutable que usará el contenedor y `CMD` se corresponde con los parámetros a usar con dicho ejecutable


```bash
ENTRYPOINT ["nginx"]
CMD ["-g", "daemon off;"]
```

Esto equivale a : $ nginx -g daemon off

Ejemplo1

```bash
FROM alpine:3.16
CMD ["/bin/date"]
# se usa  el ENTRYPOINT por defecto (que es /bin/sh -c), y ejecuta /bin/date sobre dicho punto de entrada.
```

Al ejecutar esta imagen, el contenedor imprimirá por defecto la fecha actual:

```bash
docker build -t test .
docker run test
Tue Dec 19 10:37:43 UTC 2016
```

Sin embargo es posible sobreescribir el comando CMD a usar por defecto, desde la misma línea de comandos (en tal caso se ejecutará el comando indicado):

```bash
docker run test /bin/hostname
bf0274ec8820
```

Si usas la instrucción ENTRYPOINT, entonces Docker usará el ejecutable que le indiques, y la instrucción CMD te permitirá definir un parámetro por defecto.

Ejemplo2:

```bash
FROM alpine:3.16
ENTRYPOINT ["/bin/echo"]
CMD ["Hello"]
```
Entonces producirá como resultado:

```bash
docker build -t test .
docker run test
Hello
```

También puedes especificar un valor diferente para CMD al iniciar un contenedor, y se considerará como parámetro para el ejecutable /bin/echo (en vez del que viene por defecto):

```bash
docker run test Hi
Hi
```

Si lo deseas, también puedes sobreescribir el valor del ENTRYPOINT definido en el Dockerfile.

```bash
docker run --entrypoint=/bin/hostname test
b2c70e74df18
```

La combinación de ENTRYPOINT y CMD le permite especificar el ejecutable predeterminado para su imagen, al mismo tiempo que proporciona argumentos predeterminados a ese ejecutable que el usuario puede invalidar. Veamos un ejemplo:

Ejemplo 3:

```bash
FROM alpine:3.16
ENTRYPOINT ["/bin/ping","-c","3"]
CMD ["localhost"]
```

Al ejecutar el comando  sin argumentos:
```bash
docker build -t ping 

docker run ping
```
Y con argumentos:

```bash
docker run ping docker.io
```

## Otros comandos Dockerfile

+ USER

Permite definir el nombre del usuario que se utliza ne comando como en el RUN, CMD o ENTRYPOINT, el usuario tiene que estar en el sistema con permisos adecuados

+ WORKDIR

Define el direcctorio de trabajo de los comando

+ ENV

El comando ENV establece las variables de entorno dentro de la imagen cuando se construye y cuando se ejecuta. Estas variables se pueden anular cuando inicie su imagen.

```bash
COPY package.json $PROJECT_DIR 
RUN npm install 
COPY . $PROJECT_DIR 
ENV MEDIA_DIR=/media \ 
	 NODE_ENV=production \ 
	 APP_PORT=3000 

VOLUME $MEDIA_DIR 
EXPOSE $APP_PORT 
HEALTHCHECK CMD curl --fail http://localhost:$APP_PORT || exit 
```

Estos valores persistirán al momento de lanzar un contenedor de la imagen creada y pueden ser usados dentro de cualquier fichero del entorno, por ejemplo un script ejecutable. Pueden ser sustituida pasando la opción -env en docker run. Ejemplo:

```bash
 $ docker run -env <key>=<valor>
```



## .dockerignore (BUILD)

Usar --tag o -t para la construccion (reemplazo de número de id del contenedor).

No es necesario usar --file o -f si se esta en la misma carpeta que el fichero DockerFile, solo se necesita añadir el '.' al final.

Se puede usar el fichero `.dockerignore` para excluir aquellos archivos o carpetas que no queremos incluir en la compilación. Por defecto, todos los archivos de la carpeta Dockerfile se subirán. 

  
Mantener todos los elementos que desea usar en una imagen en la misma carpeta lo ayudará a mantener la cantidad de elementos, si usamos un comando COPY este copiara todo, si no queremos que se incluyan ficheros estos deben estar en este fichero como ejemplo:
         
```bash
.git
.ipynb_checkpoints/*
/notebooks/*
/unused/*
```

# Día 2 - Docker Lab


# Creando nuestras propias imágenes

## Crear imagen de nuestra aplicación

Vamos a crear una aplicación sencilla. Para este ejemplo, utilizaremos PHP pero pueden hacer uso de cualquier lenguaje y framework con el que se sientan cómodos, con la única salvedad que los paquetes a instalar serán distintos.

Nuestra [primera versión será sencilla](../extras/02-imagenes/v0.1.0/src/index.php), con el conocido Hola Mundo adaptado a esta presnetación.

Luego, volvemos a Docker para generar una imagen.

```bash
╰─ docker build -t my-app extras/02-imagenes/v0.1.0/    
Sending build context to Docker daemon  3.584kB
Step 1/2 : FROM php:7.2-apache
7.2-apache: Pulling from library/php
6ec7b7d162b2: Pull complete 
db606474d60c: Pull complete 
afb30f0cd8e0: Pull complete 
3bb2e8051594: Pull complete 
4c761b44e2cc: Pull complete 
c2199db96575: Pull complete 
1b9a9381eea8: Pull complete 
fd07bbc59d34: Pull complete 
72b73ab27698: Pull complete 
983308f4f0d6: Pull complete 
6c13f026e6da: Pull complete 
e5e6cd163689: Pull complete 
5c5516e56582: Pull complete 
154729f6ba86: Pull complete 
Digest: sha256:4dc0f0115acf8c2f0df69295ae822e49f5ad5fe849725847f15aa0e5802b55f8
Status: Downloaded newer image for php:7.2-apache
 ---> c61d277263e1
Step 2/2 : COPY src/ /var/www/html/
 ---> 7fbe18f0b4d4
Successfully built 7fbe18f0b4d4
Successfully tagged my-app:latest
Execution time: 0h:01m:07s sec
```

Una vez generada, deberíamos poder correrla y ver que nuestra aplicación está efectivamente en su lugar

```bash
╰─ docker run --name test --rm -i -t my-app sh 

# ls /var/www/html/
index.php
# exit
```

Ahora correremos la aplicación exportando el puerto 80 localmente para poder acceder.

```bash
docker run -p 80:80 my-app
```

Desde un navegador nos aseguramos que podemos verla, visitando <http://localhost/>. Luego volvemos a la terminal y con CTRL+C procedemos a matar el proceso de Docker.

## Tagging de imágenes

Si volvemos a la salida de docker build, veremos que nuestra imagen por defecto recibe un tag "latest" cada vez que la buildeamos:

> Successfully tagged **my-app:latest**

Esto es óptimo para un entorno de desarrollo, donde siempre queremos poder fácilmente probar la última versión, pero no es una buena práctica usar dicho tag en un entorno productivo. Entre los motivos contra esta práctica, nos encontramos con la imposibilidad de determinar que la misma imágen está corriendo en multiples entornos (si la imágen se actualiza y un entorno se reinicia, puede recibir la nueva imágen por error); la posibilidad de corromper entornos (las bases de datos necesitan procesos especiales para actualizar determinadas versiones, como saltos en versiones mayores); no es fácil replicar un entorno en otro en caso de problemas, entre otros.

Con estas consideraciones, es necesario entonces que adoptemos versionado en nuestros tags, que tengan sentido con el ciclo de vida del desarrollo de nuestra aplicación. Entre varias formas, existe una en particular llamada **Versionado Semántico** que, a grandes rasgos, nos permite tener un control sobre la versión mayor (generas incompatibilidad en el API), versión menor (nuevas funciones compatibles con anteriores) y parches (corrección de errores de versiones anteriores). Si bien el versionado semántico está enfocado en servicios que declaren un API público, su uso se puede traspolar durante la etapa de desarrollo para nuestras aplicaciones y sacarnos un poco de encima la decisión de cuando cambiar de versión.

Entendiendo esta necesidad, procedemos entonces a crear nuestro primer tag de versión para nuestra precaria aplicación.

```bash
╰─ docker tag my-app:latest my-app:v0.1.0
╰─ docker images | grep my-app
my-app                  latest              7fbe18f0b4d4   15 minutes ago   410MB
my-app                  v0.1.0              7fbe18f0b4d4   15 minutes ago   410MB
```

## Actualizando imagen

Para poder ver los beneficios de agregarle tags a las imagenes, podemos entonces generar una nueva versión de la misma.

Vamos a hacer algo sencillo y [agregarle información sobre PHP](../extras/02-imagenes/v0.2.0/src/index.php) a la salida que aparece en pantalla.

Luego, generamos nuevamente la imagen pero esta vez usando la versión (v0.2.0) en vez de latest

```bash
╰─ docker build -t my-app:v0.2.0 extras/02-imagenes/v0.2.0/
Sending build context to Docker daemon  3.584kB
Step 1/2 : FROM php:7.2-apache
 ---> c61d277263e1
Step 2/2 : COPY src/ /var/www/html/
 ---> 800130fbe69d
Successfully built 800130fbe69d
Successfully tagged my-app:v0.2.0
```

Si comparamos ambas salidas, nos daremos cuenta que la primera vez tardó bastante más tiempo en general la imagen, pero esta vez fue bastante más rápido. Esto se debe a que las imagenes de contenedores hacen uso de capas, y las que ya estaban descargadas localmente, están disponibles para acelerar el proceso, mientras que todo aquello que haya cambiado, invalida el resto de las capas locales de ahí en más.

Un ejemplo bien sencillo sería:

1. Tenemos una imagen base de Ubuntu
2. Nuestra empresa aplica una serie de buenas prácticas sobre esa imagen base
3. El equipo de seguridad hace un proceso de *hardening* sobre la imagen que ya tiene buenas prácticas
4. Nosotros aplicamos nuestra aplicación sobre la imagen de *hardening*.

Si nuestros cambios se aplican sobre la capa 4, reutilizaremos siempre las capas 1, 2 y 3, ya que al generar una nueva imagen, Docker se dará cuenta que dichas capas están disponibles localmente y no necesita descargarlas (esto lo hace comparando el *checksum*). Pero si la empresa decide hacer una actualización de la capa 2, donde están las buenas prácticas, dicho cambio generará un efecto dominó sobre la capa 3 y la capa 4 cuando alguien quiere generar una imagen nueva de nuestra aplicación.

```bash
╰─ docker images | grep my-app
my-app                                   v0.2.0          800130fbe69d   12 seconds ago   410MB
my-app                                   latest          15d13e8e003b   3 minutes ago    410MB
my-app                                   v0.1.0          15d13e8e003b   3 minutes ago    410MB
```

Si ahora revisamos las imágenes locales, nos daremos con que hay 3 en total. Detalle extra, dado que construimos la imagen haciendo uso de `-t my-app:v0.2.0`, notese que el `checksum` de `latest` no coincide con la última imagen, sino con la anterior. Esto lo podemos corregir fácilmente actualizando la referencia con el comando: `docker tag my-app:v0.2.0 my-app:latest`

```bash
╰─ docker tag my-app:v0.2.0 my-app:latest
╰─ docker images | grep my-app           
my-app                  latest                  800130fbe69d   8 minutes ago    410MB
my-app                  v0.2.0                  800130fbe69d   8 minutes ago    410MB
my-app                  v0.1.0                  15d13e8e003b   42 minutes ago   410MB
```

De este modo nos garantizamos que nuestros desarrolladores siempre estén probando la última versión.

## Subiendo nuestra imagen a un registry

```bash
# Login
docker login
# Tagueamos la imagen con el nombre del repo
docker tag my-app:v0.2.0 andr35/eati-dcic:v0.2.0
docker tag my-app:v0.2.0 andr35/eati-dcic:latest
# Hacemos push
docker push andr35/eati-dcic:v0.2.0
docker push andr35/eati-dcic:latest
```

## Multiple instancias de la misma imagen

Si quisieramos correr multiples instancias de esta imagen, y sólo disponemos de nuestro entorno local, podemos simplemente generar dos consolas y correr los siguientes comandos

```bash
docker run -p 80:80 my-app:latest
# Dado que el puerto 80 ya está en uso por la primera instancia, usamos 8080
# Al hacer -p 8080:80, decimos que abrimos nuestro puerto 8080 para exponer el puerto 80 del contenedor
docker run -p 8080:80 my-app:latest
```

Luego podemos visitar <http://localhost/> y <http://localhost:8080/> para acceder a cada instancia, y nos daremos cuenta por la información de `System` que ambas corren en distintos *hostnames*.

