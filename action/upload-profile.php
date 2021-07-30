<?php
include "../config/config.php";

if (isset($_FILES["file"])) {
    $file = $_FILES["file"];
    $name = $file["name"];
    $type = $file["type"];
    $tmp_n = $file["tmp_name"];
    $size = $file["size"];
    $folder = "../images/profiles/";
    $user_id = $_POST['user_id'];

//    if ($type != 'image/jpg' && $type != 'image/jpeg' && $type != 'image/png' && $type != 'image/gif')
//    {
//      echo "Error, el archivo no es una imagen";
//    }
//    else if ($size > 1024*1024)
//    {
//      echo "Error, el tamaño máximo permitido es un 1MB";
//    }
//    else
//    {
    $src = $folder . $name;
    @move_uploaded_file($tmp_n, $src);

    $query = mysqli_query($con, "UPDATE users set profile_pic=\"$name\" WHERE id=" . $user_id);
    if ($query) {
        echo $name;
    }
//    }
}