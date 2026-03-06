<?php

define('DB_HOST', 'shortline.proxy.rlwy.net');
define('DB_PORT', '44561');
define('DB_USER', 'root');
define('DB_PASS', 'mXfpiJzTvVZwBSQDHlsXZLHyZGkJuSQT');
define('DB_NAME', 'railway');

function getConnection() {
    $conn = new mysqli(DB_HOST . ':' . DB_PORT, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(['error' => 'Error de conexión: ' . $conn->connect_error]);
        exit();
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}
?>