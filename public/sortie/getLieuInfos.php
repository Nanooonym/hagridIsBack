<?php

$conn = mysqli_connect("localhost", "root", "", "hagrid_is_back", 3308, '/tmp/mysql5.sock');
$idLieu = $_COOKIE["IdLieu"];
$result = mysqli_query($conn, "SELECT * FROM `lieu` WHERE `id` = $idLieu");

$data = array();
while ($row = mysqli_fetch_assoc($result))
{
    $data[] = $row;
}

echo json_encode($data);