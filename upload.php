<?php
include('config.php'); 
$message = [];

try {
    if (!isset($_FILES['image']) || $_FILES['image']['error'] != UPLOAD_ERR_OK) {
        throw new EXCEPTION("รูปภาพไม่ถูกต้อง");
    }

    $image = $_FILES['image'];
    $imagePath = 'upload/' . basename($image['name']);

    // ย้ายไฟล์รูปภาพไปยังโฟลเดอร์ upload
    if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
        throw new EXCEPTION("อัปโหลดรูปภาพไม่สำเร็จ");
    }

    $dataJSON = $_POST;

    $id_product = isset($dataJSON['id_product']) ? $dataJSON['id_product'] : null;
    $name = isset($dataJSON['name']) ? $dataJSON['name'] : null;
    $price = isset($dataJSON['price']) ? $dataJSON['price'] : null;
    $quantity = isset($dataJSON['quantity']) ? $dataJSON['quantity'] : null;
    $type = isset($dataJSON['type']) ? $dataJSON['type'] : null;

    // ตรวจสอบว่าค่าที่จำเป็นมีครบหรือไม่
    if (!$id_product || !$name || !$price || !$quantity || !$type) {
        throw new EXCEPTION("ข้อมูลไม่ครบถ้วน");
    }

    // สร้างคำสั่ง SQL สำหรับเพิ่มข้อมูล
    $sql = "INSERT INTO `products` (`id_product`, `name`, `price`, `image`, `quantity`, `type`, `created_at`, `updated_at`) 
            VALUES ('$id_product', '$name', '$price', '$imagePath', '$quantity', '$type', current_timestamp(), current_timestamp());";

    // ดำเนินการเพิ่มข้อมูลในฐานข้อมูล
    $qr_insert = mysqli_query($conn, $sql);

    if ($qr_insert) {
        http_response_code(201);
        $message['status'] = "เพิ่มข้อมูลสำเร็จ";
    } else {
        throw new EXCEPTION("เพิ่มข้อมูลไม่สำเร็จ: " . mysqli_error($conn));
    }
} catch (EXCEPTION $e) {
    $message['status'] = $e->getMessage();
}

echo json_encode($message);

?>
