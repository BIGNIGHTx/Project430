<?php
require('DBConnect.php');

if (isset($_POST['btn_update'])) {
    $numbertable = $_POST['table_number'];
    $capacity = $_POST['capacity'];
    $status = $_POST['status'];

    $id = $_POST['table_id'];

//left database right php to bind param
    $update_table = $db->prepare("UPDATE table_info 
                                SET table_number = :table_number,
                                capacity = :capacity,
                                status = :status
                                WHERE table_id = :table_id");

    $update_table->bindParam(':table_number', $numbertable);
    $update_table->bindParam(':capacity', $capacity);
    $update_table->bindParam(':status', $status);
    $update_table->bindParam(':table_id', $id);

    $update_table->execute();
    header("Location: tableU.php");
}
?>