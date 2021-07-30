<?php

/**
 * Created by PhpStorm.
 * User: intes_000
 * Date: 21/08/2019
 * Time: 03:51 PM
 */

class REPORTS
{

    private $db;

    function __construct($con)
    {
        $this->db = $con;
    }


    function export_report_tickets($group)
    {

        if ($group == 0) {

            $complement = '';
        } else {
            $complement = 'WHERE
                        c.`group` = ' . $group . '';
        }

        $sqlQuery = "
                    SELECT
                        t.*,
                        c.name as company_name,
                        u.name AS created_name,
                        s.`name` as status,
                         IF
	                    ( t.status_id IN ( '3', '4' ),  MIN(cm.`comment`) , '' ) AS result
                    FROM
                        `tickets` t
                        LEFT JOIN users u ON t.user_id = u.id
                        LEFT JOIN companies c ON u.company_id = c.id
                        LEFT JOIN `status` s ON t.status_id = s.id
                        LEFT JOIN comments cm ON cm.ticket_id = t.id 
                       
                    " . $complement . "
                     GROUP BY t.id
                     ORDER BY t.created_at DESC";
        $data = mysqli_query($this->db, $sqlQuery);

    return $data;




    }


}