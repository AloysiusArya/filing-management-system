<?php
header('Content-Type: application/json');
require 'functions.php';

$response = array();
$sql = "SELECT * FROM surat";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
    echo json_encode($response);
} else {
    echo json_encode(array("message" => "Tidak ada data surat ditemukan"));
}
$conn->close();
