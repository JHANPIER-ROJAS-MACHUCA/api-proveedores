<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Manejar preflight CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

require_once 'config.php';
$conn = getConnection();
$method = $_SERVER['REQUEST_METHOD'];
$inputData = json_decode(file_get_contents('php://input'), true);

switch ($method) {

    case 'GET':
        if (isset($_GET['id'])) {
            $id  = intval($_GET['id']);
            $sql = "SELECT * FROM proveedor WHERE id_proveedor = $id";
        } else {
            $sql = "SELECT * FROM proveedor ORDER BY id_proveedor ASC";
        }
        $result = $conn->query($sql);
        if (!$result) {
            echo json_encode(['success' => false, 'message' => $conn->error]);
            break;
        }
        $proveedores = [];
        while ($row = $result->fetch_assoc()) {
            $proveedores[] = $row;
        }
        echo json_encode(['success' => true, 'data' => $proveedores]);
        break;

    case 'POST':
        $razonsocial = $conn->real_escape_string($inputData['razonsocial'] ?? '');
        $direccion   = $conn->real_escape_string($inputData['direccion']   ?? '');
        $telefono    = $conn->real_escape_string($inputData['telefono']    ?? '');

        if (empty($razonsocial)) {
            echo json_encode(['success' => false, 'message' => 'La razón social es obligatoria']);
            break;
        }

        $sql = "INSERT INTO proveedor (razonsocial, direccion, telefono) VALUES ('$razonsocial', '$direccion', '$telefono')";
        if ($conn->query($sql)) {
            echo json_encode(['success' => true, 'message' => 'Proveedor creado', 'id' => $conn->insert_id]);
        } else {
            echo json_encode(['success' => false, 'message' => $conn->error]);
        }
        break;

    case 'PUT':
        $id          = intval($inputData['id_proveedor'] ?? 0);
        $razonsocial = $conn->real_escape_string($inputData['razonsocial'] ?? '');
        $direccion   = $conn->real_escape_string($inputData['direccion']   ?? '');
        $telefono    = $conn->real_escape_string($inputData['telefono']    ?? '');

        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'ID de proveedor inválido']);
            break;
        }
        if (empty($razonsocial)) {
            echo json_encode(['success' => false, 'message' => 'La razón social es obligatoria']);
            break;
        }

        $sql = "UPDATE proveedor SET razonsocial='$razonsocial', direccion='$direccion', telefono='$telefono' WHERE id_proveedor=$id";
        if ($conn->query($sql)) {
            echo json_encode(['success' => true, 'message' => 'Proveedor actualizado']);
        } else {
            echo json_encode(['success' => false, 'message' => $conn->error]);
        }
        break;

    case 'DELETE':
        $id = intval($inputData['id_proveedor'] ?? 0);

        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'ID de proveedor inválido']);
            break;
        }

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
