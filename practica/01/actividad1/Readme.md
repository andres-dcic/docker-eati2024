## Crear un contenedor a partir de la imagen nginx , el contenedor se debe llamar servidor_web y se debe acceder a él utilizando el puerto 8181 del ordenador donde tengas instalado docker.

- Deberá comprobar la creación  del contenedor y ver que el contenedor está funcionando.
- Deberá comprobar el acceso al servidor web utilizando un navegador web (recuerda que tienes que acceder a la ip del ordenador donde  tengas instalado docker)
- Deberá comprobar las imágenes que tienes en tu registro local.
- Deberá remover el contenedor (recuerda que antes debe estar parado el contenedor).


```bash
docker run -d --name servidor_web -p 8181:80 nginx 
docker ps
docker images
docker stop servidor_web
docker ps -a
docker rm servidor_web
```





