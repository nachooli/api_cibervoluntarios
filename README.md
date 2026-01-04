# API Motos – Prueba Técnica

API REST para la gestión de motocicletas, desarrollada como prueba técnica utilizando Symfony 6.4 y API Platform,
_**priorizando las herramientas que ofrece el propio framework**_.

## Descripción

La aplicación expone una API REST pública para realizar operaciones CRUD mediante una única entidad Moto.
Al estar construida la API mediante API Platform permite:
- Generación automática de los endpoints sin necesidad de controladores.
- Serialización por grupos

(No se ha desarrollado gestión de permisos ni autentificación por no complicar la prueba ya que tampoco se indica en el enunciado)

## Stack tecnológico

- PHP 8.2
- Symfony 6.4
- Composer para dependencias
- Doctrine ORM
- PostgreSQL para bbdd
- Docker
- PHPUnit para tests

## Entidad Moto

Se ha construido la entidad con los campos indicados en el enunciado de la prueba. A destacar los siguientes puntos:

- Campo "edicionLimitada":
    - Obligatorio solo en POST
    - No modificable en PUT/PATCH
    - Esto se ha realizado mediante grupos de deserialización. Como extra se podría añadir _allow_extra_attributes: false_ en los grupos de PUT/PATCH para evitar el añadido de este campo en el json.
    - Aunque se indica de manera general en _normalizationContext_ a _read_ para todos los métodos del CRUD, al tener el _denormalizationContext_ explícito en cada método, es necesario también indicar explicitamente el _normalizationContext_ a _read_ en cada método para que se pueda leer siempre tal y como indica el enunciado.
- Campos "updateAt" y "createdAt":
  - En vez de gestionarlo mediante Doctrine, se gestionan mediante un State PRocessor propio de StatePlatform, sobrescribiendo el persist processor por defecto.
  - Se hace así para mantener la lógica alienada con el framework y centralizar la lógica de la persistencia.
- Campo "tipo":
  - Adicionalmente por mantener buenas prácticas, se ha creado un MotoTipoEnum para almacenar los tipos de Moto en constantes para su uso en tests, datos de ejemplo, etc

# Arrancar proyecto

Una vez clonado el proyecto, nos moveremos a la carpeta donde se encuentra la base del proyecto:
- _cd motos_api_ (se ha separado para englobar el proyecto y así separarlo de la explicación del mismo en el README; gustos personales)
- Comandos del make:
  - **init-project**: Construye y arrancar los contenedores necesarios de Docker
  - **load-fixtures-data**: Persiste los datos de ejemplo (son 4 motos de ejemplo)
  - **run-tests**: Lanza los tests
  - Opcionales:
    - **update-database-schema**: Actualiza la bbdd
    - **clear-cache**: Limpia la cache de symfony por si es necesario
  
- Para acceder a la API y los métodos del CRUD: http://localhost:8081/api

## Tests

Se han implementado tests simples para cada uno de los métodos del CRUD.

Para lanzarlos: _make run-tests_ tal y como está indicado arriba.

Se realizan en un base de datos de test para no interferir con la principal.



# Escenarios definidos en el correo

_1- Se debe controlar que como máximo haya diez motos de edición limitada, en caso de añadir una más, la más antigua se desmarcara como edición limitada._ 

Cada vez que se haga un POST de una Moto con 'edicionLimitada = true', se deberán contar las motos con este mismo campo a TRUE.
Si hay más de 10, obtener la más antigua mediante el campo 'createdBy' y marcar esta como false.
Esto se hará mediante el Processor para seguir con la lógica de API Platform, aunque también se podría hacer mediante eventos y subscribers.
Las clases usadas serían el repositorio de Moto para la búsqueda de la moto más antigua, y el MotoProcessor para lo indicado previamente.

_2- Enviar un email notificando a los clientes cuando se cree una moto del tipo Classic._

Cada vez que se haga un POST, detectar en el MotoProcessor si el campo 'tipo === "Classic"', enviar un mensaje mediante
el Mailer de Symfony. Al ser un email, lo ideal sería capturarlo con un Messenger para hacerlo asíncrono.
Las clases usadas serían el MotoProcessor, la interfaz del Mailer y el MessageBus para la asincronía.

* Si surge cualquier duda podéis consultarme al correo!
    