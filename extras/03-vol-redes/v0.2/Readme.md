## Crear una red nueva llamada `mi_red`

```bash
docker network create mi_red
```

## Ejecutar un contenedor de base de datos, basado en una imagen alpine que utilice la red `mi_red`

```bash
docker run -it -d --name db --network mi_red alpine
```

## Ejecutar un contenedor web , basado en una imagen alpine que utilice la misma red `mi_red`


```bash
docker run -ti -d --name web --network mi_red alpine
```

## Entrar a cada uno de los contenedores y probar que se pueden comunicar

