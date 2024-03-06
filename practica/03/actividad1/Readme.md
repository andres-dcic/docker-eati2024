## Manejo de volúmenes

- Crea un directorio en tu máquina host, por ejemplo, /mi_directorio.

- Ejecuta un contenedor y monta este volumen dentro del contenedor::

```bash 
docker run -d --name mi_contenedor -v /mi_directorio:/directorio_contenedor <imagen>
```

- Accede al contenedor y verifica que el directorio /directorio_contenedor contenga los mismos archivos que /mi_directorio en el host.


Después de ejecutar el contenedor y montar el directorio del host en el contenedor, puedes acceder al contenedor usando `docker exec` y verificar que los archivos en /mi_directorio del host están presentes en /directorio_contenedor del contenedor.