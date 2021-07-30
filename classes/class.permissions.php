<?php
/**
 * Created by PhpStorm.
 * User: intes_000
 * Date: 21/08/2019
 * Time: 03:51 PM
 */

class PERMISSIONS
{

    private $db;

    function __construct($con)
    {
        $this->db = $con;
    }


    public function showMenu($user_id)
    {

        $query = "SELECT
                        m.id,
                        m.prefix,
                        m.`name`,
                        m.icon
                    FROM
                        `users` u
                    LEFT JOIN roles r ON r.id = u.role
                    LEFT JOIN role_has_permissions rh ON rh.role_id = r.id
                    LEFT JOIN permissions p ON p.id = rh.permission_id
                    LEFT JOIN menus m ON m.id = p.menu_id
                    WHERE
                        u.id = $user_id
                    AND m.`status` = 1
                    GROUP BY
                        m.prefix
                    ORDER BY
                        m.`order` ASC";
        /* Created_To */
        $menu = mysqli_query($this->db, $query);


        return $menu;

    }

    public function showSubMenu($user_id)
    {

        $query = "SELECT
                        m.menu_id,
                        m.prefix,
                        m.`name`,
                        m.icon
                    FROM
                        `users` u
                    LEFT JOIN roles r ON r.id = u.role
                    LEFT JOIN role_has_permissions rh ON rh.role_id = r.id
                    LEFT JOIN permissions p ON p.id = rh.permission_id
                    LEFT JOIN menus_sub m ON m.id = p.menu_id
                    WHERE
                        u.id = $user_id
                    AND m.`status` = 1
                    GROUP BY
                        m.menu_id
                    ORDER BY
                        m.`order` ASC";
        /* Created_To */
        $submenu = mysqli_query($this->db, $query);


        return $submenu;

    }

    public function listSubMenu($user_id)
    {

        $query = "SELECT
                        m.menu_id,
                        m.prefix,
                        m.`name`,
                        m.icon
                    FROM
                        `users` u
                    LEFT JOIN roles r ON r.id = u.role
                    LEFT JOIN role_has_permissions rh ON rh.role_id = r.id
                    LEFT JOIN permissions p ON p.id = rh.permission_id
                    LEFT JOIN menus_sub m ON m.id = p.menu_id
                    WHERE
                        u.id = $user_id
                    AND m.`status` = 1
                    GROUP BY
                        m.prefix
                    ORDER BY
                        m.`order` ASC";
        /* Created_To */
        $submenu = mysqli_query($this->db, $query);


        return $submenu;

    }

    public function showStatus($role)
    {

        if ($role == 3) { //Developer = 3
            $query = "SELECT
                        *
                    FROM
                        `status`
                    
                    WHERE
                        `id` NOT IN ('1','3','4')
                    ORDER BY
                        `order`";
        } elseif ($role == 2) {

            $query = "SELECT
                        *
                    FROM
                        `status`
                    
                    WHERE
                        `id` NOT IN ('4')
                    ORDER BY
                        `order`";

        } else {
            $query = "SELECT
                        *
                    FROM
                        `status`
                    ORDER BY
                        `order`";
        }

        $status = mysqli_query($this->db, $query);


        return $status;

    }

    public function permissions_per_page_view($user_id, $page)
    {

        $query = "SELECT
                        DISTINCT p.prefix AS action
                    FROM
                        `users` u
                    LEFT JOIN roles r ON r.id = u.role
                    LEFT JOIN role_has_permissions rh ON rh.role_id = r.id
                    LEFT JOIN permissions p ON p.id = rh.permission_id
                    LEFT JOIN menus m ON m.id = p.menu_id
                    WHERE
                        u.id = $user_id
                    AND m.prefix ='$page'
                    AND p.prefix = 'view';
                    ";

        $result = mysqli_query($this->db, $query);
        $view = mysqli_fetch_assoc($result);
        return $view;

    }

    public function permissions_per_page_edit($user_id, $page)
    {

        $query = "SELECT
                        DISTINCT p.prefix AS action
                    FROM
                        `users` u
                    LEFT JOIN roles r ON r.id = u.role
                    LEFT JOIN role_has_permissions rh ON rh.role_id = r.id
                    LEFT JOIN permissions p ON p.id = rh.permission_id
                    LEFT JOIN menus m ON m.id = p.menu_id
                    WHERE
                        u.id = $user_id
                    AND m.prefix ='$page'
                    AND p.prefix = 'edit';
                    ";

        $result = mysqli_query($this->db, $query);
        $edit = mysqli_fetch_assoc($result);
        return $edit;


    }

    public function permissions_per_page_delete($user_id, $page)
    {

        $query = "SELECT
                        DISTINCT p.prefix AS action
                    FROM
                        `users` u
                    LEFT JOIN roles r ON r.id = u.role
                    LEFT JOIN role_has_permissions rh ON rh.role_id = r.id
                    LEFT JOIN permissions p ON p.id = rh.permission_id
                    LEFT JOIN menus m ON m.id = p.menu_id
                    WHERE
                        u.id = $user_id
                    AND m.prefix ='$page'
                    AND p.prefix = 'delete';
                    ";

//        var_dump($query);
        $result = mysqli_query($this->db, $query);
        $delete = mysqli_fetch_assoc($result);
        return $delete;

    }

    public function get_rol($user_id)
    {

        $query = "SELECT DISTINCT
                    r.id
                FROM
                    `users` u
                LEFT JOIN roles r ON r.id = u.role
                LEFT JOIN role_has_permissions rh ON rh.role_id = r.id
                LEFT JOIN permissions p ON p.id = rh.permission_id
                LEFT JOIN menus m ON m.id = p.menu_id
                WHERE
                    u.id = $user_id
                    ";

        $result = mysqli_query($this->db, $query);
        $delete = mysqli_fetch_assoc($result);
        return $delete;

    }

    public function token_permissions($user_createdTicket_id, $token, $status_ticket_id)
    {
        $has_permissions = false;
        $role = $this->get_rol($_SESSION['user_id']);

        $queryUserSession = "SELECT
                        *
                    FROM
                        `users`
                    WHERE
                        id = " . $_SESSION['user_id'];
        $resultUserSession = mysqli_query($this->db, $queryUserSession);
        $result_UserSession_Assco = mysqli_fetch_assoc($resultUserSession);


        $queryUserTicketCreated = "SELECT
                        *
                    FROM
                        `users`
                    WHERE
                        id = " . $user_createdTicket_id;

        $resultUserTicketCreated = mysqli_query($this->db, $queryUserTicketCreated);
        $resultUserTicketCreated_Assoc = mysqli_fetch_assoc($resultUserTicketCreated);


        if ($resultUserTicketCreated_Assoc['token'] == $token) {
            $has_permissions = true;
        } elseif ($role['id'] == 1) { //Admin
            $has_permissions = true;
        } elseif ($role['id'] == 2) { //Support
            $has_permissions = true;
//            AND ($status_ticket_id == 2 OR $status_ticket_id == 1)
        } elseif ($role['id'] == 3 AND ($result_UserSession_Assco['group'] == $resultUserTicketCreated_Assoc['group'])) { //Developer
            $has_permissions = true;
        } elseif ($role['id'] == 5 AND ($result_UserSession_Assco['group'] == $resultUserTicketCreated_Assoc['group'])) {
            //If you are a super user (customer) and belong to the same group of companies,
            // you can enter the ticket even if it was not created by him.
            $has_permissions = true;
        } else {
            $has_permissions = false;
        }


        return $has_permissions;

    }
}