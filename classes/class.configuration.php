<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 19/11/2019
 * Time: 11:54 AM
 */

class CONFIGURATION
{
    private $db;

    function __construct($con)
    {
        $this->db = $con;
    }

    function maintenance($role_id)
    {
        $ma = false;

        $sql = "SELECT maintenance FROM configuration";
        $data = mysqli_query($this->db, $sql);
        $row = mysqli_fetch_assoc($data);

        if ($row['maintenance'] == 1 AND $role_id <> 1) {
            $ma = true;
        } else {
            $ma = false;
        }

        return $ma;
    }

    function getConfiguration()
    {
        $sql = "SELECT * FROM configuration";
        $data = mysqli_query($this->db, $sql);
        $row = mysqli_fetch_assoc($data);

        return $row;
    }

}