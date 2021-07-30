<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 25/07/2019
 * Time: 01:48 PM
 */


//namespace GLOBALFUNCTIONS;

class GLOBALFUNCTIONS
{
    private $db;

    function __construct($con)
    {
        $this->db = $con;
    }

    function validateToken($token)
    {
        $sqlQuery = "SELECT token FROM `users` WHERE token ='$token'";
        $data = mysqli_query($this->db, $sqlQuery);
        $count = mysqli_num_rows($data);

        return $count;
    }

    function dataUser($id)
    {
        $sqlQuery = "SELECT
                        u.id,
                        u.name,
                        u.username,
                        u.email,
                        u.profile_pic,
                        u.created_at,
                        c.id AS company_id,
                        c.name AS company,
                        r.name AS role
                    FROM
                        users u
                    LEFT JOIN companies c ON u.company_id = c.id
                    LEFT JOIN roles r ON u.role = r.id
                    WHERE
                        u.id = $id";
        $data = mysqli_query($this->db, $sqlQuery);

        return $data;
    }

    function subCompanies($id)
    {

        $sqlQueryG = "SELECT * FROM companies WHERE `id` = $id";
        $dataG = mysqli_query($this->db, $sqlQueryG);
        $rowG = mysqli_fetch_assoc($dataG);

        $sqlQuery = "SELECT * FROM companies WHERE `group` = " . $rowG['group'] . " AND `id` <> $id";
        $data = mysqli_query($this->db, $sqlQuery);
        $row = mysqli_fetch_assoc($data);

        return $data;
    }

    function allSubCompanies($id)
    {

        $sqlQueryG = "SELECT * FROM companies WHERE `id` = $id";
        $dataG = mysqli_query($this->db, $sqlQueryG);
        $rowG = mysqli_fetch_assoc($dataG);

        $sqlQuery = "SELECT * FROM companies WHERE `group` = " . $rowG['group'] . " ";
        $data = mysqli_query($this->db, $sqlQuery);
        $row = mysqli_fetch_assoc($data);

        return $data;
    }

    function return_id_ticket($eval)
    {
        try {

            $stmt = mysqli_query($this->db, "SELECT id FROM tickets WHERE order_number='$eval' LIMIT 1");

            foreach ($stmt as $s) {
                $idTicket = $s['id'];
            }
            return $idTicket;

        } catch (PDOException $e) {
            return "Upsss ocurrio un error. Por favor ingresa más tarde.";
        }
    }

    function getInfoTicket($ticket_id)
    {
        $sqlQuery = "SELECT
                        t.id,
                        t.user_id,
                        u.`name`,
                        t.company_id AS company_id,
                        c.`name` AS company,
                        s.id AS status_id,
                        t.created_at
                    FROM
                        tickets t
                    LEFT JOIN users u ON t.user_id = u.id
                    LEFT JOIN companies c ON u.company_id = c.id
                    LEFT JOIN `status` s ON t.status_id = s.id
                    WHERE
                        t.id = $ticket_id";


        $data = mysqli_query($this->db, $sqlQuery);
        $row = mysqli_fetch_assoc($data);

        return $row;
    }

    function getInfoFade($ticket_id)
    {
        $sqlQuery = "SELECT tf.ticket_id, tf.fade_id, f.name, f.module_id 
                      FROM tickets_has_fades tf 
                      LEFT JOIN fades f ON tf.fade_id = f.id WHERE tf.ticket_id=" . $ticket_id;

        $data = mysqli_query($this->db, $sqlQuery);
        $row = mysqli_fetch_assoc($data);

        return $row;
    }

    function getInfoDetailTicket($ticket_id)
    {
        $sqlQuery = "SELECT
                        *
                    FROM
                        tickets_detail
                    WHERE
                        ticket_id = $ticket_id";


        $data = mysqli_query($this->db, $sqlQuery);
        $row = mysqli_fetch_assoc($data);

        return $row;
    }

