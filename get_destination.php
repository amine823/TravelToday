<?php
header('Content-Type: application/json');
require_once 'config.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $conn = getConnection();
    
    $sql = "SELECT * FROM destinations WHERE id = $id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $destination = $result->fetch_assoc();
        echo json_encode($destination);
    } else {
        echo json_encode(['error' => 'Destination not found']);
    }
    
    closeConnection($conn);
} else {
    echo json_encode(['error' => 'ID missing']);
}
?>
