<?php
// =============================================
// proveedores.php - API REST CRUD Proveedores
// =============================================
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];
$conn = getConnection();

switch ($method) {

    // LISTAR
    case 'GET':
        $sql = "SELECT * FROM proveedor ORDER BY id_proveedor ASC";
        $result = $conn->query($sql);
        $proveedores = [];
        while ($row = $result->fetch_assoc()) {
            $proveedores[] = $row;
        }
        echo json_encode(['success' => true, 'data' => $proveedores]);
        break;

    // CREAR 
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $razonsocial = $conn->real_escape_string($data['razonsocial']);
        $direccion   = $conn->real_escape_string($data['direccion']);
        $telefono    = $conn->real_escape_string($data['telefono']);

        $sql = "INSERT INTO proveedor (razonsocial, direccion, telefono) VALUES ('$razonsocial', '$direccion', '$telefono')";
        if ($conn->query($sql)) {
            echo json_encode(['success' => true, 'message' => 'Proveedor creado', 'id' => $conn->insert_id]);
        } else {
            echo json_encode(['success' => false, 'message' => $conn->error]);
        }
        break;

    // ACTUALIZAR
    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $id          = intval($data['id_proveedor']);
        $razonsocial = $conn->real_escape_string($data['razonsocial']);
        $direccion   = $conn->real_escape_string($data['direccion']);
        $telefono    = $conn->real_escape_string($data['telefono']);

        $sql = "UPDATE proveedor SET razonsocial='$razonsocial', direccion='$direccion', telefono='$telefono' WHERE id_proveedor=$id";
        if ($conn->query($sql)) {
            echo json_encode(['success' => true, 'message' => 'Proveedor actualizado']);
        } else {
            echo json_encode(['success' => false, 'message' => $conn->error]);
        }
        break;
    // ELIMINAR
    case 'DELETE':
        $id = intval($_GET['id']);
        $sql = "DELETE FROM proveedor WHERE id_proveedor=$id";
        if ($conn->query($sql)) {
            echo json_encode(['success' => true, 'message' => 'Proveedor eliminado']);
        } else {
            echo json_encode(['success' => false, 'message' => $conn->error]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
        break;
}

$conn->close();
?>