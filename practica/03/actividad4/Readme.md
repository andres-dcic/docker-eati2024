
## Crea un contenedor con las siguientes especificaciones:

	- Utilizar la imagen base NGINX haciendo uso de la version nginx:alpine
	- Al acceder a la URL localhost:8080/index.html aparecer el mensaje NGINX
	- Persistir el fichero index.html en un volumen llamado static_content

## Crear un archivo Dockerfile con las siguientes instrucciones:

	```bash
	- Utilizar la imagen de nginx con la version requerida
	FROM nginx:alpine
	
	- Copiar el archivo index.htm de la carpeta src desde el host a la carpeta del contenedor
	COPY /src/index.html /usr/share/nginx/html
	```	

## Crear un volumen 'static_content' en el CLI de Docker mediante la siguiente instruccion

	```bash
	docker volume create static_content
	```
	

## Construir la imagen del contenedor. El nombre de la imagen es 'eati_nginx'
	```bash
	docker build -t eati_nginx. 
	```
	

## Crear el contenedor	con nombre 'eati_container' utilizando la imagen construida en el paso 3

	- Mediante el parametro -v hacemos que el volumen 'static_content' creado en el paso 2 apunte al directorio del contenedor donde se encuentra el archivo index.html. De esta forma persistimos todo el contenido del directorio /usr/share/nginx/html del contenedor en el volumen

	```bash
	docker run -d --name eati_container -v static_content:/usr/share/nginx/html -p 8080:80 demo_container
	```
	

## Acceder a la URL http://localhost/8080/index.html y comprobar que aparece la página deseada

- Pushear a DockerHub 

	- Recuerda que debes contar con una cuenta en https://hub.docker.com
	- Logueate en docker-hub desde la terminal
	- Ubicar el usuario de dockerhub, que con ese vas autheticarte
	- Documentacion [Docker](https://docs.docker.com/engine/reference/commandline/login/)

	```bash
	docker login
	docker login -u "myusername" -p "mypassword" docker.io
	#tagear la imagen
	docker tag eati_nginx user_docker_hub/eati_nginx:v1.0.0 
	#Push en el registry
	docker push  user_docker_hub/eati_nginx:v1.0.0
	```
	
	- Listo

