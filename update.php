<?php
include('config.php'); // ดึงไฟล์เชื่อมต่อฐานข้อมูลมาใช้
$message = [];

try {
    $dataJSON = $_POST;
    error_log(json_encode($_POST)); // บันทึกข้อมูลใน log ของเซิร์ฟเวอร์

    // ตรวจสอบว่าได้รับ ID ของผลิตภัณฑ์ที่ต้องการอัปเดตหรือไม่
    if (!isset($dataJSON['id'])) {
        throw new EXCEPTION("ไม่ได้ระบุ ID ของผลิตภัณฑ์");
    }

    $id = $dataJSON['id'];
    $updateFields = [];

    // ตรวจสอบว่าได้รับค่า name และเพิ่มในคำสั่ง SQL หากมี
    if (!empty($dataJSON['name'])) {
        $name = $dataJSON['name'];
        $updateFields[] = "`name` = '$name'";
    }

    // ตรวจสอบว่าได้รับค่า price และเพิ่มในคำสั่ง SQL หากมี
    if (!empty($dataJSON['price'])) {
        $price = $dataJSON['price'];
        $updateFields[] = "`price` = '$price'";
    }

    // ตรวจสอบว่าได้รับค่า quantity และเพิ่มในคำสั่ง SQL หากมี
    if (!empty($dataJSON['quantity'])) {
        $quantity = $dataJSON['quantity'];
        $updateFields[] = "`quantity` = '$quantity'";
    }

    // ตรวจสอบว่าได้รับค่า type และเพิ่มในคำสั่ง SQL หากมี
    if (!empty($dataJSON['type'])) {
        $type = $dataJSON['type'];
        $updateFields[] = "`type` = '$type'";
    }

    // ตรวจสอบว่ามีการอัปโหลดรูปภาพใหม่หรือไม่
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        // ดึงชื่อไฟล์รูปภาพเก่าจากฐานข้อมูล
        $sqlSelect = "SELECT `image` FROM `products` WHERE `id` = '$id'";
        $result = mysqli_query($conn, $sqlSelect);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $oldImagePath = $row['image'];

            // ลบรูปภาพเก่าถ้ามี
            if (!empty($oldImagePath) && file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        // บันทึกรูปภาพใหม่
        $image = $_FILES['image'];
        $imagePath = 'upload/' . basename($image['name']);

        if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
            throw new EXCEPTION("อัปโหลดรูปภาพไม่สำเร็จ");
        }

        $updateFields[] = "`image` = '$imagePath'";
    }

    // หากไม่มีฟิลด์ใดที่ต้องอัปเดต ให้แจ้งเตือน
    if (empty($updateFields)) {
        throw new EXCEPTION("ไม่มีข้อมูลที่เปลี่ยนแปลง");
    }

    // รวมคำสั่ง SQL
    $sql = "UPDATE `products` SET " . implode(", ", $updateFields) . " WHERE `id` = '$id'";

    // ดำเนินการอัปเดตข้อมูล
    $qr_update = mysqli_query($conn, $sql);

    if ($qr_update) {
        http_response_code(200);
        $message['status'] = "อัปเดตข้อมูลสำเร็จ";
    } else {
        $message['status'] = "อัปเดตข้อมูลไม่สำเร็จ: " . mysqli_error($conn);
    }
} catch (EXCEPTION $e) {
    $message['status'] = $e->getMessage();
}

echo json_encode($message);
?>
