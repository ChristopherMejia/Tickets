<?php
/**
 * Created by PhpStorm.
 * User: intes_000
 * Date: 09/08/2019
 * Time: 07:17 PM
 */

//namespace ALERTS;


class ALERTS
{

    private $db;

    function __construct($con)
    {
        $this->db = $con;
    }


    /* BELL AND PUSH*/
    function listNotificationUserPush($user)
    {
        $data = array();

        /* TICKET DATA */
        $sqlTicket = "SELECT
                                t.id,
                                t.title,
                                c.`name` AS company,
                                t.order_number,
                                uc.`name` AS user_created,
                                uc.profile_pic,
                                IFNULL(ua.`id`, 0) AS user_assigned,
                                n.push,
                                n.bell,
                                t.created_at
                            FROM
                                tickets t
                            LEFT JOIN notifications n ON n.ticket_id = t.id
                            LEFT JOIN users uc ON t.user_id = uc.id
                            LEFT JOIN users ua ON n.user_id = ua.id
                            LEFT JOIN companies c ON t.company_id = c.id
                            WHERE
                                n.user_id = $user
                                AND n.push = 0
                            GROUP BY
                                t.id
                            ORDER BY
                                t.id DESC";


        $dataTicket = mysqli_query($this->db, $sqlTicket);
        foreach ($dataTicket as $dt) {
            $ticket_id = $dt['id'];
            $title = $dt['title'];
            $company = $dt['company'];
            $order_number = $dt['order_number'];
            $user_assigned = $dt['user_assigned'];
            $profile_pic = $dt['profile_pic'];
            $user_created = $dt['user_created'];
            $created_at = $dt['created_at'];

            $data[] = array("ticket_id" => $ticket_id, "title" => $title, "company" => $company, "order_number" => $order_number, "user_assigned" => $user_assigned, "profile_pic" => $profile_pic, "user_created" => $user_created, "created_at" => $created_at);

        }


        /*  ********************************** END ROLE */

        return $data;
    }

    function listNotificationUserBell($user)
    {
        $data = array();

        /* TICKET DATA */
        $sqlTicket = "SELECT
                                t.id,
                                t.title,
                                p.name AS priority_name,
                                p.badge AS priority_badge,
                                c.`name` AS company,
                                t.order_number,
                                uc.`name` AS user_created,
                                uc.profile_pic,
                                IFNULL(ua.`id`, 0) AS user_assigned,
                                n.push,
                                n.bell,
                                s.id AS status_id,
                                s.name AS status,
                                s.badge,
                                t.created_at
                            FROM
                                tickets t
                            LEFT JOIN notifications n ON n.reference = t.id
                            LEFT JOIN users uc ON t.user_id = uc.id
                            LEFT JOIN users ua ON n.user_id = ua.id
                            LEFT JOIN companies c ON t.company_id = c.id
                            LEFT JOIN priorities p ON t.priority_id = p.id
                            LEFT JOIN `status` s ON t.status_id = s.id
                            WHERE
                                n.user_id = $user
                                AND n.bell = 0
                                AND n.type='ticket'
                            GROUP BY
                                t.id
                            ORDER BY
                                t.id DESC";


        $dataTicket = mysqli_query($this->db, $sqlTicket);
        foreach ($dataTicket as $dt) {
            $ticket_id = $dt['id'];
            $title = $dt['title'];
            $priority_name = $dt['priority_name'];
            $priority_badge = $dt['priority_badge'];
            $company = $dt['company'];
            $order_number = $dt['order_number'];
            $user_assigned = $dt['user_assigned'];
            $profile_pic = $dt['profile_pic'];
            $user_created = $dt['user_created'];
            $status = $dt['status'];
            $status_id = $dt['status_id'];
            $badge = $dt['badge'];
            $created_at = $dt['created_at'];

            $data[] = array("ticket_id" => $ticket_id, "title" => $title, "priority_name" => $priority_name, "priority_badge" => $priority_badge, "company" => $company, "order_number" => $order_number, "user_assigned" => $user_assigned, "profile_pic" => $profile_pic, "user_created" => $user_created, "status" => $status, "status_id" => $status_id, "badge" => $badge, "created_at" => $created_at);

        }


        /*  ********************************** END ROLE */

        return $data;
    }

    function updateNotificationBell($ticket_id, $user_id)
    {

        $updated_at = "NOW()";

        $query_ticket = "SELECT * FROM notifications WHERE ticket_id= $ticket_id AND user_id=$user_id";
        $sql_ticket = mysqli_query($this->db, $query_ticket);
        $row = mysqli_fetch_assoc($sql_ticket);

        if ($row['bell'] == 0) {
            $sql = "UPDATE notifications SET bell=1,updated_at=$updated_at WHERE reference=$ticket_id AND user_id=$user_id";
            $query = mysqli_query($this->db, $sql);
        }

    }

    function updateNotificationPush($ticket_id, $user_id)
    {

        $updated_at = "NOW()";

        $sqlUpdate = "UPDATE notifications SET push = 1, updated_at=$updated_at WHERE reference=$ticket_id AND user_id=$user_id";
        mysqli_query($this->db, $sqlUpdate);

//        var_dump($sqlUpdate);
    }


    /* BELL MESSAGES */
    function listNotificationUserMsgBell($user)
    {
        $data = array();

        $sqlTicket = "SELECT
                                t.id,
                                t.title,
                                c.`name` AS company,
                                t.order_number,
                                uc.`name` AS user_created,
                                uc.profile_pic,
                                IFNULL(ua.`id`, 0) AS user_assigned,
                                n.push,
                                n.bell,
                                t.created_at
                            FROM
                                tickets t
                            LEFT JOIN notifications n ON n.reference = t.id
                            LEFT JOIN users uc ON t.user_id = uc.id
                            LEFT JOIN users ua ON n.user_id = ua.id
                            LEFT JOIN companies c ON t.company_id = c.id
                            WHERE
                                n.user_id = $user
                                AND n.bell = 0
                                AND n.type='comment'
                            GROUP BY
                                t.id
                            ORDER BY
                                t.id DESC";

//        print_r($sqlTicket);

        $dataTicket = mysqli_query($this->db, $sqlTicket);
        foreach ($dataTicket as $dt) {
            $ticket_id = $dt['id'];
            $title = $dt['title'];
            $comment = $dt['comment'];
            $company = $dt['company'];
            $order_number = $dt['order_number'];
            $user_assigned = $dt['user_assigned'];
            $profile_pic = $dt['profile_pic'];
            $user_created = $dt['user_created'];
            $created_at = $dt['created_at'];

            $data[] = array("ticket_id" => $ticket_id, "comment" => $comment, "title" => $title, "company" => $company, "order_number" => $order_number, "user_assigned" => $user_assigned, "profile_pic" => $profile_pic, "user_created" => $user_created, "created_at" => $created_at);

        }


        /*  ********************************** END ROLE */
//var_dump($sqlTicket);
        return $data;
    }

    function upd_bell_noti_msg($ticket_id, $user_id)
    {

        $query_ticket = "SELECT * FROM comments WHERE ticket_id = $ticket_id AND user_id != $user_id  AND notification = 0";
        $sql_ticket = mysqli_query($this->db, $query_ticket);
        $row = mysqli_fetch_assoc($sql_ticket);

        if ($row['notification'] == 0) {
            $sql = "UPDATE comments SET notification=1 WHERE ticket_id=$ticket_id AND user_id != $user_id ";
            $query = mysqli_query($this->db, $sql);
        }

    }
    /* BELL MESSAGES */


}