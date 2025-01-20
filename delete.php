<?php

include './config.php';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = null;

    // ตรวจสอบ Query String
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = intval($_GET['id']); // แก้จาก $_GET['di'] เป็น $_GET['id']
    }

    // ตรวจสอบ JSON Payload
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['id']) && is_numeric($input['id'])) {
        $id = intval($input['id']);
    }

    // ตรวจสอบว่ามี ID หรือไม่
    if (!$id) {
        echo json_encode(["status" => "error", "message" => "Invalid or missing ID."]);
        exit;
    }

    // Query the database
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $filePath = $row['image'];

        // ลบไฟล์ภาพ
        if (file_exists($filePath) && !unlink($filePath)) {
            echo json_encode(["status" => "error", "message" => "Failed to delete the image file."]);
            exit;
        }

        // ลบข้อมูลในฐานข้อมูล
        $deleteStmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $deleteStmt->bind_param("i", $id);

        if ($deleteStmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Product deleted successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to delete the database record."]);
        }
        $deleteStmt->close();

    } else {
        echo json_encode(["status" => "error", "message" => "Product not found."]);
    }
    $stmt->close();

} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}

?>
