<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 21/01/2020
 * Time: 11:57 AM
 */


include('../../config/config.php');


    $user = $_POST['user'];
    $page = $_POST['page'];


    function random_color_part()
    {
        return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
    }

    function random_color()
    {
        return random_color_part() . random_color_part() . random_color_part();
    }

//echo random_color();


    $sqlPresence = mysqli_query($con, $queryPresence = "REPLACE INTO `users_presence` (user_id, page) VALUES($user_id,'$page');");

    $sqlPresenceSEL = mysqli_query($con, $queryPresenceSEL = "SELECT
                                                                u.profile_pic,
                                                                u.`name`
                                                            FROM
                                                                `users_presence` up
                                                                LEFT JOIN users u ON up.user_id = u.id
                                                            WHERE
                                                                up.page = '$page' 
                                                                AND up.user_id != $user;");

    $row_cnt_sel = $sqlPresenceSEL->num_rows;

    $output .= '
                        <div class="docs-presence-plus-widget goog-inline-block">
                            <div class="docs-presence-plus-widget-inner goog-inline-block">
                                <div class="docs-presence-plus-widget-status">';
    if ($row_cnt_sel > 0) {
        foreach ($sqlPresenceSEL as $sel) {

            $name = $sel['name'];
            $pic = $sel['profile_pic'];
            $color = '#'.random_color() ;

            $output .= '<div role="button" data-toggle="tooltip" data-placement="bottom" title="' . $name . '">
                            <div class="docs-presence-plus-collab-widget-image-container" style="background-color: ' . $color . '">
                               <div class="docs-presence-plus-collab-widget-image-border">
                                 <img class="docs-presence-plus-collab-widget-image ttip" src="images/profiles/' . $pic . '">
                               </div>
                            </div>
                      </div>';
        }
    }
    $output .= '
                                </div>
                            </div>
                        </div>
                    ';


//    var_dump($output);
    $data = array(
        'usersp' => $output
    );

    echo json_encode($data);


?>