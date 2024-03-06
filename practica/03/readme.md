## Crear una red personalizada llamada "eati" y luego crear dos contenedores basados en la imagen alpine y sobre la misma red. Comprobar que ambos se puedan  comunicar.


docker network create eati


docker run -d --name contenedor1 --network eati <imagen>
docker run -d --name contenedor2 --network eati <imagen>


Accede a uno de los contenedores y comprueba si puedes comunicarte con el otro contenedor a través de la red:

```bash
docker exec -it contenedor1 sh
ping contenedor2
```