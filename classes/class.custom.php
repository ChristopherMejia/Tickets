<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 19/11/2019
 * Time: 11:54 AM
 */

class CUSTOM
{
    private $db;

    function __construct($con)
    {
        $this->db = $con;
    }


    function getUserCustom($user_id)
    {
        $sql = "SELECT * FROM custom WHERE user_id = $user_id";
        $data = mysqli_query($this->db, $sql);
        $row = mysqli_fetch_assoc($data);

        return $row;
    }

}