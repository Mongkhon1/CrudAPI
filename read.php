<?php

include './config.php';
$sql = "SELECT * FROM products ORDER BY created_at DESC";
$result = $conn->query($sql);

$images = [];
if ($result->num_rows>0) {
  while ($row = $result->fetch_assoc()) {
    $images[] = $row;
  }
}
echo json_encode($images);

?>