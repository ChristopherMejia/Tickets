<?php
include "../config/config.php";

$datos = array();
$json = array();
$array=json_decode($_POST['data']);
$idUser=json_decode($_POST['id']);
$activeFilter=json_decode($_POST['action']);


foreach($array as $value){
    $aux = array(
        'columna' => $value->campo,
        'valor' => $value->palabra,
    );
    array_push($datos, $aux);
}
//concatenamos el usuario loggeado
$condicion = "WHERE tickets.asigned_id = ". $idUser ." ";
//Contacetamos las condiciones de los filtros agregados
foreach($datos as $dato){
    switch($dato["columna"]){

        case "Prioridad":
            $condicion .= "AND priorities.name LIKE '%".$dato["valor"]."%' "; 
            break;
        case "Titulo":
            $condicion .= "AND tickets.title LIKE '%" .$dato["valor"]. "%' ";
            break;
        case "Empresa":
            $condicion .= "AND companies.name LIKE '%" .$dato["valor"]. "%' ";
            break;
        case "Creado":
            $condicion .= "AND users.name LIKE '" .$dato["valor"]. "' ";
            break;
        case "Estatus":
            $condicion .= "AND status.name LIKE '" .$dato["valor"]. "' ";
    }
}

$query = "SELECT tickets.order_number as order_number,
    tickets.title as title,
    tickets.priority_id as priority_id,
    tickets.company_id as company_id,
    tickets.status_id as status_id,
    tickets.user_id as user_id,
    tickets.asigned_id as asigned_id,
    tickets.created_at as created_at,
    tickets.updated_at as updated_at,
    priorities.name as priorityName,
    priorities.id as priorityId,
    companies.name as companyName,
    companies.id as companyId,
    users.id as usersId,
    users.name as userName,
    status.id as statusId,
    status.name as statusName
    FROM tickets
    LEFT JOIN priorities ON tickets.priority_id = priorities.id
    LEFT JOIN companies ON tickets.company_id = companies.id
    LEFT JOIN users ON tickets.user_id = users.id
    LEFT JOIN status ON tickets.status_id = status.id
    ";
$queryFull = $query . $condicion; // string with the query
// var_dump($queryFull);
$queryResponse = mysqli_query($con, $queryFull); //execute query
while($row = mysqli_fetch_array($queryResponse) ){
    // var_dump($row);
    $array = array(
        'orderNumber' => $row['order_number'],
        'titulo' => $row['title'],
        'prioridad' => $row['priority_id'],
        'compania' => $row['companyName'],
        'creadoPor' => $row['user_id'],
        'asignado' => $row['asigned_id'],
        'created_at' => $row['created_at'],
        'updated_at' => $row['updated_at'],
        'estatus' => $row['status_id'],
        'namePrioridad' => $row['priorityName'],
        'companyName' => $row['companyName'],
        'userName' => $row['nameUser'],
        'statusName' => $row['statusName'],

    );
    array_push($json, $array);
}
echo json_encode($json);
exit();
?>