## Creamos un volumen llamado "datos-del-banco"

```bash
docker volume create datos-del-banco
```

## Chequeamos si fue creado

```bash
docker volume ls
```

## Hacemos referencia mediante un contenedor al volumen creado

```bash
docker run --name db1 -v datos-del-banco:/var/lib/mysql
```


De esta forma, le estamos diciendo a Docker crear un contenedor y asociar el directorio /var/lib/mysql con el volumen `datos-del-banco`. Nuestra aplicación necesita una contraseña para iniciar sesión en la base de datos, 
así que digamos que este contenedor, en su entorno  (-e, environment), tendrá la contraseña __alura__:

```bash
docker run --name db1 -v datos-del-banco:/var/lib/mysql -e MYSQL_ROOT_PASSWORD=alura
```

Genial, ahora solo tenemos que especificar la imagen que creará nuestro container, en nuestro caso, es la imagen de MySQL:

```bash
docker run --name db -v datos-del-banco:/var/lib/mysql -e MYSQL_ROOT_PASSWORD=alura mysql
```
Ahora bien, nuestro contenedor ya se está ejecutando, hagamos algunas pruebas 
para ver como funciona nuestro volumen. En otra pestaña en la terminal, 
digamos a docker que queremos ejecutar algunos comandos en un container (container exec) con una terminal interactiva (-ti) en nuestro container bd con el interpretador `/bin/bash:`


```bash
docker container exec -it db /bin/bash
``` 
```bash
bash-4.4# mysql -u root -p
Enter password: 
Welcome to the MySQL monitor.  Commands end with ; or \g.
Your MySQL connection id is 10
Server version: 8.3.0 MySQL Community Server - GPL

Copyright (c) 2000, 2024, Oracle and/or its affiliates.

Oracle is a registered trademark of Oracle Corporation and/or its
affiliates. Other names may be trademarks of their respective
owners.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

mysql> 
```

## Creamos una base de datos `tienda` dentro de ese contenedor el cual guarda los datos en el volumen `datos-del-banco`

```bash
mysql> create database tienda;
Query OK, 1 row affected (0.00 sec)

mysql> exit
``` 


## Salimos de nuestro contenedor y luego le  decimos a docker que detenga nuestro contenedor (docker stop db y luego le indicamos removerlo (docker rm db)

## Listo!! Incluso habiendo removido el contenedor, la información debe haber sido guardada en el volumen


## Ahora, volvemos a crear un nuevo contenedor llamado `db2` y haremos referencia
al mismo volumen `datos-del-banco` en el directorio /var/lib/mysql

```bash
docker  run --name db2 -v datos-del-banco:/var/lib/mysql -e MYSQL_ROOT_PASSWORD=alura -d mysql
```

## Veamos ahora si la información fue persistente 

```bash
docker  exec -it db2 /bin/bash
```


```bash

mysql> show databases;
+--------------------+
| Database           |
+--------------------+
| information_schema |
| mysql              |
| performance_schema |
| sys                |
| tienda             |
+--------------------+
5 rows in set (0.01 sec)

mysql>
```

Observar que nuestra base de datos de la `tienda` aparece normalmente, es decir, pudimos mantener los datos 
incluso después de que se eliminó el contenedor `db1`

## Por último, ¿Cómo eliminamos el volumen?

```bash
docker volume rm datos-del-banco
```

Recordar que para remover un volumen, ningún contanedor puede estar usándolo.
