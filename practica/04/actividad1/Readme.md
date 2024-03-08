# De acuerdo al Dockerfile en la carpeta /src, buildear, ejecutar y comprobar el acceso al contenedor mediante los siguiente comandos:

```bash
docker build -t eati_sincompose .
docker run -p 5000:5000 -d eati_sincompose
curl localhost:5000
```

-  Crear un archivo docker-compose.yml que defina un servicio llamado `eati` y realice lo mismo que los pasos anteriores.

-  Ejecute el servicio usando Docker Compose con el comando docker-compose up.




