<?php
// Incluir el archivo de conexión a la base de datos
require(dirname(__FILE__) . "/core/conn.php");

// Crear una nueva instancia de conexión a la base de datos
$db = new db();
$Database = $db->db;

// Verificar si la conexión a la base de datos fue exitosa
if (!$Database) {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(["code" => 500, "message" => "Error en la conexión a la base de datos"]);
    exit;
}

// Establecer el tipo de contenido como JSON
header("Content-Type: application/json");

// Verificar si existe PATH_INFO
$PATHINFO = isset($_SERVER['PATH_INFO']) ? $_SERVER["PATH_INFO"] : "/";

// Buscar el ID de la solicitud
$PATHparts = explode('/', $PATHINFO);
$ID = ($PATHINFO !== '/') ? end($PATHparts) : null;

// Determinar el método de solicitud y manejarlo en consecuencia
switch ($_SERVER['REQUEST_METHOD']) {
    case "POST":
        // Manejar la solicitud POST
        echo insertar($Database);
        break;

    case "GET":
        // Manejar la solicitud GET
        echo consultar($Database, $ID);
        break;

    case "DELETE":
        // Manejar la solicitud DELETE
        echo borrar($Database, $ID);
        break;

    case "PUT":
        // Manejar la solicitud PUT
        echo actualizar($Database, $ID);
        break;

    default:
        // Manejar métodos de solicitud no soportados
        header("HTTP/1.1 400 Bad Request");
        echo json_encode(["code" => 400, "message" => "Método de solicitud no soportado"]);
        break;
}

/**
 * Consulta los usuarios en la base de datos.
 *
 * @param PDO $Conn Instancia de conexión a la base de datos.
 * @param int|null $id ID del usuario a consultar. Si es null, consulta todos los usuarios.
 * @return string Resultado de la consulta en formato JSON.
 */
function consultar(PDO $Conn, ?int $id)
{
    $resultado = [];

    try {
        // Preparar la consulta SQL según si se proporciona un ID o no
        $sql = ($id === null) ? "SELECT * FROM usuarios" : "SELECT * FROM usuarios WHERE id = :id";
        $sentencia = $Conn->prepare($sql);

        // Si se proporciona un ID, enlazar el parámetro
        if ($id !== null) {
            $sentencia->bindValue(":id", $id, PDO::PARAM_INT);
        }

        // Ejecutar la consulta e informar el resultado
        if ($sentencia->execute()) {
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        $resultado = ["error" => "Error en la consulta: " . $e->getMessage()];
    }

    return json_encode($resultado);
}

/**
 * Inserta un nuevo usuario en la base de datos.
 *
 * @param PDO $Conn Instancia de conexión a la base de datos.
 * @return string Resultado de la inserción en formato JSON.
 */
function insertar(PDO $Conn)
{
    // Obtener los datos del cuerpo de la solicitud
    $data_post = json_decode(file_get_contents("php://input"), true);

    // Validar que el campo 'username' esté presente y no esté vacío
    if (!isset($data_post['username']) || empty($data_post['username'])) {
        return json_encode(['error' => 1, 'message' => "Datos vacíos (username)"]);
    }

    // Preparar la consulta de inserción
    $sql = "INSERT INTO usuarios (nombre) VALUES (:username)";
    $sentencia = $Conn->prepare($sql);
    $sentencia->bindValue(":username", $data_post['username'], PDO::PARAM_STR);

    // Ejecutar la consulta e informar el resultado
    if (!$sentencia->execute()) {
        return json_encode(['error' => 1, 'message' => "Error en la base de datos"]);
    } else {
        return json_encode(['error' => 0, 'message' => "Insertado correctamente"]);
    }
}

/**
 * Borra un usuario de la base de datos.
 *
 * @param PDO $Conn Instancia de conexión a la base de datos.
 * @param string $id ID del usuario a borrar.
 * @return string Resultado de la eliminación en formato JSON.
 */
function borrar(PDO $Conn, string $id)
{
    // Validar que el ID esté presente
    if (!isset($id) || empty($id)) {
        return json_encode(['error' => 1, 'message' => "Datos vacíos (ID)"]);
    }

    // Preparar la consulta de eliminación
    $sql = "DELETE FROM usuarios WHERE id = :id";
    $sentencia = $Conn->prepare($sql);
    $sentencia->bindValue(":id", $id, PDO::PARAM_STR);

    // Ejecutar la consulta e informar el resultado
    if (!$sentencia->execute()) {
        return json_encode(['error' => 1, 'message' => "Error en la base de datos"]);
    } else {
        return json_encode(['error' => 0, 'message' => "Borrado correctamente"]);
    }
}

/**
 * Actualiza un usuario en la base de datos.
 *
 * @param PDO $Conn Instancia de conexión a la base de datos.
 * @param string $id ID del usuario a actualizar.
 * @return string Resultado de la actualización en formato JSON.
 */
function actualizar(PDO $Conn, string $id)
{
    // Obtener los datos del cuerpo de la solicitud
    $data_post = json_decode(file_get_contents("php://input"), true);

    // Validar que el campo 'username' esté presente y no esté vacío
    if (!isset($data_post['username']) || empty($data_post['username'])) {
        return json_encode(['error' => 1, 'message' => "Datos vacíos (username)"]);
    }

    // Validar que el ID esté presente y no esté vacío
    if (!isset($id) || empty($id)) {
        return json_encode(['error' => 1, 'message' => "Datos vacíos (ID)"]);
    }

    // Preparar la consulta de actualización
    $sql = "UPDATE usuarios SET nombre = :username WHERE id = :id";
    $sentencia = $Conn->prepare($sql);
    $sentencia->bindValue(":username", $data_post['username'], PDO::PARAM_STR);
    $sentencia->bindValue(":id", $id, PDO::PARAM_STR);

    // Ejecutar la consulta e informar el resultado
    if (!$sentencia->execute()) {
        return json_encode(['error' => 1, 'message' => "Error en la base de datos"]);
    } else {
        return json_encode(['error' => 0, 'message' => "Editado correctamente"]);
    }
}
