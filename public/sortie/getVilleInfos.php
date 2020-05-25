<?php

$conn = mysqli_connect("localhost", "root", "", "hagrid_is_back", 3308, '/tmp/mysql5.sock');
$idVille = $_COOKIE["IdVille"];
$result = mysqli_query($conn, "SELECT * FROM `ville` WHERE `id` = $idVille");

$data = array();
while ($row = mysqli_fetch_assoc($result))
{
    $data[] = $row;
}

echo json_encode($data);