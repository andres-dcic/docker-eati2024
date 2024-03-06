## Manejo de volúmenes

- Crea un volumen de Docker llamado mi_volumen:

```bash
docker volume create mi_volumen
```

- Ejecuta un contenedor y monta este volumen dentro del contenedor:

```bash 
docker run -d --name contenedor_volumen -v mi_volumen:/directorio_contenedor <imagen>
```

- Verifica que el volumen mi_volumen se haya creado correctamente y que los datos persistan incluso después de eliminar el contenedor.


Después de crear el volumen mi_volumen y ejecutar el contenedor, puedes verificar que el volumen se haya creado correctamente usando `docker volume ls`. Luego, puedes eliminar el contenedor y crear uno nuevo montando el volumen mi_volumen, y comprobar que los datos persisten.