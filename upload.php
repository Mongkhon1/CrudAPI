<?php

include('config.php'); // ดึงไฟล์เชื่อมต้อฐานข้อมูลมาใช้
$message = [];
try {
    if(!isset($_FILES['image']) || $_FILES['image']['error']!=UPLOAD_ERR_OK){
        throw new EXCEPTION ("รุปภาพไม่ถูกต้อง");
    }

$image =$_FILES['image'];
$imagePath = 'upload/' . basename($image['name']);
if(!move_uploaded_file($image['tmp_name'],$imagePath)){
    throw new EXCEPTION("อัปโหลดรูปภาพไม่สำเร็จ");
}

$dataJSON = $_POST;
$name = $dataJSON['name'];
$price = $dataJSON["price"];

$sql = "INSERT INTO `products` (`name`, `price`, `image`, `created_at`, `updated_at`) VALUES ('$name', '$price', '$imagePath', current_timestamp(), current_timestamp());";

$qr_insert = mysqli_query($conn,$sql);
if($qr_insert){
    http_response_code(201);
    $message['status'] = "เพิ่มข้อมูลสำเร็จ";
}else{
    throw new EXCEPTION("เพิ่มข้อมูลไม่สำเร็จ:". mysqli_error($conn));
    }
} catch (EXCEPTION $e) {
    $message['status'] = $e->getMessage();
}

echo json_encode($message);

?>