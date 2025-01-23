<?php
include('config.php');

$table = "CREATE TABLE products(
    id int(6) AUTO_INCREMENT COMMENT 'รหัส',
    id_product varchar(100) COMMENT 'รหัสสินค้า'
    name varchar(100) COMMENT 'ชื่อสินค้า',
    price varchar(100) COMMENT 'ราคา',
    image varchar(100) COMMENT 'รูปภาพ',
    quantity varchar(100) COMMENT 'จำนวน' 
    type varchar(100) COMMENT 'ประเภท' 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'วันที่สร้าง',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'วันที่อัพเดต',
    PRIMARY KEY (id)
);";

if ($conn->query($table) === TRUE) {
    echo "สร้างฐานสินค้าสำเร็จเรียบร้อยแล้ว";
} else {
    echo "ไม่สามารถสร้างตารางได้: " . $conn->error;
}

$conn->close();
?>