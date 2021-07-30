<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 01/08/2019
 * Time: 04:01 PM
 */

session_start();
include "../config/config.php";//DB


$id = $_POST['ticket_id'];
$user_id = $_POST['user_id'];


if (!empty($_FILES['files'])) {
    // File upload configuration
    $targetDir = "../public/attach/$id/";
    $allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'pdf', 'xlsx', 'xls', 'xml', 'csv', 'docx', 'doc', 'xml','log', 'JPG', 'PNG', 'JPEG', 'GIF', 'PDF', 'XLSX', 'XLS', 'CSV', 'DOCX', 'DOC', 'XML','LOG');

    $files_arr = array();
    foreach ($_FILES['files']['name'] as $key => $val) {
        $image_name = $_FILES['files']['name'][$key];
        $tmp_name = $_FILES['files']['tmp_name'][$key];
        $size = $_FILES['files']['size'][$key];
        $type = $_FILES['files']['type'][$key];
        $error = $_FILES['files']['error'][$key];

        // File upload path
        $fileName = basename($_FILES['files']['name'][$key]);
        $targetFilePath = $targetDir . $fileName;

        // Check whether file type is valid
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        $fileOnlyName = pathinfo($targetFilePath, PATHINFO_FILENAME);
        if (in_array($fileType, $allowTypes)) {

            if (file_exists($targetFilePath)) {

                $increment = 0;
                while (file_exists($targetFilePath)) {
                    $increment++;
                    $targetFilePath = $targetDir . $fileOnlyName . $increment . '.' . $fileType;
                    $fileName = $fileOnlyName . $increment . '.' . $fileType;
                }
            }

//            var_dump($fileName);
            // Store files on the server
            if (move_uploaded_file($_FILES['files']['tmp_name'][$key], $targetFilePath)) {
                $files_arr[] = $targetFilePath;

                $sqlData = "INSERT INTO upload_data (user_id, ticket_id, filename, created_at) VALUES ('$user_id','$id','$fileName',NOW())";


//                var_dump($sqlData);

                $query_new_insert = mysqli_query($con, $sqlData);

                if ($query_new_insert) {
                    $messages[] = "Tu archivo ha sido ingresado satisfactoriamente.";

                } else {
                    $errors [] = "Lo siento algo ha salido mal intenta nuevamente." . mysqli_error($con);
                }
            } else {
                $errors [] = "Hubo un problema al cargar el archivo (" . $fileType . ") seleccionado.";
                $log_error->addLine($_SESSION['user_id'], getcwd(), 'TICKET', 'SUBIR ARCHIVO(s)', array("Ticket ID:" . $id), 'Hubo un problema al cargar el archivo (' . $fileType . ') seleccionado.', 1);

            }
        }


    }
} else {
    $errors [] = "Error desconocido.";
}


$myObj->filename = $fileName;


if (isset($errors)) {

    $myObj->success = 0;
    $myJSON = json_encode($myObj);
    echo $myJSON;
}
if (isset($messages)) {

    $myObj->success = 1;
    $myJSON = json_encode($myObj);
    echo $myJSON;
}

?>