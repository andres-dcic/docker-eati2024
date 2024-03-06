# Docker EATI 2024 
 <img src="/contenido/img/docker-2.png" width="100"> <img src="/contenido/img/logo.png"  width="80" height="80" style="margin-left: 30px"  >

# Intro

La idea de este curso nace luego de unas charlas con el grupo de tutores del DCIC , con el fin de promover el uso de Docker, y hacer llegar esta tecnología a estudiantes que seguramente se cruzarán con ella en sus próximos años, surgiendo la necesidad de armar un mini curso para explorar distintos contenidos relacionados al tema.
La audiencia de este material es todo aquel que tenga una base de conocimientos sobre sistemas operativos.

## Objetivo
Durante este curso aprenderán lo necesario para llevar una aplicación casera desde su concepción hasta tenerla corriendo con Docker siguiendo buenas prácticas.

## Contenido


### [01 - Fundamentos de Docker](contenido/01-contenedores.md)

- Introducción a Docker: Qué es Docker, su importancia y beneficios.
- Comprensión de la contenerización: cómo funcionan los contenedores Docker en comparación con las maquinas virtuales.
- Arquitectura Docker: Docker Engine, Docker Images, Docker Containers, Docker Registry.
- Instalación de Docker: Configuración de Docker en diferentes plataformas (Windows, macOS, Linux).
- Conceptos básicos de Docker CLI.
- `Laboratorio práctico: ejecución de su primer contenedor Docker y exploración de los comandos CLI de Docker`
- [Ejercicios prácticos](/practica/01/)

### [02 - Imágenes de contenedores Docker](contenido/02-imagenes.md)

- Imágenes de Docker: comprensión de Dockerfiles, creación de imágenes y capas personalizadas.
- Registros Docker: Registros públicos como Docker Hub, registros privados.
- `Laboratorio práctico: creación de imágenes de Docker personalizadas e implementación de aplicaciones de contenedores múltiples`
- [Ejercicios prácticos](/practica/02/)


### [03 - Volúmenes y redes de contenedores Docker](contenido/03-volumenes.md)

- Almacenamiento presistente en contenedores: volúmenes y enlaces de host
- Redes en Docker: conceptos básicos y configuración
- Comunicación entre contenedores
- Exponer y probar nuestra aplicación
- Formas de exponer servicios
- `Laboratorio práctico: configuración de redes y volúmenes para una aplicación`
 - [Ejercicios prácticos](/practica/03/)

### 04 - Docker-compose y optimización de contenedores Docker

- Orquestación con docker-compose: definición y gestión de multi-contenedor
- Uso de variables de entorno en Docker-compose
- Balanceo por medio de servicio
- Multiples servicios, mismo label
- `Laboratorio práctico: creación de imágenes de Docker personalizadas e implementación de aplicaciones de contenedores múltiples mediante Docker Compose`
- Ejercicios prácticos

### 05 - Proyecto integrador

El proyecto integrador consiste en una aplicación funcional y poder contenerizarla en cualquier entorno utilizando Docker y Docker Compose. La aplicación consta de dos  servicios que interactúan entre sí para proporcionar funcionalidades específicas.

### Esqueleto proyecto-integrador

```bash
├── docker-compose.yml
│
├── servicios/
│   ├── servicio1/
│   │   ├── Dockerfile
│   │   └── ...
│   │
│   ├── servicio2/
│   │   ├── Dockerfile
│   │   └── ...
│   │
│   └── servicioN/
│       ├── Dockerfile
│       └── ...
│
└── otros-archivos/
    └── 
```

### Detalles del proyecto
- docker-compose.yml: Este archivo definirá todos los servicios de la aplicación, así como sus interacciones y configuraciones. Aquí se especificarán los servicios, volúmenes, redes y cualquier otra configuración necesaria para el entorno de desarrollo.

- servicios/: Este directorio contendrá subdirectorios para cada uno de los servicios de la aplicación. Cada subdirectorio contendrá un Dockerfile para construir la imagen del servicio y cualquier otro archivo necesario para su funcionamiento.

- otros-archivos/: En este directorio se pueden incluir otros archivos necesarios para la aplicación, como scripts de inicialización, configuraciones, archivos estáticos, etc.


### Ejemplo de Servicios
- Servicio 1 (Frontend): Un servicio que sirve la interfaz de usuario de la aplicación web. Puede estar basado en un framework como React, Angular o Vue.js.

- Servicio 2 (Backend API): Un servicio que proporciona una API para que el frontend interactúe. Puede estar desarrollado con Node.js, Python Flask, Ruby on Rails, etc.

- Servicio N (Base de Datos): Un servicio que aloja la base de datos de la aplicación. Puede ser MongoDB, MySQL, PostgreSQL, etc.


