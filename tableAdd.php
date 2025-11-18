<?php
require('DBConnect.php');

if (isset($_POST['btn_add'])) {
    $numbertable = $_POST['table_number'];
    $capacity = $_POST['capacity'];
    $status = $_POST['status'];


    $insert = $db->prepare("INSERT INTO table_info (table_number, capacity, status) 
                            VALUES (:table_number, :capacity, :status)");

    $insert->bindParam(':table_number', $numbertable);
    $insert->bindParam(':capacity', $capacity);
    $insert->bindParam(':status', $status);

    $insert->execute();
    header("Location: tableU.php");
}
?>