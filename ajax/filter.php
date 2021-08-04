<?php
include "../config/config.php";
if($_POST['data'] != null){

    $datos = array();
    $json = array();
    $array=json_decode($_POST['data']);
    $idUser=json_decode($_POST['id']);
    

    foreach($array as $value){
        $aux = array(
            'columna' => $value->campo,
            'valor' => $value->palabra,
        );
        array_push($datos, $aux);
    }

    switch($_POST['flag']){
        case "ticket":
            $condicion = "WHERE tickets.asigned_id = ". $idUser ." ";
            break;
        case "pending":
            $condicion = "WHERE tickets.asigned_id = ". 0 ." " ;
            break;
        case "testing":
            $condicion = "WHERE tickets.status_id = ". 5 ." " ;
            break;
        case "all";
            $condicion = "WHERE tickets.user_id ";
            break;
    }
    
    

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
            $condicion .= "AND users.name LIKE '%" .$dato["valor"]. "%' ";
            break;
        case "Estatus":
            $condicion .= "AND status.name LIKE '%" .$dato["valor"]. "%' ";
            break;
        case "Modulo":
            $tableMod = true;
            $condicion = "WHERE tickets.asigned_id = users.id AND modules.name LIKE '%" .$dato["valor"]. "%'";
            break;
        case "Asignado":
            $table = true;
            $condicion = "WHERE tickets.asigned_id = users.id AND users.name LIKE '%" .$dato["valor"]. "%'";
            break;
        }
    }
    if(isset($tableMod) || isset($table)){
        $condicion .= " ORDER BY tickets.order_number ASC";
    }

    $query = "SELECT tickets.id as ticketId,
    tickets.order_number as order_number,
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
    status.name as statusName,
    status.badge as statusBadge,
    tickets_detail.module_id as module_id 
    FROM tickets
    LEFT JOIN priorities ON tickets.priority_id = priorities.id
    LEFT JOIN companies ON tickets.company_id = companies.id
    LEFT JOIN users ON tickets.user_id = users.id
    LEFT JOIN status ON tickets.status_id = status.id
    LEFT JOIN tickets_detail ON tickets_detail.ticket_id = tickets.id
    ";

    if(isset($table)){
        $query ="SELECT tickets.id as ticketId,
        tickets.order_number as order_number,
        tickets.title as title,
        tickets.priority_id as priority_id,
        tickets.company_id as company_id,
        tickets.status_id as status_id,
        tickets.user_id as user_id,
        tickets.asigned_id as asigned_id,
        tickets.created_at as created_at,
        tickets.updated_at as updated_at,
        priorities.name as priorityName,
        companies.id as companyId,
        status.id as statusId,
        status.name as statusName,
        status.badge as statusBadge,
        users.id as usersId,
        users.name as userName
        FROM users
        LEFT JOIN tickets ON tickets.asigned_id = users.id
        LEFT JOIN priorities ON tickets.priority_id = priorities.id
        LEFT JOIN companies ON tickets.company_id = companies.id
        LEFT JOIN status ON tickets.status_id = status.id
        ";
    }

    if(isset($tableMod)){
        $query = " SELECT
        tickets.id as ticketId,
        tickets.order_number as order_number,
        tickets.title as title,
        tickets.priority_id as priority_id,
        tickets.company_id as company_id,
        tickets.status_id as status_id,
        tickets.user_id as user_id,
        tickets.asigned_id as asigned_id,
        tickets.created_at as created_at,
        tickets.updated_at as updated_at,
        priorities.name as priorityName,
        companies.id as companyId,
        status.id as statusId,
        status.name as statusName,
        status.badge as statusBadge,
        users.id as usersId,
        users.name as userName
        FROM modules
        LEFT JOIN tickets_detail ON tickets_detail.module_id = modules.id
        LEFT JOIN tickets ON tickets.id = tickets_detail.ticket_id
        LEFT JOIN users ON tickets.asigned_id = users.id
        LEFT JOIN priorities ON tickets.priority_id = priorities.id
        LEFT JOIN companies ON tickets.company_id = companies.id
        LEFT JOIN status ON tickets.status_id = status.id
        ";
    }

    $queryFull = $query . $condicion; // string with the query
    
    // var_dump($queryFull);
    $queryResponse = mysqli_query($con, $queryFull); //execute query
    while($row = mysqli_fetch_array($queryResponse) ){
    // var_dump($row);
    $array = array(
        'ticket_id' => $row['ticketId'],
        'orderNumber' => $row['order_number'],
        'titulo' => $row['title'],
        'prioridad' => $row['priority_id'],
        'compania' => $row['companyId'],
        'creadoPor' => $row['user_id'],
        'asignado' => $row['asigned_id'],
        'created_at' => $row['created_at'],
        'updated_at' => $row['updated_at'],
        'estatus' => $row['status_id'],
        'namePrioridad' => $row['priorityName'],
        'companyName' => $row['companyName'],
        'userName' => $row['nameUser'],
        'statusName' => $row['statusName'],
        'statusBadge' => $row['statusBadge'],
        'module_id' => $row['module_id'],
    );
    array_push($json, $array);
    }
}
// var_dump($json);
?>
<div class="panel-body no-padding" id="tablaFilter">
    <div class="table-responsive">
        <table class="table table-striped jambo_table bulk_action">
            <thead>
            <tr class="headings">
                <th>#</th>
                <th class="column-title">Titulo</th>
                <th class="column-title action-hidden">Prioridad</th>
                <th class="column-title">Empresa</th>
                <th class="column-title">Creado por</th>
                <th class="column-title action-hidden">Asignado a</th>
                <th class="column-title action-hidden">Estatus</th>
                <th class="column-title action-hidden">Modulo</th>
                <th>Creado</th>
                <th>Última Actualización</th>
                <th class="column-title no-link last"><span class="nobr"></span></th>
            </tr>
            </thead>
            <tbody>
                <?php foreach($json as $column){ ?>
                    <tr class="even pointer"> 
                        <td> <?php echo $column['orderNumber'] ?> </td>
                    
                        <td> <?php echo $column['titulo'] ?> </td>
                   
                        <td class="action-hidden"> <?php echo $column['namePrioridad'] ?> </td>
                   
                        <td> 
                            <div class="mini-logo"
                                style="background: url('images/logos/<?php echo$column['compania'] ?>.png') no-repeat center center;  background-size: cover; ">
                            </div>
                        </td>
                   
                        <td> 
                            <?php 
                            $sqlCre = mysqli_query($con, "SELECT * FROM users WHERE id='" .$column['creadoPor']. "' ");
                            $rowCre = mysqli_fetch_array($sqlCre);

                            echo $rowCre['name'];
                            ?> 
                        </td>
                   
                        <td class="action-hidden"> 
                            <?php
                            $sqlAsi = mysqli_query($con, "SELECT * FROM users WHERE id='" .$column['asignado']. "' ");
                            $rowAsi = mysqli_fetch_array($sqlAsi); 
                            if (isset($rowAsi['name'])) {
                                echo $rowAsi['name'];
                            } else {
                                echo "Pendiente";
                            };
                            ?> 
                        </td>
                    
                        <td class="action-hidden"> 
                            <span class="lb-custom label label-<?php echo $column['statusBadge']; ?>"><?php echo $column['statusName']; ?></span>
                        </td>
                        
                        <td> 
                        <?php 
                            $sqlModule = mysqli_query($con, "SELECT * FROM modules WHERE id='" .$column['module_id']. "' ");
                            $rowModule = mysqli_fetch_array($sqlModule);
                            if (isset($rowModule['name'])): ?>
                                <span class="label label-default"> <?php echo strtoupper($rowModule['name']); ?></span>
                            <?php else: ?>
                                <span class="label label-warning"> Sin asignar</span>
                            <?php endif; 
                        ?>
                        </td>
                    
                        <td> <?php echo $column['created_at'] ?> </td>
                   
                        <td> <?php echo $column['updated_at'] ?> </td>

                        <td>
                            <span class="pull-right">
                                <a href="ticket-detail?order=<?php echo $column['orderNumber']; ?>" class='btn btn-default'
                                title='Ver detalle'>
                                    <i class="glyphicon glyphicon-eye-open"></i>
                                </a>

                                <a href="#" class='btn btn-default action-delete' title='Eliminar ticket'
                                onclick="eliminar('<?php echo $column['ticket_id'] ?>')">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </a>
                            </span>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
