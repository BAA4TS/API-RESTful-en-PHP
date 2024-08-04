# API RESTful en PHP

Este repositorio contiene una implementación básica de una API RESTful en PHP. La API permite realizar operaciones CRUD (Crear, Leer, Actualizar y Eliminar) sobre usuarios en una base de datos MySQL.

## Estructura del Proyecto

```
C:.
|   index.php
|
\---core
        conn.php
```

- **index.php**: El punto de entrada de la API. Maneja las solicitudes HTTP y dirige a la función adecuada según el método de solicitud (`POST`, `GET`, `PUT`, `DELETE`).
- **core/conn.php**: Contiene la clase `db` que maneja la conexión a la base de datos MySQL.

## Requisitos

- PHP 7.4 o superior
- MySQL
- Extensión PDO para PHP

## Instalación

1. **Clona el repositorio**:

    ```bash
    git clone https://github.com/tu_usuario/tu_repositorio.git
    ```

2. **Configura la base de datos**:
   - Crea una base de datos MySQL llamada `testapi`.
   - Crea una tabla `usuarios` con la siguiente estructura:

     ```sql
     CREATE TABLE usuarios (
         id INT AUTO_INCREMENT PRIMARY KEY,
         nombre VARCHAR(255) NOT NULL
     );
     ```

3. **Configura la conexión a la base de datos**:
   - Asegúrate de que el archivo `core/conn.php` tenga los detalles correctos para conectarse a tu base de datos MySQL (nombre de usuario, contraseña, etc.).

4. **Ejecuta el servidor PHP**:
   - Navega al directorio del proyecto y ejecuta el servidor integrado de PHP:

     ```bash
     php -S localhost:8000
     ```

## Uso

### Consultar Usuarios

- **Método**: `GET`
- **URL**: `http://localhost:8000/`
  - **Descripción**: Obtiene todos los usuarios.
- **URL con ID**: `http://localhost:8000/{id}`
  - **Descripción**: Obtiene un usuario específico por ID, donde `{id}` es el identificador del usuario que deseas consultar.

**Ejemplo de solicitud GET usando `curl`:**

```bash
curl -X GET http://localhost:8000/
```

```bash
curl -X GET http://localhost:8000/1
```

En este ejemplo, `1` es el ID del usuario que deseas consultar.

### Insertar Usuario

- **Método**: `POST`
- **URL**: `http://localhost:8000/`
- **Descripción**: Inserta un nuevo usuario.
- **Cuerpo de la Solicitud**:
  ```json
  {
      "username": "nombre_del_usuario"
  }
  ```

**Ejemplo de solicitud POST usando `curl`:**

```bash
curl -X POST http://localhost:8000/ \
     -H "Content-Type: application/json" \
     -d '{"username": "nombre_del_usuario"}'
```

### Actualizar Usuario

- **Método**: `PUT`
- **URL**: `http://localhost:8000/{id}`
  - **Descripción**: Actualiza un usuario existente por ID, donde `{id}` es el identificador del usuario que deseas actualizar.
- **Cuerpo de la Solicitud**:
  ```json
  {
      "username": "nuevo_nombre"
  }
  ```

**Ejemplo de solicitud PUT usando `curl`:**

```bash
curl -X PUT http://localhost:8000/1 \
     -H "Content-Type: application/json" \
     -d '{"username": "nuevo_nombre"}'
```

En este ejemplo, `1` es el ID del usuario que deseas actualizar.

### Borrar Usuario

- **Método**: `DELETE`
- **URL**: `http://localhost:8000/{id}`
  - **Descripción**: Borra un usuario específico por ID, donde `{id}` es el identificador del usuario que deseas eliminar.

**Ejemplo de solicitud DELETE usando `curl`:**

```bash
curl -X DELETE http://localhost:8000/1
```

En este ejemplo, `1` es el ID del usuario que deseas borrar.

## Respuestas

Las respuestas de la API están en formato JSON y contienen mensajes sobre el éxito o fracaso de la solicitud.

### Ejemplo de Respuesta Exitosa

```json
{
    "error": 0,
    "message": "Operación realizada correctamente"
}
```

### Ejemplo de Respuesta con Error

```json
{
    "error": 1,
    "message": "Descripción del error"
}
```

## Errores Comunes

- **500 Internal Server Error**: Error en la conexión a la base de datos.
- **400 Bad Request**: Método de solicitud no soportado o datos inválidos en la solicitud.