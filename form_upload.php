<?php
/*
define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
if(!IS_AJAX) {die('Restricted access');}
*/
require_once '../cm-admin/clases/dbConfig.php';
include('../php/class.uploader.php');


function generate_name($longitud)
{
    $key = '';
    $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
    $max = strlen($pattern) - 1;
    for ($i = 0; $i < $longitud; $i++) $key .= $pattern{mt_rand(0, $max)};
    return $key;
}

//assign var
$new_name = generate_name(20);

// _Admin
$competition = $_POST['competition'];

$name = trim($_POST['name']);
$last_name = trim($_POST['last_name']);
$Mlast_name = trim($_POST['Mlast_name']);

$full_name = $name . " " . $last_name . " " . $Mlast_name;

$email = $_POST['email'];
$phone = $_POST['phone'];
$box = $_POST['box'];
$gender = $_POST['gender'];
$birthDate = $_POST['birthDate'];
$shirt_size = strtoupper($_POST['shirt_size']);
//$state = $_POST['state'];
$category = $_POST['category'];
$sub = $_POST['subcategory'];

$subcategory = 0;
if ($sub == 'Equipos') {

    $subcategory = 0;

} else if ($sub == 'Individuales') {

    $subcategory = 1;

} else {

    $subcategory = 2;
}

if ($category == 1 && $subcategory == 1) {

    $isOnline = 1;

} else {
    $isOnline = 0;
}


$isWod = $_POST['isWod'];
// TEAM
$isTeam = $_POST['isTeam'];
$nameTeam = trim($_POST['nameTeam']);

$name2 = trim($_POST['name2']);
$last_name2 = trim($_POST['last_name2']);
$Mlast_name2 = trim($_POST['Mlast_name2']);
$email2 = $_POST['email2'];
$birthDate2 = $_POST['birthDate2'];
$shirt_size2 = strtoupper($_POST['shirt_size2']);
$gender2 = $_POST['gender2'];

$name3 = trim($_POST['name3']);
$last_name3 = trim($_POST['last_name3']);
$Mlast_name3 = trim($_POST['Mlast_name3']);
$email3 = $_POST['email3'];
$birthDate3 = $_POST['birthDate3'];
$shirt_size3 = strtoupper($_POST['shirt_size3']);
$gender3 = $_POST['gender3'];

//$name4 = trim($_POST['name4']);
//$last_name4 = trim($_POST['last_name4']);
//$Mlast_name4 = trim($_POST['Mlast_name4']);
//$email4 = $_POST['email4'];
//$birthDate4 = $_POST['birthDate4'];
//$shirt_size4 = strtoupper($_POST['shirt_size4']);


$nombreArchivo = explode(".", $_FILES['filer_input']['name']);
$extension = end($nombreArchivo);


$file_payment = "images/payments/";
$file_payment .= $new_name;
$file_payment .= ".";
$file_payment .= $extension;

// ---------- IMAGE UPLOAD ----------
$uploader = new Uploader();
$data = $uploader->upload($_FILES['filer_input'], array(
    'limit' => null, //Maximum Limit of files. {null, Number}
    'maxSize' => null, //Maximum Size of files {null, Number(in MB's)}
    'extensions' => null, //Whitelist for file extension. {null, Array(ex: array('jpg', 'png'))}
    'required' => false, //Minimum one file is required for upload {Boolean}
    'uploadDir' => '../images/payments/', //Upload directory {String}
    'title' => $new_name, //New file name {null, String, Array} *please read documentation in README.md
    'removeFiles' => true, //Enable file exclusion {Boolean(extra for jQuery.filer), String($_POST field name containing json data with file names)}
    'replace' => true, //Replace the file if it already exists  {Boolean}
    'perms' => null, //Uploaded file permisions {null, Number}
    'onCheck' => null, //A callback function name to be called by checking a file for errors (must return an array) | ($file) | Callback
    'onError' => null, //A callback function name to be called if an error occured (must return an array) | ($errors, $file) | Callback
    'onSuccess' => null, //A callback function name to be called if all files were successfully uploaded | ($files, $metas) | Callback
    'onUpload' => null, //A callback function name to be called if all files were successfully uploaded (must return an array) | ($file) | Callback
    'onComplete' => null, //A callback function name to be called when upload is complete | ($file) | Callback
    'onRemove' => null //A callback function name to be called by removing files (must return an array) | ($removed_files) | Callback
));


if ($data['isComplete']) {

    if ($subcategory == 0) {


        $result = $user->save_team($nameTeam, $full_name, $competition);


        //Search ID TEAM
        $userRow = $user->getIdTeam($nameTeam, $competition);
        $id_team = $userRow['id'];


        //RECORD´S
//        echo $id_team .' | '. $name .' | '. $last_name.' | '. $Mlast_name.' | '. $email.' | '. $phone.' | '. $box.' | '. $gender.' | '. $birthDate.' | '. $shirt_size.' | '. $category.' | '. $subcategory.' | '. $file_payment.' | '. $competition;
        $result1 = $user->save_recordT($id_team, $name, $last_name, $Mlast_name, $email, $phone, $box, $gender, $birthDate, $shirt_size, $category, $subcategory, $file_payment, $competition);

        $result2 = $user->save_recordT($id_team, $name2, $last_name2, $Mlast_name2, $email2, "NULL", $box, $gender2, $birthDate2, $shirt_size2, $category, $subcategory, $file_payment, $competition);

        $result3 = $user->save_recordT($id_team, $name3, $last_name3, $Mlast_name3, $email3, "NULL", $box, $gender3, $birthDate3, $shirt_size3, $category, $subcategory, $file_payment, $competition);

//   $result4 = $user->save_recordT($name4,$last_name4,$Mlast_name4,$email4,"NULL",$box,"NULL",$birthDate4,$shirt_size4,$state,$category,$subcategory,$file_payment,$id_team,$competition);


    } else {
//       echo 'individual';
//       echo $isWod;

        $result = $user->save_record($name, $last_name, $Mlast_name, $email, $phone, $box, $gender, $birthDate, $shirt_size, $category, $subcategory, $file_payment, $competition);


        if ($isWod == 1) {
            //Search ID TEAM
            $userRecord = $user->getIdUser();
            $id_user = $userRecord['id'];

//            echo $id_user;

            $resultWod1 = $rank->saveWod($id_user, 'WOD1', trim($_POST['wod1']), trim($_POST['url_wod1']));
            $resultWod2 = $rank->saveWod($id_user, 'WOD2', trim($_POST['wod2']), trim($_POST['url_wod2']));
            $resultWod3 = $rank->saveWod($id_user, 'WOD3', trim($_POST['wod3']), trim($_POST['url_wod3']));

//        var_dump($resultWod1);

            echo $isOnline;
        }
    }


}

if ($data['hasErrors']) {
    $errors = $data['errors'];
    print_r($errors);
}
?>
