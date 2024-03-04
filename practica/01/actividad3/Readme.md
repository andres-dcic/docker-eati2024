
## Crear una imagen con un servidor web Apache y el contenido de la carpeta content (modificar el  Dockerfile de abajo)

```bash
docker build . -t simple-apache:new
```
## Ejecutar el contenedor con la nueva imagen

```bash
docker run -d --name myapache -p 5050:80 simple-apache:new
```

## Averiguar cuántas capas tiene la nueva imagen

```bash
#En el apartado "Layers" pueden contarse cuántas capas hay
docker inspect simple-apache:new #En el apartado "Layers" pueden contarse cuántas capas hay
docker history simple-apache:new 
docker image inspect simple-nginx -f '{{.RootFS.Layers}}'
```

Dockerfile

```bash
#Imagen que voy a utilizar como base
FROM nginx:alpine
#Etiquetado
LABEL project="EATI Docker"
#Como metadato, indicamos que el contenedor utiliza el puerto 80
EXPOSE 80
#Modificaciones sobre la imagen que he utilizado como base, en este caso alpine
COPY content/ /usr/share/nginx/html/
```
