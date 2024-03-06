## Manejo de volúmenes

- Crea un volumen de Docker llamado datos_compartidos:

```bash
docker volume create datos_compartidos
```

- Ejecuta dos contenedores y monta el volumen datos_compartidos en ambos contenedores:

```bash
docker run -d --name contenedor1 -v datos_compartidos:/datos <imagen> 
```

```bash
docker run -d --name contenedor2 -v datos_compartidos:/datos <imagen> 
```

- Verifica que los dos contenedores puedan acceder y modificar los mismos archivos en el volumen datos_compartidos.


Después de ejecutar los dos contenedores y montar el volumen datos_compartidos en ambos, puedes verificar que los contenedores puedan acceder y modificar los mismos archivos en el volumen datos_compartidos, creando archivos en uno de los contenedores y verificando su presencia en el otro contenedor.