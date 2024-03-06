 
## Prerrequisito
- Crear una cuenta en Docker Hub. 
- https://hub.docker.com/
- **Important Note**: En los sigueientes pasos **<accountdockerhub>** es la cuenta de Docker Hub  


## Correr el contenedor base de Nginx
- Acceder a la  URL http://localhost
```
docker run --name mynginxdefault -p 80:80 -d nginx
docker ps
docker stop mynginxdefault
```

## Crear un DockerFile y copiar el index.html al directorio por defecto de Nginx
- **Dockerfile**
```
FROM nginx
COPY index.html /usr/share/nginx/html

```

## Buildear la imagen y correrla 
```
docker build -t mynginx_image1:v1 .
docker run --name mynginx1 -p 80:80 -d mynginx_image1:v1

Reemplazar con el ID de DockerHub
docker build -t <your-docker-hub-id>/mynginx_image1:v1 .
docker run --name mynginx1 -p 80:80 -d <your-docker-hub-id>/mynginx_image1:v1
```

## Tagguear la imagen y hacer un push a Docker Hub 
```
docker images

Reemplazar con el ID de DockerHub
docker tag <your-docker-hub-id>/mynginx_image1:v1 <your-docker-hub-id>/mynginx_image1:v1-release
docker push <your-docker-hub-id>/mynginx_image1:v1-release
```

## Verificar tú imagen en DockerHub
- Login a DockerHub y verificar la imagen recien pusheada 
- Url: https://hub.docker.com/repositories
