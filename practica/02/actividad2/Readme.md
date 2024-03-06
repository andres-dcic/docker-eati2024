
## El siguiente `Dockerfile` está incompleto y debe modificarse para producir los resultados siguientes.
(`Sugerencia, es posible que deba agregar una variable de entorno`).

## Construir la imagen 
```
docker build -t testimage .
```

## Correr y setear la variable de entorno `myhost`
```
docker run -e myhost=host1 testimage
```

## Deberías ver la siguiente salida
```
    host1
```

## Sin ninguna variable de entorno tú deberías ver la siguiente salida
```
    docker run testimage
    testhost
```