    function recent_activity($user_id)
    {
        $arrayActivity = array();

        $sqlTickets = "SELECT * FROM tickets WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 3";
        $dataT = mysqli_query($this->db, $sqlTickets);
        $rowT = mysqli_fetch_assoc($dataT);


        $sqlNotes = "SELECT * FROM notes WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 3";
        $dataN = mysqli_query($this->db, $sqlNotes);
        $rowN = mysqli_fetch_assoc($dataN);


        $sqlComments = "SELECT * FROM comments c LEFT JOIN tickets t ON c.ticket_id = t.id WHERE c.user_id = $user_id ORDER BY c.created_at DESC LIMIT 3";
        $dataC = mysqli_query($this->db, $sqlComments);
        $rowC = mysqli_fetch_assoc($dataC);

        $sqlUpload = "SELECT * FROM upload_data WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 3";
        $dataU = mysqli_query($this->db, $sqlUpload);
        $rowU = mysqli_fetch_assoc($dataU);


        $arrayTickets[] = array("order_number" => $rowT['order_number'], "title" => $rowT['title'], "created_at" => $rowT['created_at']);
        if (isset($arrayTickets)) {
            $arrayActivity[] = array("arrayTickets" => $arrayTickets);
        }

        $arrayNotes[] = array("title" => $rowN['title'], "created_at" => $rowN['created_at']);
        if (isset($arrayNotes)) {
            $arrayActivity[] = array("arrayNotes" => $arrayNotes);
        }

        $arrayComments[] = array("order_number" => substr($rowC['order_number'], 0, 20), "created_at" => $rowC['created_at']);
        if (isset($arrayComments)) {
            $arrayActivity[] = array("arrayComments" => $arrayComments);
        }

        $arrayUpload[] = array("filename" => $rowU['filename'], "created_at" => $rowU['created_at']);
        if (isset($arrayUpload)) {
            $arrayActivity[] = array("arrayUploads" => $arrayUpload);
        }


        return $arrayActivity;
    }

    function getRoles()
    {

        $sqlQuery = "SELECT * FROM roles";
        $data = mysqli_query($this->db, $sqlQuery);

        return $data;
    }

    function getRol($user_id)
    {

        $sqlQuery = "SELECT r.id FROM users u LEFT JOIN roles r  ON u.role = r.id WHERE u.id= $user_id";
        $data = mysqli_query($this->db, $sqlQuery);
        $row = mysqli_fetch_assoc($data);

        return $row;
    }

    function getGroupCompany($user_id)
    {

        $sqlQuery = "SELECT c.`group` FROM users u LEFT JOIN companies c ON	u.company_id = c.id WHERE	u.id = " . $user_id;
        $data = mysqli_query($this->db, $sqlQuery);
        $row = mysqli_fetch_assoc($data);

        return $row;

    }

    function getDataCompany($company_id)
    {

        $sqlQuery = "SELECT * FROM companies WHERE id= $company_id";
        $data = mysqli_query($this->db, $sqlQuery);
        $row = mysqli_fetch_assoc($data);

        return $row;
    }

    function getDptos()
    {

        $sqlQuery = "SELECT * FROM departments";
        $data = mysqli_query($this->db, $sqlQuery);

        return $data;
    }

    function timeElapsedDates($fechaInicio, $fechaFin)
    {

        $fecha1 = new DateTime($fechaInicio);
        $fecha2 = new DateTime($fechaFin);
        $fecha = $fecha1->diff($fecha2);
        $tiempo = "";

        //años
        if ($fecha->y > 0) {
            $tiempo .= $fecha->y;

            if ($fecha->y == 1)
                $tiempo .= " año, ";
            else
                $tiempo .= " años, ";
        }

        //meses
        if ($fecha->m > 0) {
            $tiempo .= $fecha->m;

            if ($fecha->m == 1)
                $tiempo .= " mes, ";
            else
                $tiempo .= " meses, ";
        }

        //dias
        if ($fecha->d > 0) {
            $tiempo .= $fecha->d;

            if ($fecha->d == 1)
                $tiempo .= " día, ";
            else
                $tiempo .= " días, ";
        }

        //horas
        if ($fecha->h > 0) {
            $tiempo .= $fecha->h;

            if ($fecha->h == 1)
                $tiempo .= " hora, ";
            else
                $tiempo .= " horas, ";
        }

        //minutos
        if ($fecha->i > 0) {
            $tiempo .= $fecha->i;

            if ($fecha->i == 1)
                $tiempo .= " minuto";
            else
                $tiempo .= " minutos";
        } else if ($fecha->i == 0) //segundos
            $tiempo .= $fecha->s . " segundos";

        return $tiempo;
    }

