## Arrancar un contenedor que se llame `bbdd` y que ejecute una instancia de la imagen **mariadb** para que sea accesible desde el puerto 3306. 

-  Deberá comprobar la la conexión al servidor de base de datos con el usuario creado y de la base de datos `prueba` creada automáticamente.
-  Puede acceder directamente al contenedor o utilizar algún cliente de base de datos.

## Lanzamos el comando en primer plano para poder leer los posibles mensajes de error que puedan surgir mientras trabajamos desde otra consola.**
   
```bash
docker run --name bbdd 
--env MARIADB_ROOT_PASSWORD=root 
--env MARIADB_DATABASE=prueba 
--env MARIADB_USER=invitado
--env MARIADB_PASSWORD=invitado
mariadb --port 3306
```
-  ¿Cómo correr el mismo contenedor pero en segundo plano ? Sin acceder desde otra consola, cómo accedería internamente al contenedor? **Hint: attach** 
-  Deberá comprobar que no se puede borrar la imagen `mariadb` mientras el contenedor `bbdd` está creado.

```bash
docker rmi mariadb
```