    function esImagen($ext)
    {
        $is = false;
        $file = 'image';
        switch (strtolower($ext)) {
            case "jpg":
                $is = true;
                $file = 'image';
                break;
            case "jpeg":
                $is = true;
                $file = 'image';
                break;
            case "png":
                $is = true;
                $file = 'image';
                break;
            case "gif":
                $is = true;
                $file = 'image';
                break;
            case "pdf":
                $is = false;
                $file = 'pdf';
                break;
            case "xlsx":
                $is = false;
                $file = 'excel';
                break;
            case "xls":
                $is = false;
                $file = 'excel';
                break;
            case "xml":
                $is = false;
                $file = 'xml';
                break;
            case "csv":
                $is = false;
                $file = 'csv';
                break;
            case "docx":
                $is = false;
                $file = 'doc';
                break;
            case "log":
                $is = false;
                $file = 'log';
                break;
            default:
                $is = false;
                break;
        }
        return $file;
    }

    function log($msg, $fileName = "Debug.log")
    {
        global $username;

        if (!$fileName):
            $fileName = "Debug.log";
        endif;

        if (!$username):
            $username = "noaccess";
        endif;


//        $server = $_SERVER['SERVER_NAME'];
        $server = "localhost/intesystem";

        $Ruta = $server . "/log";
        if (file_exists("$Ruta$fileName")):
            //el tamanio del archivo esta en KB
            $tamanio = round(filesize("$Ruta$fileName") / 1024);
            //d($tamanio);
            if ($tamanio >= 5000):
                rename("$Ruta$fileName", $Ruta . str_replace(".log", "", $fileName) . date("d-m-Y_h-i-s") . ".log");
            endif;
            $Intentos = 0;
            while (TRUE):
                $Intentos++;
                $PAArchivo = @fopen("$Ruta$fileName", "a");
                if (!$PAArchivo):
                    continue;
                endif;
                if ($Intentos < 10):
                    continue;
                endif;
                break;
            endwhile;
        else:
            $Intentos = 0;
            while (TRUE):
                $Intentos++;
                $PAArchivo = @fopen("$Ruta$fileName", "w");
                if (!$PAArchivo):
                    continue;
                endif;
                if ($Intentos < 10):
                    continue;
                endif;
                break;
            endwhile;
            fclose($PAArchivo);
            exec("chmod 777 $Ruta$fileName");
            $Intentos = 0;
            while (TRUE):
                $Intentos++;
                $PAArchivo = @fopen("$Ruta$fileName", "a");
                if (!$PAArchivo):
                    continue;
                endif;
                if ($Intentos < 10):
                    continue;
                endif;
                break;
            endwhile;
        endif;

        $Linea = "\n" . $_SERVER['PHP_SELF'] . " " . date("d/m/Y H:i:s") . " " . $username . " --->" . $msg;
        fputs($PAArchivo, $Linea);
        fclose($PAArchivo);
    }

    function getTicketsReport($group)
    {


        $sqlQuery = "
                    SELECT
                        t.*
                    FROM
                        `tickets` t
                        LEFT JOIN users u ON t.user_id = u.id
                        LEFT JOIN companies c ON u.company_id = c.id
                    WHERE
                        c.`group` = " . $group . "
                     ORDER BY t.created_at DESC";
        $data = mysqli_query($this->db, $sqlQuery);
//        $row = mysqli_fetch_assoc($data);

//        var_dump($sqlQuery);
        return $data;


    }

}
