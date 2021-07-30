<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 05/07/2019
 * Time: 06:53 PM
 */

//namespace NOTIFICATIONS;

use PHPMailer\PHPMailer\PHPMailer;

require __DIR__ . '/../lib/PHPMailer/src/Exception.php';
require __DIR__ . '/../lib/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../lib/PHPMailer/src/SMTP.php';


class NOTIFICATION
{
    private $db;

    function __construct($con)
    {
        $this->db = $con;
    }

    /* CUSTOMER */
    public function openTicket($ticket_id)
    {

        $dataTicket = mysqli_query($this->db, "SELECT * FROM tickets WHERE id='$ticket_id' LIMIT 1");
        foreach ($dataTicket as $dt) {
            $order_number = $dt['order_number'];
            $asigned_id = $dt['asigned_id'];
            $user_id = $dt['user_id'];
            $title = $dt['title'];
        }

        /* Created_To */
        $user = mysqli_query($this->db, "SELECT * FROM users WHERE id=" . $user_id);
        foreach ($user as $u) {
            $user_name = $u['name'];
            $user_email = $u['email'];
            $company_id = $u['company_id'];
        }

        /* Company */
        $company = mysqli_query($this->db, "SELECT * FROM companies WHERE id=" . $company_id);
        foreach ($company as $c) {
            $company = $u['name'];
        }

        /*email cc*/

        if ($asigned_id == 0) {
            $sqlCC = mysqli_query($this->db, "SELECT DISTINCT u.email FROM `users` u LEFT JOIN roles r ON u.role = r.id WHERE r.id <= 2");
            $ccArray = array();
            foreach ($sqlCC as $m) {
                $ccArray[] = array("Email" => $m['email']);
            }
        } else {
            $sqlCC = mysqli_query($this->db, "SELECT DISTINCT u.email FROM `tickets` t LEFT JOIN users u ON t.asigned_id = u.id WHERE t.asigned_id = " . $asigned_id);
            $ccArray = array();
            foreach ($sqlCC as $m) {
                $ccArray[] = array("Email" => $m['email']);
            }
        }

        $content = '
                    Tu ticket de atención ha sido creado con éxito y ha recibido el <b>Folio #' . $order_number . '</b> 
                    teniendo como titulo <b>"' . $title . '"</b>
                    Estamos localizando al ejecutivo más adecuado para atender esta solicitud.
                   ';
        $ccArray = array_unique($ccArray);
        $this->sendMail_TicketCustomer($user_name, $user_email, $ccArray, $order_number, $title, $company, $content, '');
    }

    public function closeTicket($annotations, $ticket_id)
    {

        $dataTicket = mysqli_query($this->db, $queryTicket = "SELECT * FROM tickets WHERE id='$ticket_id' LIMIT 1");
        foreach ($dataTicket as $dt) {
            $order_number = $dt['order_number'];
            $asigned_id = $dt['asigned_id'];
            $user_id = $dt['user_id'];
            $title = $dt['title'];
        }

        /* Created_To */
        $user = mysqli_query($this->db, $queryUser = "SELECT * FROM users WHERE id=" . $user_id);

        foreach ($user as $u) {
            $user_name = $u['name'];
            $user_email = $u['email'];
            $company_id = $u['company_id'];
        }

        /* Company */
        $company = mysqli_query($this->db, "SELECT * FROM companies WHERE id=" . $company_id);
        foreach ($company as $c) {
            $company = $u['name'];
        }

        /*email cc*/

        if ($asigned_id == 0) {
            $sqlCC = mysqli_query($this->db, "SELECT DISTINCT u.email FROM `users` u LEFT JOIN roles r ON u.role = r.id WHERE r.id <= 2");
            $ccArray = array();
            foreach ($sqlCC as $m) {
                $ccArray[] = array("Email" => $m['email']);
            }
        } else {
            $sqlCC = mysqli_query($this->db, "SELECT DISTINCT u.email FROM `tickets` t LEFT JOIN users u ON t.asigned_id = u.id WHERE t.asigned_id = " . $asigned_id);
            $ccArray = array();
            foreach ($sqlCC as $m) {

                $ccArray[] = array("Email" => $m['email']);
            }
        }

        $content = '
        Tu ticket <b>#' . $order_number . '</b> <b><i>' . $title . '</i></b> ha sido Cerrado.<br><br>
        Esperamos que nuestra atención haya sido de tu entera satisfacción, y por favor en caso de que necesites reactivar este ticket simplemente responde a este correo.<br><br>
        Con la intención de mejora continua en nuestros servicios nos gustaría saber su opinión a través de esta encuesta:<br>
        https://forms.gle/uc4zd6YxCDp7YmjbA
        ';

        $ccArray = array_unique($ccArray);
        $this->sendMail_TicketCustomer($user_name, $user_email, $ccArray, $order_number, $title, $company, $content, $annotations);
    }

    public function sendMail_TicketCustomer($fullname, $email, $email_cc, $order, $title, $company, $content, $annotations)
    {


        if ($annotations != "") {
            $html_annotations = '<div style="padding-right: 60px; padding-left: 60px;border-radius: 6px; margin-bottom: 2rem; background-color: #e9ecef;">
                                   <h3>Comentarios:</h3>
                                   <small>' . $annotations . '</small>
                                 </div>';
        } else {
            $html_annotations = '';
        }

        $mail = new PHPMailer(true);
        $mail->CharSet = "UTF-8";
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = 'login';
        $mail->Username = 'no-reply@intesystem.net';
        $mail->Password = 'UN!cyHcInv3o';
        $mail->SMTPSecure = 'ssl';
        $mail->Host = 'mail.intesystem.net';
        $mail->Port = 465;
        $mail->SetFrom('no-reply@intesystem.net', 'INTESYSTEM');
        $mail->Subject = '[#' . $order . '] ' . $title;
        $mail->Body = '
        <!DOCTYPE html>
<html>
<head>
    <title>Sistema de Tickets | INTESYSTEM</title>
    <link href=http://soporte.intesystem.net/favicon.ico rel="shortcut icon">
    <!-- Custom Theme files -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="keywords" content=""/>
    <!-- //Custom Theme files -->
    <!-- Responsive Styles and Valid Styles -->
    <style type="text/css">

        body {
            width: 100%;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        html {
            width: 100%;
        }

        table {
            font-size: 14px;
            border: 0;
        }

        /* ----------- responsivity ----------- */
        @media only screen and (max-width: 800px) {
            table.container.top-header-left {
                width: 726px;
            }
        }

        @media only screen and (max-width: 736px) {
            table.container.top-header-left {
                width: 684px;
            }
        }

        @media only screen and (max-width: 667px) {
            table.container.top-header-left {
                width: 600px;
            }

            table.container-middle {
                width: 565px;
            }

            a.logo-text img {
                width: 100%;
                height: inherit;
            }

            table.logo {
                width: 40%;
            }

            table.mail_left {
                width: 200px;
            }

            td.mail_gd, td.mail_gd a {
                text-align: center !important;
            }

            td.ban_pad {
                height: 48px;
            }

            td.future {
                font-size: 2em !important;
            }

            td.ban_tex {
                height: 18px;
            }

            table.ban-hei {
                height: 315px !important;
            }

            td.ser_pad {
                height: 50px;
            }

            td.wel_text {
                font-size: 2.1em !important;
            }

            td.ser_text {
                line-height: 2em !important;
                font-size: 1em !important;
            }

            table.twelve_one {
                width: 316px;
            }

            table.twelve_two {
                width: 229px;
            }

            td.pic_one img {
                width: 100%;
                height: inherit;
            }

            table.ser_left_two {
                width: 100px;
            }

            table.ser_left_one {
                width: 200px;
            }

            img.full {
                width: 100%;
            }

            table.twelve_three {
                width: 272px;
            }

            td.ser_text2 {
                font-size: 1.5em !important;
            }

            table.cir_left {
                width: 276px;
            }

            table.twelve_four {
                width: 200px;
            }

            table.twelve_five {
                width: 337px;
            }

            td.ser_one {
                height: 45px;
            }

            table.foo {
                width: 370px;
            }
        }

        @media only screen and (max-width: 640px) {
            td.ser_one {
                height: 60px;
            }

            .top-bottom-bg {
                width: 420px !important;
                height: auto !important;
            }

            table.container-middle.navi-grid {
                width: 360px !important;
            }

            table.logo {
                width: 45%;
            }
        }

        @media only screen and (max-width: 600px) {
            table.container.top-header-left {
                width: 535px;
            }

            table.container-middle {
                width: 485px;
            }



            table.ser_left_one {
                width: 151px;
            }

            table.ser_left_two {
                width: 86px;
            }

            table.twelve_one {
                width: 239px;
            }

            table.twelve_two {
                width: 221px;
            }

            table.twelve_three {
                width: 100%;
            }

            img.full {
                width: inherit;
            }

            table.cir_left {
                width: 230px;
            }

            table.cir_left img {
                width: 54%;
                height: inherit;
            }

            img.full.team_img {
                width: 100% !important;
                height: inherit;
            }

            table.twelve_four {
                width: 160px;
            }

            table.twelve_five {
                width: 298px;
            }

            td.team_pad {
                height: 0;
            }

            table.foo {
                width: 100%;
            }

            td.ser_text.editable {
                text-align: center;
            }

            table.foo1 {
                width: 100%;
            }
        }

        @media only screen and (max-width: 568px) {
            /*-- w3layouts --*/
            table.container.top-header-left {
                width: 495px !important;
            }

            table.ban_info {
                width: 400px;
            }

            td.future {
                font-size: 1.8em !important;
            }



            table.container-middle {
                width: 449px;
            }

            table.twelve_two {
                width: 190px;
            }

            td.ser_one {
                height: 34px;
            }

            td.ser_two {
                height: 21px;
            }

            table.cir_left {
                width: 100%;
            }

            table.cir_left img {
                width: 30%;
                height: inherit;
            }

            table.twelve_four {
                width: 100%;
            }

            img.full.team_img {
                width: 45% !important;
                height: inherit;
            }

            table.twelve_five {
                width: 100%;
            }

            td.text_team {
                text-align: center;
            }

            td.twel_pad {
                height: 25px;
            }
        }

        /*-- agileits --*/
        @media only screen and (max-width: 480px) {
            .container {
                width: 290px !important;
            }

            .container-middle {
                width: 85% !important;
            }

            .mainContent {
                width: 240px !important;
            }

            .top-bottom-bg {
                width: 260px !important;
                height: auto !important;
            }

            table.logo {
                width: 33% !important;
            }

            table.container.top-header-left {
                width: 422px !important;
            }

            table.container-middle.navi-grid {
                width: 399px !important;
            }

            table.container-middle.nav-head {
                width: 350px !important;
            }

            table.twelve_one {
                width: 100%;
            }

            table.ser_left_one {
                width: 271px;
            }

            table.twelve_two {
                width: 100%;
            }

            td.pic_one {
                text-align: center !important;
            }

            td.pic_one img {
                width: 70%;
                height: inherit;
            }

            td.ser_pad {
                height: 32px;
            }

            td.future {
                font-size: 1.5em !important;
            }

            table.ban_info {
                width: 348px;
            }

            table.logo {
                width: 43% !important;
            }



            td.ban_pad {
                height: 24px;
            }

            table.logo {
                width: 54% !important;
            }

            td.ser_text {
                font-size: 13px !important;
            }
        }

        @media only screen and (max-width: 414px) {
            table.container.top-header-left {
                width: 397px !important;
            }

            table.container-middle.navi-grid {
                width: 372px !important;
            }

            table.container.top-header-left {
                width: 370px !important;
            }

            .container-middle {
                width: 95% !important;
            }

            table.ser_left_one {
                width: 255px;
            }
        }

        @media only screen and (max-width: 384px) {

            table.container-middle.navi-grid, table.container.top-header-left {
                width: 300px !important;
            }

            table.ban_info {
                width: 297px;
            }

            td.future {
                font-size: 1.3em !important;
            }

            .container-middle {
                width: 90% !important;
            }

            table.ban_info {
                width: 310px;
            }

            /*-- agileits --*/
            table.container-middle.nav-head {
                width: 340px !important;
            }

            table.ser_left_one {
                width: 216px;
            }

            table.mail_left, table.mail_right {
                width: 100%;
                height: 38px;
            }


            td.ser_one {
                height: 11px;
            }
        }

        @media only screen and (max-width: 320px) {
            td.wel_text {
                font-size: 1.9em !important;
            }

            img.full {
                width: 100%;
            }

            table.container.top-header-left {
                width: 284px !important;
            }

            table.container-middle.nav-head {
                width: 257px !important;
            }

            table.ban_info {
                width: 257px;
            }

            td.future {
                font-size: 1.2em !important;
            }

            td.ban_tex {
                height: 10px;
            }



            table.logo {
                width: 56% !important;
            }

            td.top_mar {
                height: 6px;
            }

            table.mail_left, table.mail_right {
                width: 100%;
                height: 29px;
            }

            table.ser_left_one {
                width: 181px;
            }

            table.ser_left_two {
                width: 73px;
            }

            td.pic_one img {
                width: 100%;
            }

            table.cir_left img {
                width: 37%;
            }

            td.thompson {
                font-size: 1.5em !important;
            }

            table.follow {
                width: 100%;
            }

            table.follow td {
                text-align: center !important;
            }

            table.logo {
                width: 69% !important;
            }
        }
    </style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table border="0" width="100%" cellpadding="0" cellspacing="0">

    <tr>
        <td width="100%" align="center" valign="top" bgcolor="062f3c" style="">
            <table>
                <tr>
                    <td class="top_mar" height="50"></td>
                </tr>
            </table>
            <!-- main content -->
            <table style="box-shadow:0px 0px 0px 0px #E0E0E0;" width="800" border="0" cellpadding="0" cellspacing="0"
                   align="center" class="container top-header-left">
                <tr bgcolor="2771ca">
                    <td>
                        <table border="0" width="650" align="center" cellpadding="0" cellspacing="0"
                               class="container-middle nav-head">
                            <tr>
                                <td height="15"></td>
                            </tr>
                            <tr>
                                <td>
                                    <table border="0" align="center" cellpadding="0" cellspacing="0"
                                           style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
                                           class="logo">
                                        <tbody>

                                        <tr>
                                            <td align="right">
                                                <a href="#" class="logo-text" style="text-decoration:none; color:#fff;">
                                                    <h1>' . $company . '</h1>
                                                </a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td height="15"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr bgcolor="#3f5367">
                    <td>
                        <table border="0" width="650" align="center" cellpadding="0" cellspacing="0"
                               class="container-middle nav-head">
                            <tr>
                                <td height="15"></td>
                            </tr>
                            <tr>
                                <td>
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0"
                                           style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <table class="mail_left" align="left" border="0" cellpadding="0"
                                                       cellspacing="0" width="450">
                                                    <tbody>
                                                    <tr>
                                                        <td class="mail_gd" align="center"
                                                            style=" text-align: left; font-size:1.2em; font-family:Candara; color: #FFFFFF;">
                                                            <a href="mailto:contacto@intesystem.com.mx"
                                                               style="color:#fff;text-decoration:none">
                                                                <img src="http://soporte.intesystem.net/email/images/envelope.png"
                                                                     alt="" border="0"
                                                                     height="18" width="18">&nbsp; &nbsp;
                                                                contacto@intesystem.com.mx
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <table class="mail_right" align="right" border="0" cellpadding="0"
                                                       cellspacing="0" width="200">
                                                    <tbody>
                                                    <tr>
                                                        <td align="center"
                                                            style="font-size:14px;color:#f5f5f5;font-family:Arial,serif">
                                                            <img src="http://soporte.intesystem.net/email/images/1.png"
                                                                 alt="" border="0" height="16"
                                                                 width="16">&nbsp;
                                                            &nbsp;33 3629 3619 / 33 1816 3347 / 33 2005 2286

                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td height="15"></td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr bgcolor="#ffffff">
                    <td>
                        <table border="0" width="650" align="center" cellpadding="0" cellspacing="0"
                               class="container-middle">
                            <tbody>
                            <!-- padding-top -->
                            <tr>
                                <td class="ser_pad" height="70"></td>
                            </tr>
                            <!-- //padding-top -->

                            <tr>
                                <td height="15"></td>
                            </tr>
                            <tr>
                                <td class="ser_text" align="center"
                                    style=" color:#464646; font-size: 1.2em; font-family: Candara; line-height:1.8em; text-align: left">
                                    <h3> Hola Estimado ' . $fullname . ',</h3>
                                    <br>
                                    ' . $html_annotations . '

                                    <br>
                                    <p>
                                        ' . $content . '
                                    </p>
                                    <br>
                                    <p>
                                        Saludos,
                                        Equipo de Soporte   Intesys
                                    </p>

                                </td>
                            </tr>
                            <tr>
                                <td height="20"></td>
                            </tr>
                            <!-- padding-top -->
                            <tr>
                                <td class="ser_pad" height="70"></td>
                            </tr>
                            <!-- //padding-top -->
                            </tbody>
                        </table>
                    </td>
                </tr>


                <tr>
                    <td>
                        <table align="center" cellpadding="0" cellspacing="0" style="border-bottom:1px solid #f7f7f7">
                        </table>
                    </td>
                </tr>

                <tr bgcolor="2771ca">
                    <td>
                        <table border="0" width="650" align="center" cellpadding="0" cellspacing="0"
                               class="container-middle">
                            <tr>
                                <td height="10" style="font-size: 1px; line-height: 10px;">&nbsp;</td>
                            </tr>

                            <tr>
                                <td>

                                    <table class="foo" width="375" border="0" align="left" cellpadding="0"
                                           cellspacing="0">
                                        <tr>
                                            <td class="ser_text editable"
                                                style="font-family: Candara; font-size: 1em; color: #ffffff; line-height: 24px;">
                                                © 2019 INTESYSTEM . All Rights Reserved.</a>
                                            </td>
                                            <td style="float: right;">
                                                <a href="intesystem.com.mx" target="_blank"
                                                   style="font-family: Candara; font-size: 1em; color: #ffffff; float: right; line-height: 24px;">
                                                    intesystem.com.mx
                                                </a>
                                            </td>
                                        </tr>
                                    </table>

                                    <!-- SPACE -->
                                    <table width="1" height="10" border="0" cellpadding="0" cellspacing="0"
                                           align="left">
                                        <tr>
                                            <td height="10"
                                                style="font-size: 0;line-height: 0;border-collapse: collapse;padding-left: 24px;">
                                                &nbsp;
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- END SPACE -->

                                    <table class="foo1" width="170" border="0" align="right" cellpadding="0"
                                           cellspacing="0">
                                        <tr>
                                            <td class="ser_text editable"
                                                style="font-family: Candara; font-size: 1em; color: #ffffff; line-height: 24px;">


                                            </td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>

                            <tr>
                                <td height="10" style="font-size: 1px; line-height: 10px;">&nbsp;</td>
                            </tr>

                        </table>
                    </td>
                </tr>

            </table>
            <table>
                <tr>
                    <td class="top_mar" height="50"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
         ';
        $mail->IsHTML(true);
//        var_dump($email);
        $mail->AddAddress($email);//Customer email
        foreach ($email_cc as $cc) {
            $mail->AddCC($cc['Email']);
        }


        if (!$mail->send()) {
            echo 'Message was not sent.';
            echo 'Mailer error: ' . $mail->ErrorInfo;
            error_log("\n" . date('Y-m-d h:i:s A') . " ===> El mensaje no ha sido enviado. (" . $email . ")", 3, "../log/Email_TicketCustomer.log");
        } else {
            error_log("\n" . date('Y-m-d h:i:s A') . " ===> El mensaje ha sido enviado. (" . $email . ")", 3, "../log/Email_TicketCustomer.log");
            error_log("\n" . date('Y-m-d h:i:s A') . " ===> Copias del mensaje. (" . print_r($email_cc, true) . ")", 3, "../log/Email_TicketCustomer.log");
        }


    }
    /* END CUSTOMER */

    /* Ticket Edited / Notes / Comments */
    public function updTicket($ticket_id, $cause, $content, $notifiCustomer, $comment)
    {

        $dataTicket = mysqli_query($this->db, "SELECT * FROM tickets WHERE id='$ticket_id' LIMIT 1");
        foreach ($dataTicket as $dt) {
            $order_number = $dt['order_number'];
            $asigned_id = $dt['asigned_id'];
            $user_id = $dt['user_id'];
            $title = $dt['title'];
            $created_at = $dt['created_at'];
            $priority_id = $dt['priority_id'];
            $project_id = $dt['project_id'];
            $status_id = $dt['status_id'];
        }
        /* Status */
        $statuss = mysqli_query($this->db, "SELECT * FROM  status WHERE id=" . $status_id);
        foreach ($statuss as $s) {
            $status = $s['name'];
        }


        /* Priorities */
        $priorities = mysqli_query($this->db, "SELECT * FROM  priorities WHERE id=" . $priority_id);
        foreach ($priorities as $p) {
            $priority = $p['name'];
        }

        /* Projects */
        $projects = mysqli_query($this->db, "SELECT * FROM  projects WHERE id=" . $project_id);
        foreach ($projects as $p) {
            $project = $p['name'];
            $project_desc = $p['description'];
        }

        /* Created_To */
        $user = mysqli_query($this->db, "SELECT * FROM users WHERE id=" . $user_id);
        foreach ($user as $u) {
            $user_name = $u['name'];
            $user_email = $u['email'];
            $company_id = $u['company_id'];
            $user_role = $u['role'];
        }

        /* Company */
        $company = mysqli_query($this->db, "SELECT * FROM companies WHERE id=" . $company_id);
        foreach ($company as $c) {
            $company = $u['name'];
        }

        /*email cc*/
        $ccArray = array();
        if ($asigned_id == 0) {
            $sqlCC = mysqli_query($this->db, "SELECT u.email, u.name FROM `users` u LEFT JOIN roles r ON u.role = r.id WHERE r.id = 2 AND u.is_active ='1'");

            foreach ($sqlCC as $m) {
                $ccArray[] = array("Email" => $m['email'], "Assigned_to" => $m['name']);
            }
        } else {
            $sqlCC = mysqli_query($this->db, "SELECT u.email, u.name FROM `tickets` t LEFT JOIN users u ON t.asigned_id = u.id WHERE t.asigned_id = " . $asigned_id . "  AND u.is_active ='1'");

            foreach ($sqlCC as $m) {
                $ccArray[] = array("Email" => $m['email'], "Assigned_to" => $m['name']);
            }
        }

        //customer restriction, notifications
        if ($notifiCustomer == 0) {
            $user_email = "root@intesystem.net"; //Cambiar correo por que es interno
        }

        error_log("\n" . date('Y-m-d h:i:s A') . " ===> ¿Enviar al cliente? (" . $notifiCustomer . ")", 3, "../log/Upd_Ticket.log");
        error_log("\n" . date('Y-m-d h:i:s A') . " ===> Ticket: (" . $order_number . ")", 3, "../log/Upd_Ticket.log");
        error_log("\n" . date('Y-m-d h:i:s A') . " ===> Enviar a: (" . $user_email . ")", 3, "../log/Upd_Ticket.log");
//        error_log("\n" . date('Y-m-d h:i:s A') . " ===> CC: (" . print_r($ccArray) . ")", 3, "../log/Upd_Ticket.log");

        $ccArray = array_unique($ccArray);
        $this->internalChanges($cause, $user_name, $user_email, $ccArray, $order_number, $title, $company, $content, $project, $project_desc, $priority, $status, $created_at, $comment);

    }

    public function internalChanges($cause, $user_name, $user_email, $email_cc, $order_number, $title, $company, $content, $project, $project_desc, $priority, $status, $created_at, $comment)
    {
        $assigned_to = '';
        foreach ($email_cc as $cc) {
            $assigned_to .= $cc['Assigned_to'] . ',';
        }
        $assigned_to = substr($assigned_to, 0, -1);


        if ($user_email <> 'test@intesystem.net') {
            $getInfoTicket = '';
            $project_ticket = '';
        } else {
            $getInfoTicket = '
                           <tr>
                                <td class="wel_text lcolor" align="center">
                                    <h2 style="font-size:2.1em;color:#2771ca;font-family:Candara;text-align:left;font-weight:600; margin-top: 5px;">
                                        Detalles
                                    </h2>

                                </td>

                            </tr>
                            <tr>
                                <td class="lcolor">
                                    <b>Fecha de Creación:</b> ' . $created_at . '<br>
                                    <b>Creado por:</b> ' . $user_name . '<br>
                                    <b>Asignado a: ' . $assigned_to . '</b><br>
                                    <b>Prioridad:</b> ' . $priority . '<br>
                                    <b>Estatus:</b> ' . $status . '<br>
                                </td>

                            </tr>
            ';
            $project_ticket = '
                            <tr>
                                <td class="wel_text lcolor" align="center">
                                    <h2 style="font-size:2.1em;color:#2771ca;font-family:Candara;text-align:left;font-weight:600; margin-top: 5px">
                                        Proyecto
                                    </h2>
                                </td>
                            </tr>
                            <tr>
                                <td class="lcolor">
                                    <b>Proyecto:</b> ' . $project . '<br><br>
                                    <small>' . $project_desc . '</small>
                                </td>
                            </tr>
            ';
        }

        $mail = new PHPMailer(true);
        $mail->CharSet = "UTF-8";
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = 'login';
        $mail->Username = 'no-reply@intesystem.net';
        $mail->Password = 'UN!cyHcInv3o';
        $mail->SMTPSecure = 'ssl';
        $mail->Host = 'mail.intesystem.net';
        $mail->Port = 465;
        $mail->SetFrom('no-reply@intesystem.net', 'INTESYSTEM');
        $mail->Subject = $cause . ' [#' . $order_number . '] [' . $title . ']';
        $mail->Body = '<!DOCTYPE html>
<html>
<head>
    <title>Sistema de Tickets | INTESYSTEM</title>
    <link href=http://soporte.intesystem.net/favicon.ico rel="shortcut icon">
    <!-- Custom Theme files -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="keywords" content=""/>
    <!-- //Custom Theme files -->
    <!-- Responsive Styles and Valid Styles -->
    <style type="text/css">

        body {
            width: 100%;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        html {
            width: 100%;
        }

        table {
            font-size: 14px;
            border: 0;
        }

        .lcolor {
            font-size: 1.1em;
            padding: 5px 20px;
            border-left-width: 10px;
            border-left-style: solid;
            border-left-color: orange;
        }

        /* ----------- responsivity ----------- */
        @media only screen and (max-width: 800px) {
            table.container.top-header-left {
                width: 726px;
            }
        }

        @media only screen and (max-width: 736px) {
            table.container.top-header-left {
                width: 684px;
            }
        }

        @media only screen and (max-width: 667px) {
            table.container.top-header-left {
                width: 600px;
            }

            table.container-middle {
                width: 565px;
            }

            a.logo-text img {
                width: 100%;
                height: inherit;
            }

            table.logo {
                width: 40%;
            }

            table.mail_left {
                width: 200px;
            }

            td.mail_gd, td.mail_gd a {
                text-align: center !important;
            }

            td.ban_pad {
                height: 48px;
            }

            td.future {
                font-size: 2em !important;
            }

            td.ban_tex {
                height: 18px;
            }


            td.ser_pad {
                height: 50px;
            }

            td.wel_text {
                font-size: 2.1em !important;
            }

            td.ser_text {
                line-height: 2em !important;
                font-size: 1em !important;
            }

            table.twelve_one {
                width: 316px;
            }

            table.twelve_two {
                width: 229px;
            }

            td.pic_one img {
                width: 100%;
                height: inherit;
            }

            table.ser_left_two {
                width: 100px;
            }

            table.ser_left_one {
                width: 200px;
            }

            img.full {
                width: 100%;
            }

            table.twelve_three {
                width: 272px;
            }

            td.ser_text2 {
                font-size: 1.5em !important;
            }

            table.cir_left {
                width: 276px;
            }

            table.twelve_four {
                width: 200px;
            }

            table.twelve_five {
                width: 337px;
            }

            td.ser_one {
                height: 45px;
            }

            table.foo {
                width: 370px;
            }
        }

        @media only screen and (max-width: 640px) {
            td.ser_one {
                height: 60px;
            }

            .top-bottom-bg {
                width: 420px !important;
                height: auto !important;
            }

            table.container-middle.navi-grid {
                width: 360px !important;
            }

            table.logo {
                width: 45%;
            }
        }

        @media only screen and (max-width: 600px) {
            table.container.top-header-left {
                width: 535px;
            }

            table.container-middle {
                width: 485px;
            }


            table.ser_left_one {
                width: 151px;
            }

            table.ser_left_two {
                width: 86px;
            }

            table.twelve_one {
                width: 239px;
            }

            table.twelve_two {
                width: 221px;
            }

            table.twelve_three {
                width: 100%;
            }

            img.full {
                width: inherit;
            }

            table.cir_left {
                width: 230px;
            }

            table.cir_left img {
                width: 54%;
                height: inherit;
            }

            img.full.team_img {
                width: 100% !important;
                height: inherit;
            }

            table.twelve_four {
                width: 160px;
            }

            table.twelve_five {
                width: 298px;
            }

            td.team_pad {
                height: 0;
            }

            table.foo {
                width: 100%;
            }

            td.ser_text.editable {
                text-align: center;
            }

            table.foo1 {
                width: 100%;
            }
        }

        @media only screen and (max-width: 568px) {
            /*-- w3layouts --*/
            table.container.top-header-left {
                width: 495px !important;
            }

            table.ban_info {
                width: 400px;
            }

            td.future {
                font-size: 1.8em !important;
            }


            table.container-middle {
                width: 449px;
            }

            table.twelve_two {
                width: 190px;
            }

            td.ser_one {
                height: 34px;
            }

            td.ser_two {
                height: 21px;
            }

            table.cir_left {
                width: 100%;
            }

            table.cir_left img {
                width: 30%;
                height: inherit;
            }

            table.twelve_four {
                width: 100%;
            }

            img.full.team_img {
                width: 45% !important;
                height: inherit;
            }

            table.twelve_five {
                width: 100%;
            }

            td.text_team {
                text-align: center;
            }

            td.twel_pad {
                height: 25px;
            }
        }

        /*-- agileits --*/
        @media only screen and (max-width: 480px) {
            .container {
                width: 290px !important;
            }

            .container-middle {
                width: 85% !important;
            }

            .mainContent {
                width: 240px !important;
            }

            .top-bottom-bg {
                width: 260px !important;
                height: auto !important;
            }

            table.logo {
                width: 33% !important;
            }

            table.container.top-header-left {
                width: 422px !important;
            }

            table.container-middle.navi-grid {
                width: 399px !important;
            }

            table.container-middle.nav-head {
                width: 350px !important;
            }

            table.twelve_one {
                width: 100%;
            }

            table.ser_left_one {
                width: 271px;
            }

            table.twelve_two {
                width: 100%;
            }

            td.pic_one {
                text-align: center !important;
            }

            td.pic_one img {
                width: 70%;
                height: inherit;
            }

            td.ser_pad {
                height: 32px;
            }

            td.future {
                font-size: 1.5em !important;
            }

            table.ban_info {
                width: 348px;
            }

            table.logo {
                width: 43% !important;
            }


            td.ban_pad {
                height: 24px;
            }

            table.logo {
                width: 54% !important;
            }

            td.ser_text {
                font-size: 13px !important;
            }
        }

        @media only screen and (max-width: 414px) {
            table.container.top-header-left {
                width: 397px !important;
            }

            table.container-middle.navi-grid {
                width: 372px !important;
            }

            table.container.top-header-left {
                width: 370px !important;
            }

            .container-middle {
                width: 95% !important;
            }

            table.ser_left_one {
                width: 255px;
            }
        }

        @media only screen and (max-width: 384px) {

            table.container-middle.navi-grid, table.container.top-header-left {
                width: 300px !important;
            }

            table.ban_info {
                width: 297px;
            }

            td.future {
                font-size: 1.3em !important;
            }

            .container-middle {
                width: 90% !important;
            }

            table.ban_info {
                width: 310px;
            }

            /*-- agileits --*/
            table.container-middle.nav-head {
                width: 340px !important;
            }

            table.ser_left_one {
                width: 216px;
            }

            table.mail_left, table.mail_right {
                width: 100%;
                height: 38px;
            }


            td.ser_one {
                height: 11px;
            }
        }

        @media only screen and (max-width: 320px) {
            td.wel_text {
                font-size: 1.9em !important;
            }

            img.full {
                width: 100%;
            }

            table.container.top-header-left {
                width: 284px !important;
            }

            table.container-middle.nav-head {
                width: 257px !important;
            }

            table.ban_info {
                width: 257px;
            }

            td.future {
                font-size: 1.2em !important;
            }

            td.ban_tex {
                height: 10px;
            }


            table.logo {
                width: 56% !important;
            }

            td.top_mar {
                height: 6px;
            }

            table.mail_left, table.mail_right {
                width: 100%;
                height: 29px;
            }

            table.ser_left_one {
                width: 181px;
            }

            table.ser_left_two {
                width: 73px;
            }

            td.pic_one img {
                width: 100%;
            }

            table.cir_left img {
                width: 37%;
            }

            td.thompson {
                font-size: 1.5em !important;
            }

            table.follow {
                width: 100%;
            }

            table.follow td {
                text-align: center !important;
            }

            table.logo {
                width: 69% !important;
            }
        }
    </style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table border="0" width="100%" cellpadding="0" cellspacing="0">

    <tr>
        <td width="100%" align="center" valign="top" bgcolor="062f3c" style="">
            <table>
                <tr>
                    <td class="top_mar" height="50"></td>
                </tr>
            </table>
            <!-- main content -->
            <table style="box-shadow:0px 0px 0px 0px #E0E0E0;" width="800" border="0" cellpadding="0" cellspacing="0"
                   align="center" class="container top-header-left">
                <tr bgcolor="2771ca">
                    <td>
                        <table border="0" width="650" align="center" cellpadding="0" cellspacing="0"
                               class="container-middle nav-head">
                            <tr>
                                <td height="15"></td>
                            </tr>
                            <tr>
                                <td>
                                    <table border="0" align="center" cellpadding="0" cellspacing="0"
                                           style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
                                           class="logo">
                                        <tbody>

                                        <tr>
                                            <td align="right">
                                                <a href="#" class="logo-text" style="text-decoration:none; color:#fff;">
                                                    <h1 style="color:#fff!important;">TICKET N° #' . $order_number .
            '</h1>
                                                </a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td height="15"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr bgcolor="#3f5367">
                    <td>
                        <table border="0" width="650" align="center" cellpadding="0" cellspacing="0"
                               class="container-middle nav-head">
                            <tr>
                                <td height="15"></td>
                            </tr>
                            <tr>
                                <td>
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0"
                                           style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <table class="mail_left" align="left" border="0" cellpadding="0"
                                                       cellspacing="0" width="450">
                                                    <tbody>
                                                    <tr>
                                                        <td class="mail_gd" align="center"
                                                            style=" text-align: left; font-size:1.2em; font-family:Candara; color: #FFFFFF;">
                                                            <a href="mailto:contacto@intesystem.com.mx"
                                                               style="color:#fff;text-decoration:none">
                                                                <img src="http://soporte.intesystem.net/email/images/envelope.png"
                                                                     alt="" border="0"
                                                                     height="18" width="18">&nbsp; &nbsp;
                                                                contacto@intesystem.com.mx
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <table class="mail_right" align="right" border="0" cellpadding="0"
                                                       cellspacing="0" width="200">
                                                    <tbody>
                                                    <tr>
                                                        <td align="center"
                                                            style="font-size:14px;color:#f5f5f5;font-family:Arial,serif">
                                                            <img src="http://soporte.intesystem.net/email/images/1.png"
                                                                 alt="" border="0" height="16"
                                                                 width="16">&nbsp;
                                                            &nbsp;33 3629 3619 / 33 1816 3347 / 33 2005 2286
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td height="15"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr bgcolor="#ffffff">
                    <td>
                        <table border="0" width="650" align="center" cellpadding="0" cellspacing="0"
                               class="container-middle">
                            <tbody>
                            <!-- padding-top -->
                            <tr>
                                <td class="ser_pad" height="70"></td>

                            </tr>
                            <!-- //padding-top -->
                            <tr>
                                <td class="wel_text lcolor" align="center">
                                    <h2 style="font-size:2.1em;color:#2771ca;font-family:Candara;text-align:left;font-weight:600; margin-top: 5px">
                                         Evento(s)
                                    </h2>
                                </td>
                            </tr>
                            <tr>
                                <td class="wel_text lcolor">
                                    ' . $cause . '
                                    <br><br><br>
                                    "<small><i>' . $comment . '</i></small>"
                                </td>
                            </tr>
                            <tr>
                                <td height="30"></td>
                            </tr>
                            ' . $getInfoTicket . '


                            ' . $project_ticket . '
                            <tr>
                                <td height="15"></td>
                            </tr>
                            <!-- padding-top -->
                            <tr>
                                <td class="ser_pad" height="70"></td>
                            </tr>
                            <!-- //padding-top -->
                            </tbody>
                        </table>
                    </td>
                </tr>


                <tr>
                    <td>
                        <table align="center" cellpadding="0" cellspacing="0" style="border-bottom:1px solid #f7f7f7">
                        </table>
                    </td>
                </tr>

                <tr bgcolor="2771ca">
                    <td>
                        <table border="0" width="650" align="center" cellpadding="0" cellspacing="0"
                               class="container-middle">
                            <tr>
                                <td height="10" style="font-size: 1px; line-height: 10px;">&nbsp;</td>
                            </tr>

                            <tr>
                                <td>

                                    <table class="foo" width="375" border="0" align="left" cellpadding="0"
                                           cellspacing="0">
                                        <tr>
                                            <td class="ser_text editable"
                                                style="font-family: Candara; font-size: 1em; color: #ffffff; line-height: 24px;">
                                                © 2019 INTESYSTEM . All Rights Reserved.</a>
                                            </td>
                                            <td style="float: right;">
                                                <a href="intesystem.com.mx" target="_blank"
                                                   style="font-family: Candara; font-size: 1em; color: #ffffff; float: right; line-height: 24px;">
                                                    intesystem.com.mx
                                                </a>
                                            </td>
                                        </tr>
                                    </table>

                                    <!-- SPACE -->
                                    <table width="1" height="10" border="0" cellpadding="0" cellspacing="0"
                                           align="left">
                                        <tr>
                                            <td height="10"
                                                style="font-size: 0;line-height: 0;border-collapse: collapse;padding-left: 24px;">
                                                &nbsp;
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- END SPACE -->

                                    <table class="foo1" width="170" border="0" align="right" cellpadding="0"
                                           cellspacing="0">
                                        <tr>
                                            <td class="ser_text editable"
                                                style="font-family: Candara; font-size: 1em; color: #ffffff; line-height: 24px;">


                                            </td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>

                            <tr>
                                <td height="10" style="font-size: 1px; line-height: 10px;">&nbsp;</td>
                            </tr>

                        </table>
                    </td>
                </tr>

            </table>
            <table>
                <tr>
                    <td class="top_mar" height="50"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>     
         ';
        $mail->IsHTML(true);
        $mail->AddAddress($user_email);//Customer email or not
        foreach ($email_cc as $cc) {
            $mail->AddCC($cc['Email']);
        }


        if (!$mail->send()) {
            echo 'Message was not sent.';
            echo 'Mailer error: ' . $mail->ErrorInfo;
            error_log("\n" . date('Y-m-d h:i:s A') . " ===> El mensaje no ha sido enviado. (" . $user_email . ")", 3, "../log/Email_UpdTicket.log");
        } else {
            error_log("\n" . date('Y-m-d h:i:s A') . " ===> El mensaje ha sido enviado. (" . $user_email . ")", 3, "../log/Email_UpdTicket.log");
            error_log("\n" . date('Y-m-d h:i:s A') . " ===> CC del mensaje. (" . print_r($email_cc, true) . ")", 3, "../log/Email_UpdTicket.log");
        }


    }

    /* ------------------------- SMS --------------------------------- */
    public function sendSMS_newTicket($name, $email, $phone)
    {
        try {
            //Send SMS
            $mail = new PHPMailer;
            $mail->IsSMTP();
            $mail->Host = "smtp.upsidewireless.com";
            $mail->SMTPAuth = true;
            $mail->Port = 25;
            $mail->Username = "farvizu";
            $mail->Password = "wxZM6996";
            $mail->From = "farvizu@smtp.upsidewireless.com";
            $mail->FromName = "Intesystem";
            $mail->addAddress($phone . "@sms.upsidewireless.com", "test phone");
            $mail->Subject = "Test sms intesys";
            $mail->Body = "Un ticket fue dado de alta";
            $mail->send();
            $messages[] = 'Un ticket fue dado de alta';

        } catch (Exception $e) {
            $errors [] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    }

    /* ----------------------- New users -------------------------------- */
    public function sendMail_newUser($fullname, $email, $password, $username, $company)
    {
        $mail = new PHPMailer(true);
        $mail->CharSet = "UTF-8";
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = 'login';
        $mail->Username = 'no-reply@intesystem.net';
        $mail->Password = 'UN!cyHcInv3o';
        $mail->SMTPSecure = 'ssl';
        $mail->Host = 'mail.intesystem.net';
        $mail->Port = 465;
        $mail->SetFrom('no-reply@intesystem.net', 'INTESYSTEM');
        $mail->Subject = 'Bienvenido ' . $fullname;
        $mail->Body = '
        
<!DOCTYPE html>
<html>
<head>
    <title>Sistema de Tickets | INTESYSTEM</title>
    <link href=http://soporte.intesystem.net/favicon.ico rel="shortcut icon">
    <!-- Custom Theme files -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="keywords" content=""/>
    <!-- //Custom Theme files -->
    <!-- Responsive Styles and Valid Styles -->
    <style type="text/css">

        body {
            width: 100%;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        html {
            width: 100%;
        }

        table {
            font-size: 14px;
            border: 0;
        }

        .btn-ini {
            -moz-box-shadow: inset 0px 1px 0px 0px #ffffff;
            -webkit-box-shadow: inset 0px 1px 0px 0px #ffffff;
            box-shadow: inset 0px 1px 0px 0px #ffffff;
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #2770ca), color-stop(1, #3f5367));
            background: -moz-linear-gradient(top, #2770ca 5%, #3f5367 100%);
            background: -webkit-linear-gradient(top, #2770ca 5%, #3f5367 100%);
            background: -o-linear-gradient(top, #2770ca 5%, #3f5367 100%);
            background: -ms-linear-gradient(top, #2770ca 5%, #3f5367 100%);
            background: linear-gradient(to bottom, #2770ca 5%, #3f5367 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=\'#2770ca\', endColorstr=\'#3f5367\', GradientType=0);
            background-color: #2770ca;
            -moz-border-radius: 6px;
            -webkit-border-radius: 6px;
            border-radius: 6px;
            border: 1px solid #ffffff;
            display: inline-block;
            cursor: pointer;
            color: #ffffff;
            font-family: Arial;
            font-size: 15px;
            font-weight: bold;
            padding: 10px 48px;
            text-decoration: none;
            text-shadow: 0px 1px 0px #ffffff;
        }

        .btn-ini:hover {
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #3f5367), color-stop(1, #2770ca));
            background: -moz-linear-gradient(top, #3f5367 5%, #2770ca 100%);
            background: -webkit-linear-gradient(top, #3f5367 5%, #2770ca 100%);
            background: -o-linear-gradient(top, #3f5367 5%, #2770ca 100%);
            background: -ms-linear-gradient(top, #3f5367 5%, #2770ca 100%);
            background: linear-gradient(to bottom, #3f5367 5%, #2770ca 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=\'#3f5367\', endColorstr=\'#2770ca\', GradientType=0);
            background-color: #3f5367;
        }

        .btn-ini:active {
            position: relative;
            top: 1px;
        }

        .lcolor {
            font-size: 1.1em;
            padding: 5px 20px;
            border-left-width: 10px;
            border-left-style: solid;
            border-left-color: orange;
        }

        /* ----------- responsivity ----------- */
        @media only screen and (max-width: 800px) {
            table.container.top-header-left {
                width: 726px;
            }
        }

        @media only screen and (max-width: 736px) {
            table.container.top-header-left {
                width: 684px;
            }
        }

        @media only screen and (max-width: 667px) {
            table.container.top-header-left {
                width: 600px;
            }

            table.container-middle {
                width: 565px;
            }

            a.logo-text img {
                width: 100%;
                height: inherit;
            }

            table.logo {
                width: 40%;
            }

            table.mail_left {
                width: 200px;
            }

            td.mail_gd, td.mail_gd a {
                text-align: center !important;
            }

            td.ban_pad {
                height: 48px;
            }

            td.future {
                font-size: 2em !important;
            }

            td.ban_tex {
                height: 18px;
            }


            td.ser_pad {
                height: 50px;
            }

            td.wel_text {
                font-size: 2.1em !important;
            }

            td.ser_text {
                line-height: 2em !important;
                font-size: 1em !important;
            }

            table.twelve_one {
                width: 316px;
            }

            table.twelve_two {
                width: 229px;
            }

            td.pic_one img {
                width: 100%;
                height: inherit;
            }

            table.ser_left_two {
                width: 100px;
            }

            table.ser_left_one {
                width: 200px;
            }

            img.full {
                width: 100%;
            }

            table.twelve_three {
                width: 272px;
            }

            td.ser_text2 {
                font-size: 1.5em !important;
            }

            table.cir_left {
                width: 276px;
            }

            table.twelve_four {
                width: 200px;
            }

            table.twelve_five {
                width: 337px;
            }

            td.ser_one {
                height: 45px;
            }

            table.foo {
                width: 370px;
            }
        }

        @media only screen and (max-width: 640px) {
            td.ser_one {
                height: 60px;
            }

            .top-bottom-bg {
                width: 420px !important;
                height: auto !important;
            }

            table.container-middle.navi-grid {
                width: 360px !important;
            }

            table.logo {
                width: 45%;
            }
        }

        @media only screen and (max-width: 600px) {
            table.container.top-header-left {
                width: 535px;
            }

            table.container-middle {
                width: 485px;
            }



            table.ser_left_one {
                width: 151px;
            }

            table.ser_left_two {
                width: 86px;
            }

            table.twelve_one {
                width: 239px;
            }

            table.twelve_two {
                width: 221px;
            }

            table.twelve_three {
                width: 100%;
            }

            img.full {
                width: inherit;
            }

            table.cir_left {
                width: 230px;
            }

            table.cir_left img {
                width: 54%;
                height: inherit;
            }

            img.full.team_img {
                width: 100% !important;
                height: inherit;
            }

            table.twelve_four {
                width: 160px;
            }

            table.twelve_five {
                width: 298px;
            }

            td.team_pad {
                height: 0;
            }

            table.foo {
                width: 100%;
            }

            td.ser_text.editable {
                text-align: center;
            }

            table.foo1 {
                width: 100%;
            }
        }

        @media only screen and (max-width: 568px) {
            /*-- w3layouts --*/
            table.container.top-header-left {
                width: 495px !important;
            }

            table.ban_info {
                width: 400px;
            }

            td.future {
                font-size: 1.8em !important;
            }



            table.container-middle {
                width: 449px;
            }

            table.twelve_two {
                width: 190px;
            }

            td.ser_one {
                height: 34px;
            }

            td.ser_two {
                height: 21px;
            }

            table.cir_left {
                width: 100%;
            }

            table.cir_left img {
                width: 30%;
                height: inherit;
            }

            table.twelve_four {
                width: 100%;
            }

            img.full.team_img {
                width: 45% !important;
                height: inherit;
            }

            table.twelve_five {
                width: 100%;
            }

            td.text_team {
                text-align: center;
            }

            td.twel_pad {
                height: 25px;
            }
        }

        /*-- agileits --*/
        @media only screen and (max-width: 480px) {
            .container {
                width: 290px !important;
            }

            .container-middle {
                width: 85% !important;
            }

            .mainContent {
                width: 240px !important;
            }

            .top-bottom-bg {
                width: 260px !important;
                height: auto !important;
            }

            table.logo {
                width: 33% !important;
            }

            table.container.top-header-left {
                width: 422px !important;
            }

            table.container-middle.navi-grid {
                width: 399px !important;
            }

            table.container-middle.nav-head {
                width: 350px !important;
            }

            table.twelve_one {
                width: 100%;
            }

            table.ser_left_one {
                width: 271px;
            }

            table.twelve_two {
                width: 100%;
            }

            td.pic_one {
                text-align: center !important;
            }

            td.pic_one img {
                width: 70%;
                height: inherit;
            }

            td.ser_pad {
                height: 32px;
            }

            td.future {
                font-size: 1.5em !important;
            }

            table.ban_info {
                width: 348px;
            }

            table.logo {
                width: 43% !important;
            }



            td.ban_pad {
                height: 24px;
            }

            table.logo {
                width: 54% !important;
            }

            td.ser_text {
                font-size: 13px !important;
            }
        }

        @media only screen and (max-width: 414px) {
            table.container.top-header-left {
                width: 397px !important;
            }

            table.container-middle.navi-grid {
                width: 372px !important;
            }

            table.container.top-header-left {
                width: 370px !important;
            }

            .container-middle {
                width: 95% !important;
            }

            table.ser_left_one {
                width: 255px;
            }
        }

        @media only screen and (max-width: 384px) {

            table.container-middle.navi-grid, table.container.top-header-left {
                width: 300px !important;
            }

            table.ban_info {
                width: 297px;
            }

            td.future {
                font-size: 1.3em !important;
            }

            .container-middle {
                width: 90% !important;
            }

            table.ban_info {
                width: 310px;
            }

            /*-- agileits --*/
            table.container-middle.nav-head {
                width: 340px !important;
            }

            table.ser_left_one {
                width: 216px;
            }

            table.mail_left, table.mail_right {
                width: 100%;
                height: 38px;
            }



            td.ser_one {
                height: 11px;
            }
        }

        @media only screen and (max-width: 320px) {
            td.wel_text {
                font-size: 1.9em !important;
            }

            img.full {
                width: 100%;
            }

            table.container.top-header-left {
                width: 284px !important;
            }

            table.container-middle.nav-head {
                width: 257px !important;
            }

            table.ban_info {
                width: 257px;
            }

            td.future {
                font-size: 1.2em !important;
            }

            td.ban_tex {
                height: 10px;
            }



            table.logo {
                width: 56% !important;
            }

            td.top_mar {
                height: 6px;
            }

            table.mail_left, table.mail_right {
                width: 100%;
                height: 29px;
            }

            table.ser_left_one {
                width: 181px;
            }

            table.ser_left_two {
                width: 73px;
            }

            td.pic_one img {
                width: 100%;
            }

            table.cir_left img {
                width: 37%;
            }

            td.thompson {
                font-size: 1.5em !important;
            }

            table.follow {
                width: 100%;
            }

            table.follow td {
                text-align: center !important;
            }

            table.logo {
                width: 69% !important;
            }
        }
    </style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table border="0" width="100%" cellpadding="0" cellspacing="0">

    <tr>
        <td width="100%" align="center" valign="top" bgcolor="062f3c" style="">
            <table>
                <tr>
                    <td class="top_mar" height="50"></td>
                </tr>
            </table>
            <!-- main content -->
            <table style="box-shadow:0px 0px 0px 0px #E0E0E0;" width="800" border="0" cellpadding="0" cellspacing="0"
                   align="center" class="container top-header-left">
                <tr bgcolor="2771ca">
                    <td>
                        <table border="0" width="650" align="center" cellpadding="0" cellspacing="0"
                               class="container-middle nav-head">
                            <tr>
                                <td height="15"></td>
                            </tr>
                            <tr>
                                <td>
                                    <table border="0" align="center" cellpadding="0" cellspacing="0"
                                           style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
                                           class="logo">
                                        <tbody>

                                        <tr>
                                            <td align="right">
                                                <a href="#" class="logo-text" style="text-decoration:none; color:#fff;">
                                                    <h1>BIENVENIDO ' . $fullname . '</h1>
                                                </a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td height="15"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr bgcolor="#3f5367">
                    <td>
                        <table border="0" width="650" align="center" cellpadding="0" cellspacing="0"
                               class="container-middle nav-head">
                            <tr>
                                <td height="15"></td>
                            </tr>
                            <tr>
                                <td>
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0"
                                           style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <table class="mail_left" align="left" border="0" cellpadding="0"
                                                       cellspacing="0" width="450">
                                                    <tbody>
                                                    <tr>
                                                        <td class="mail_gd" align="center"
                                                            style=" text-align: left; font-size:1.2em; font-family:Candara; color: #FFFFFF;">
                                                            <a href="mailto:contacto@intesystem.com.mx"
                                                               style="color:#fff;text-decoration:none">
                                                                <img src="http://soporte.intesystem.net/email/images/envelope.png"
                                                                     alt="" border="0"
                                                                     height="18" width="18">&nbsp; &nbsp;
                                                                contacto@intesystem.com.mx
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <table class="mail_right" align="right" border="0" cellpadding="0"
                                                       cellspacing="0" width="200">
                                                    <tbody>
                                                    <tr>
                                                        <td align="center"
                                                            style="font-size:14px;color:#f5f5f5;font-family:Arial,serif">
                                                            <img src="http://soporte.intesystem.net/email/images/1.png"
                                                                 alt="" border="0" height="16"
                                                                 width="16">&nbsp;
                                                            &nbsp;33 3629 3619 / 33 1816 3347 / 33 2005 2286
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td height="15"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr bgcolor="#ffffff">
                    <td>
                        <table border="0" width="650" align="center" cellpadding="0" cellspacing="0"
                               class="container-middle">
                            <tbody>
                            <!-- padding-top -->
                            <tr>
                                <td class="ser_pad" height="70"></td>

                            </tr>
                            <!-- //padding-top -->
                            <tr>
                                <td><h1 style="color: #414141;">' . $company . '</h1></td>
                            </tr>
                            <tr>
                                <td class="wel_text lcolor" align="center">
                                    <h2 style="font-size:1.2em;color:#2771ca;font-family:Candara;text-align:left;font-weight:600; margin-top: 5px">
                                        Usuario:
                                        <small style="color:#4f4f4f!important; font-size: 0.8em">' . $username . ' / ' . $email . '</small>
                                    </h2>
                                    <h2 style="font-size:1.2em;color:#2771ca;font-family:Candara;text-align:left;font-weight:600; margin-top: 5px">
                                        Contraseña:
                                        <!-- <small style="color:#4f4f4f; font-size: 0.8em">' . $password . '</small> -->
                                        <small style="color:#4f4f4f; font-size: 0.8em">***********</small>
                                    </h2>
                                    <div align="left">
                                        <a href="http://soporte.intesystem.net/" target="_blank" class="btn-ini" style="color:#fff">
                                            Iniciar Sesión
                                        </a>
                                    </div>
                                </td>

                            </tr>


                            <!-- padding-top -->
                            <tr>
                                <td class="ser_pad" height="70"></td>
                            </tr>
                            <!-- //padding-top -->
                            </tbody>
                        </table>
                    </td>
                </tr>


                <tr>
                    <td>
                        <table align="center" cellpadding="0" cellspacing="0" style="border-bottom:1px solid #f7f7f7">
                        </table>
                    </td>
                </tr>

                <tr bgcolor="2771ca">
                    <td>
                        <table border="0" width="650" align="center" cellpadding="0" cellspacing="0"
                               class="container-middle">
                            <tr>
                                <td height="10" style="font-size: 1px; line-height: 10px;">&nbsp;</td>
                            </tr>

                            <tr>
                                <td>

                                    <table class="foo" width="375" border="0" align="left" cellpadding="0"
                                           cellspacing="0">
                                        <tr>
                                            <td class="ser_text editable"
                                                style="font-family: Candara; font-size: 1em; color: #ffffff; line-height: 24px;">
                                                © 2019 INTESYSTEM . All Rights Reserved.</a>
                                            </td>
                                            <td style="float: right;">
                                                <a href="intesystem.com.mx" target="_blank"
                                                   style="font-family: Candara; font-size: 1em; color: #ffffff; float: right; line-height: 24px;">
                                                    intesystem.com.mx
                                                </a>
                                            </td>
                                        </tr>
                                    </table>

                                    <!-- SPACE -->
                                    <table width="1" height="10" border="0" cellpadding="0" cellspacing="0"
                                           align="left">
                                        <tr>
                                            <td height="10"
                                                style="font-size: 0;line-height: 0;border-collapse: collapse;padding-left: 24px;">
                                                &nbsp;
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- END SPACE -->

                                    <table class="foo1" width="170" border="0" align="right" cellpadding="0"
                                           cellspacing="0">
                                        <tr>
                                            <td class="ser_text editable"
                                                style="font-family: Candara; font-size: 1em; color: #ffffff; line-height: 24px;">


                                            </td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>

                            <tr>
                                <td height="10" style="font-size: 1px; line-height: 10px;">&nbsp;</td>
                            </tr>

                        </table>
                    </td>
                </tr>

            </table>
            <table>
                <tr>
                    <td class="top_mar" height="50"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
         ';
        $mail->IsHTML(true);
        $mail->AddAddress($email);
//        $mail->AddCC('freddarvizu@gmail.com');

        if (!$mail->send()) {
            echo 'Message was not sent.';
            echo 'Mailer error: ' . $mail->ErrorInfo;
            error_log("\n" . date('Y-m-d h:i:s A') . " ===> El mensaje no ha sido enviado. (" . $email . ")", 3, "../log/Email_NewUser.log");
        } else {
            error_log("\n" . date('Y-m-d h:i:s A') . " ===> El mensaje ha sido enviado. (" . $email . ")", 3, "../log/Email_NewUser.log");
        }

    }

    /* -----------------------Forgot Password -------------------------------- */
    public function sendMail_forgotPass($username, $token, $fullname, $email)
    {

        $mail = new PHPMailer(true);
        $mail->CharSet = "UTF-8";
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = 'login';
        $mail->Username = 'no-reply@intesystem.net';
        $mail->Password = 'UN!cyHcInv3o';
        $mail->SMTPSecure = 'ssl';
        $mail->Host = 'mail.intesystem.net';
        $mail->Port = 465;
        $mail->SetFrom('no-reply@intesystem.net', 'INTESYSTEM');
        $mail->Subject = 'Recuperación de contraseña de Mi Cuenta SO Intesys';
        $mail->Body = '
        
        
        
<!DOCTYPE html>
<html>
<head>
    <title>Sistema de Tickets | INTESYSTEM</title>
    <link href=http://soporte.intesystem.net/favicon.ico rel="shortcut icon">
    <!-- Custom Theme files -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="keywords" content=""/>
    <!-- //Custom Theme files -->
    <!-- Responsive Styles and Valid Styles -->
    <style type="text/css">

        body {
            width: 100%;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        html {
            width: 100%;
        }

        table {
            font-size: 14px;
            border: 0;
        }

        .btn-ini {
            -moz-box-shadow: inset 0px 1px 0px 0px #ffffff;
            -webkit-box-shadow: inset 0px 1px 0px 0px #ffffff;
            box-shadow: inset 0px 1px 0px 0px #ffffff;
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #2770ca), color-stop(1, #3f5367));
            background: -moz-linear-gradient(top, #2770ca 5%, #3f5367 100%);
            background: -webkit-linear-gradient(top, #2770ca 5%, #3f5367 100%);
            background: -o-linear-gradient(top, #2770ca 5%, #3f5367 100%);
            background: -ms-linear-gradient(top, #2770ca 5%, #3f5367 100%);
            background: linear-gradient(to bottom, #2770ca 5%, #3f5367 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=\'#2770ca\', endColorstr=\'#3f5367\', GradientType=0);
            background-color: #2770ca;
            -moz-border-radius: 6px;
            -webkit-border-radius: 6px;
            border-radius: 6px;
            border: 1px solid #ffffff;
            display: inline-block;
            cursor: pointer;
            color: #ffffff;
            font-family: Arial;
            font-size: 15px;
            font-weight: bold;
            padding: 10px 48px;
            text-decoration: none;
            text-shadow: 0px 1px 0px #ffffff;
        }

        .btn-ini:hover {
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #3f5367), color-stop(1, #2770ca));
            background: -moz-linear-gradient(top, #3f5367 5%, #2770ca 100%);
            background: -webkit-linear-gradient(top, #3f5367 5%, #2770ca 100%);
            background: -o-linear-gradient(top, #3f5367 5%, #2770ca 100%);
            background: -ms-linear-gradient(top, #3f5367 5%, #2770ca 100%);
            background: linear-gradient(to bottom, #3f5367 5%, #2770ca 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=\'#3f5367\', endColorstr=\'#2770ca\', GradientType=0);
            background-color: #3f5367;
        }

        .btn-ini:active {
            position: relative;
            top: 1px;
        }

        .lcolor {
            font-size: 1.1em;
            padding: 5px 20px;
            border-left-width: 10px;
            border-left-style: solid;
            border-left-color: orange;
        }

        /* ----------- responsivity ----------- */
        @media only screen and (max-width: 800px) {
            table.container.top-header-left {
                width: 726px;
            }
        }

        @media only screen and (max-width: 736px) {
            table.container.top-header-left {
                width: 684px;
            }
        }

        @media only screen and (max-width: 667px) {
            table.container.top-header-left {
                width: 600px;
            }

            table.container-middle {
                width: 565px;
            }

            a.logo-text img {
                width: 100%;
                height: inherit;
            }

            table.logo {
                width: 40%;
            }

            table.mail_left {
                width: 200px;
            }

            td.mail_gd, td.mail_gd a {
                text-align: center !important;
            }

            td.ban_pad {
                height: 48px;
            }

            td.future {
                font-size: 2em !important;
            }

            td.ban_tex {
                height: 18px;
            }


            td.ser_pad {
                height: 50px;
            }

            td.wel_text {
                font-size: 2.1em !important;
            }

            td.ser_text {
                line-height: 2em !important;
                font-size: 1em !important;
            }

            table.twelve_one {
                width: 316px;
            }

            table.twelve_two {
                width: 229px;
            }

            td.pic_one img {
                width: 100%;
                height: inherit;
            }

            table.ser_left_two {
                width: 100px;
            }

            table.ser_left_one {
                width: 200px;
            }

            img.full {
                width: 100%;
            }

            table.twelve_three {
                width: 272px;
            }

            td.ser_text2 {
                font-size: 1.5em !important;
            }

            table.cir_left {
                width: 276px;
            }

            table.twelve_four {
                width: 200px;
            }

            table.twelve_five {
                width: 337px;
            }

            td.ser_one {
                height: 45px;
            }

            table.foo {
                width: 370px;
            }
        }

        @media only screen and (max-width: 640px) {
            td.ser_one {
                height: 60px;
            }

            .top-bottom-bg {
                width: 420px !important;
                height: auto !important;
            }

            table.container-middle.navi-grid {
                width: 360px !important;
            }

            table.logo {
                width: 45%;
            }
        }

        @media only screen and (max-width: 600px) {
            table.container.top-header-left {
                width: 535px;
            }

            table.container-middle {
                width: 485px;
            }


            table.ser_left_one {
                width: 151px;
            }

            table.ser_left_two {
                width: 86px;
            }

            table.twelve_one {
                width: 239px;
            }

            table.twelve_two {
                width: 221px;
            }

            table.twelve_three {
                width: 100%;
            }

            img.full {
                width: inherit;
            }

            table.cir_left {
                width: 230px;
            }

            table.cir_left img {
                width: 54%;
                height: inherit;
            }

            img.full.team_img {
                width: 100% !important;
                height: inherit;
            }

            table.twelve_four {
                width: 160px;
            }

            table.twelve_five {
                width: 298px;
            }

            td.team_pad {
                height: 0;
            }

            table.foo {
                width: 100%;
            }

            td.ser_text.editable {
                text-align: center;
            }

            table.foo1 {
                width: 100%;
            }
        }

        @media only screen and (max-width: 568px) {
            /*-- w3layouts --*/
            table.container.top-header-left {
                width: 495px !important;
            }

            table.ban_info {
                width: 400px;
            }

            td.future {
                font-size: 1.8em !important;
            }


            table.container-middle {
                width: 449px;
            }

            table.twelve_two {
                width: 190px;
            }

            td.ser_one {
                height: 34px;
            }

            td.ser_two {
                height: 21px;
            }

            table.cir_left {
                width: 100%;
            }

            table.cir_left img {
                width: 30%;
                height: inherit;
            }

            table.twelve_four {
                width: 100%;
            }

            img.full.team_img {
                width: 45% !important;
                height: inherit;
            }

            table.twelve_five {
                width: 100%;
            }

            td.text_team {
                text-align: center;
            }

            td.twel_pad {
                height: 25px;
            }
        }

        /*-- agileits --*/
        @media only screen and (max-width: 480px) {
            .container {
                width: 290px !important;
            }

            .container-middle {
                width: 85% !important;
            }

            .mainContent {
                width: 240px !important;
            }

            .top-bottom-bg {
                width: 260px !important;
                height: auto !important;
            }

            table.logo {
                width: 33% !important;
            }

            table.container.top-header-left {
                width: 422px !important;
            }

            table.container-middle.navi-grid {
                width: 399px !important;
            }

            table.container-middle.nav-head {
                width: 350px !important;
            }

            table.twelve_one {
                width: 100%;
            }

            table.ser_left_one {
                width: 271px;
            }

            table.twelve_two {
                width: 100%;
            }

            td.pic_one {
                text-align: center !important;
            }

            td.pic_one img {
                width: 70%;
                height: inherit;
            }

            td.ser_pad {
                height: 32px;
            }

            td.future {
                font-size: 1.5em !important;
            }

            table.ban_info {
                width: 348px;
            }

            table.logo {
                width: 43% !important;
            }



            td.ban_pad {
                height: 24px;
            }

            table.logo {
                width: 54% !important;
            }

            td.ser_text {
                font-size: 13px !important;
            }
        }

        @media only screen and (max-width: 414px) {
            table.container.top-header-left {
                width: 397px !important;
            }

            table.container-middle.navi-grid {
                width: 372px !important;
            }

            table.container.top-header-left {
                width: 370px !important;
            }

            .container-middle {
                width: 95% !important;
            }

            table.ser_left_one {
                width: 255px;
            }
        }

        @media only screen and (max-width: 384px) {

            table.container-middle.navi-grid, table.container.top-header-left {
                width: 300px !important;
            }

            table.ban_info {
                width: 297px;
            }

            td.future {
                font-size: 1.3em !important;
            }

            .container-middle {
                width: 90% !important;
            }

            table.ban_info {
                width: 310px;
            }

            /*-- agileits --*/
            table.container-middle.nav-head {
                width: 340px !important;
            }

            table.ser_left_one {
                width: 216px;
            }

            table.mail_left, table.mail_right {
                width: 100%;
                height: 38px;
            }



            td.ser_one {
                height: 11px;
            }
        }

        @media only screen and (max-width: 320px) {
            td.wel_text {
                font-size: 1.9em !important;
            }

            img.full {
                width: 100%;
            }

            table.container.top-header-left {
                width: 284px !important;
            }

            table.container-middle.nav-head {
                width: 257px !important;
            }

            table.ban_info {
                width: 257px;
            }

            td.future {
                font-size: 1.2em !important;
            }

            td.ban_tex {
                height: 10px;
            }


            table.logo {
                width: 56% !important;
            }

            td.top_mar {
                height: 6px;
            }

            table.mail_left, table.mail_right {
                width: 100%;
                height: 29px;
            }

            table.ser_left_one {
                width: 181px;
            }

            table.ser_left_two {
                width: 73px;
            }

            td.pic_one img {
                width: 100%;
            }

            table.cir_left img {
                width: 37%;
            }

            td.thompson {
                font-size: 1.5em !important;
            }

            table.follow {
                width: 100%;
            }

            table.follow td {
                text-align: center !important;
            }

            table.logo {
                width: 69% !important;
            }
        }
    </style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table border="0" width="100%" cellpadding="0" cellspacing="0">

    <tr>
        <td width="100%" align="center" valign="top" bgcolor="062f3c" style="">
            <table>
                <tr>
                    <td class="top_mar" height="50"></td>
                </tr>
            </table>
            <!-- main content -->
            <table style="box-shadow:0px 0px 0px 0px #E0E0E0;" width="800" border="0" cellpadding="0" cellspacing="0"
                   align="center" class="container top-header-left">
                <tr bgcolor="2771ca">
                    <td>
                        <table border="0" width="650" align="center" cellpadding="0" cellspacing="0"
                               class="container-middle nav-head">
                            <tr>
                                <td height="15"></td>
                            </tr>
                            <tr>
                                <td>
                                    <table border="0" align="center" cellpadding="0" cellspacing="0"
                                           style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
                                           class="logo">
                                        <tbody>

                                        <tr>
                                            <td align="right">
                                                <a href="#" class="logo-text" style="text-decoration:none; color:#fff;">
                                                    <h1>RECUPERAR CONTRASEÑA</h1>
                                                </a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td height="15"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr bgcolor="#3f5367">
                    <td>
                        <table border="0" width="650" align="center" cellpadding="0" cellspacing="0"
                               class="container-middle nav-head">
                            <tr>
                                <td height="15"></td>
                            </tr>
                            <tr>
                                <td>
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0"
                                           style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <table class="mail_left" align="left" border="0" cellpadding="0"
                                                       cellspacing="0" width="450">
                                                    <tbody>
                                                    <tr>
                                                        <td class="mail_gd" align="center"
                                                            style=" text-align: left; font-size:1.2em; font-family:Candara; color: #FFFFFF;">
                                                            <a href="mailto:contacto@intesystem.com.mx"
                                                               style="color:#fff;text-decoration:none">
                                                                <img src="http://soporte.intesystem.net/email/images/envelope.png"
                                                                     alt="" border="0"
                                                                     height="18" width="18">&nbsp; &nbsp;
                                                                contacto@intesystem.com.mx
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <table class="mail_right" align="right" border="0" cellpadding="0"
                                                       cellspacing="0" width="200">
                                                    <tbody>
                                                    <tr>
                                                        <td align="center"
                                                            style="font-size:14px;color:#f5f5f5;font-family:Arial,serif">
                                                            <img src="http://soporte.intesystem.net/email/images/1.png"
                                                                 alt="" border="0" height="16"
                                                                 width="16">&nbsp;
                                                            &nbsp;33 3629 3619 / 33 1816 3347 / 33 2005 2286
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td height="15"></td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr bgcolor="#ffffff">
                    <td>
                        <table border="0" width="650" align="center" cellpadding="0" cellspacing="0"
                               class="container-middle">
                            <tbody>
                            <!-- padding-top -->
                            <tr>
                                <td class="ser_pad" height="70"></td>

                            </tr>
                            <!-- //padding-top -->
                            <tr>
                                <td>
                                    <h1>Hola ' . $fullname . '</h1>
                                    <p style="font-size: 22px; color: #414141;">
                                        Para recuperar tu acceso a <b>SO Intesys</b> debes de cambiar tu contraseña. Haz
                                        clic en el botón y sigue las instrucciones.
                                    </p>
                                    <small>El enlace caduca en 1 hora.</small>
                                </td>
                            </tr>
                            <tr>
                                <td class="wel_text lcolor" align="center">
                                    <div align="left">
                                        <a href="http://soporte.intesystem.net/reset-password?t=' . $token . '"
                                           target="_blank" class="btn-ini" style="color:#fff">
                                            Cambia tu Contraseña
                                        </a>
                                    </div>
                                </td>

                            </tr>


                            <!-- padding-top -->
                            <tr>
                                <td class="ser_pad" height="70"></td>
                            </tr>
                            <!-- //padding-top -->
                            </tbody>
                        </table>
                    </td>
                </tr>


                <tr>
                    <td>
                        <table align="center" cellpadding="0" cellspacing="0" style="border-bottom:1px solid #f7f7f7">
                        </table>
                    </td>
                </tr>

                <tr bgcolor="2771ca">
                    <td>
                        <table border="0" width="650" align="center" cellpadding="0" cellspacing="0"
                               class="container-middle">
                            <tr>
                                <td height="10" style="font-size: 1px; line-height: 10px;">&nbsp;</td>
                            </tr>

                            <tr>
                                <td>

                                    <table class="foo" width="375" border="0" align="left" cellpadding="0"
                                           cellspacing="0">
                                        <tr>
                                            <td class="ser_text editable"
                                                style="font-family: Candara; font-size: 1em; color: #ffffff; line-height: 24px;">
                                                © 2019 INTESYSTEM . All Rights Reserved.</a>
                                            </td>
                                            <td style="float: right;">
                                                <a href="intesystem.com.mx" target="_blank"
                                                   style="font-family: Candara; font-size: 1em; color: #ffffff; float: right; line-height: 24px;">
                                                    intesystem.com.mx
                                                </a>
                                            </td>
                                        </tr>
                                    </table>

                                    <!-- SPACE -->
                                    <table width="1" height="10" border="0" cellpadding="0" cellspacing="0"
                                           align="left">
                                        <tr>
                                            <td height="10"
                                                style="font-size: 0;line-height: 0;border-collapse: collapse;padding-left: 24px;">
                                                &nbsp;
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- END SPACE -->

                                    <table class="foo1" width="170" border="0" align="right" cellpadding="0"
                                           cellspacing="0">
                                        <tr>
                                            <td class="ser_text editable"
                                                style="font-family: Candara; font-size: 1em; color: #ffffff; line-height: 24px;">


                                            </td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>

                            <tr>
                                <td height="10" style="font-size: 1px; line-height: 10px;">&nbsp;</td>
                            </tr>

                        </table>
                    </td>
                </tr>

            </table>
            <table>
                <tr>
                    <td class="top_mar" height="50"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
        
        
         ';
        $mail->IsHTML(true);
        $mail->AddAddress($email);
//        $mail->AddCC('freddarvizu@gmail.com');

        if (!$mail->send()) {
            echo 'Message was not sent.';
            echo 'Mailer error: ' . $mail->ErrorInfo;
            error_log("\n" . date('Y-m-d h:i:s A') . " ===> El mensaje no ha sido enviado. (" . $email . ")", 3, "../log/Email_ForgotPass.log");
        } else {
            error_log("\n" . date('Y-m-d h:i:s A') . " ===> El mensaje ha sido enviado. (" . $email . ")", 3, "../log/Email_ForgotPass.log");
        }


    }

    public function sendMail_resetPass($fullname, $email)
    {

        $mail = new PHPMailer(true);
        $mail->CharSet = "UTF-8";
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = 'login';
        $mail->Username = 'no-reply@intesystem.net';
        $mail->Password = 'UN!cyHcInv3o';
        $mail->SMTPSecure = 'ssl';
        $mail->Host = 'mail.intesystem.net';
        $mail->Port = 465;
        $mail->SetFrom('no-reply@intesystem.net', 'INTESYSTEM');
        $mail->Subject = 'Actualización de contraseña';
        $mail->Body = '
        

<!DOCTYPE html>
<html>
<head>
    <title>Sistema de Tickets | INTESYSTEM</title>
    <link href=http://soporte.intesystem.net/favicon.ico rel="shortcut icon">
    <!-- Custom Theme files -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="keywords" content=""/>
    <!-- //Custom Theme files -->
    <!-- Responsive Styles and Valid Styles -->
    <style type="text/css">

        body {
            width: 100%;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        html {
            width: 100%;
        }

        table {
            font-size: 14px;
            border: 0;
        }

        .btn-ini {
            -moz-box-shadow: inset 0px 1px 0px 0px #ffffff;
            -webkit-box-shadow: inset 0px 1px 0px 0px #ffffff;
            box-shadow: inset 0px 1px 0px 0px #ffffff;
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #2770ca), color-stop(1, #3f5367));
            background: -moz-linear-gradient(top, #2770ca 5%, #3f5367 100%);
            background: -webkit-linear-gradient(top, #2770ca 5%, #3f5367 100%);
            background: -o-linear-gradient(top, #2770ca 5%, #3f5367 100%);
            background: -ms-linear-gradient(top, #2770ca 5%, #3f5367 100%);
            background: linear-gradient(to bottom, #2770ca 5%, #3f5367 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=\' #2770ca\', endColorstr=\' #3f5367\', GradientType=0);
            background-color: #2770ca;
            -moz-border-radius: 6px;
            -webkit-border-radius: 6px;
            border-radius: 6px;
            border: 1px solid #ffffff;
            display: inline-block;
            cursor: pointer;
            color: #ffffff;
            font-family: Arial;
            font-size: 15px;
            font-weight: bold;
            padding: 10px 48px;
            text-decoration: none;
            text-shadow: 0px 1px 0px #ffffff;
        }

        .btn-ini:hover {
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #3f5367), color-stop(1, #2770ca));
            background: -moz-linear-gradient(top, #3f5367 5%, #2770ca 100%);
            background: -webkit-linear-gradient(top, #3f5367 5%, #2770ca 100%);
            background: -o-linear-gradient(top, #3f5367 5%, #2770ca 100%);
            background: -ms-linear-gradient(top, #3f5367 5%, #2770ca 100%);
            background: linear-gradient(to bottom, #3f5367 5%, #2770ca 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=\' #3f5367\', endColorstr=\' #2770ca\', GradientType=0);
            background-color: #3f5367;
        }

        .btn-ini:active {
            position: relative;
            top: 1px;
        }

        .lcolor {
            font-size: 1.1em;
            padding: 5px 20px;
            border-left-width: 10px;
            border-left-style: solid;
            border-left-color: orange;
        }

        /* ----------- responsivity ----------- */
        @media only screen and (max-width: 800px) {
            table.container.top-header-left {
                width: 726px;
            }
        }

        @media only screen and (max-width: 736px) {
            table.container.top-header-left {
                width: 684px;
            }
        }

        @media only screen and (max-width: 667px) {
            table.container.top-header-left {
                width: 600px;
            }

            table.container-middle {
                width: 565px;
            }

            a.logo-text img {
                width: 100%;
                height: inherit;
            }

            table.logo {
                width: 40%;
            }

            table.mail_left {
                width: 200px;
            }

            td.mail_gd, td.mail_gd a {
                text-align: center !important;
            }

            td.ban_pad {
                height: 48px;
            }

            td.future {
                font-size: 2em !important;
            }

            td.ban_tex {
                height: 18px;
            }


            td.ser_pad {
                height: 50px;
            }

            td.wel_text {
                font-size: 2.1em !important;
            }

            td.ser_text {
                line-height: 2em !important;
                font-size: 1em !important;
            }

            table.twelve_one {
                width: 316px;
            }

            table.twelve_two {
                width: 229px;
            }

            td.pic_one img {
                width: 100%;
                height: inherit;
            }

            table.ser_left_two {
                width: 100px;
            }

            table.ser_left_one {
                width: 200px;
            }

            img.full {
                width: 100%;
            }

            table.twelve_three {
                width: 272px;
            }

            td.ser_text2 {
                font-size: 1.5em !important;
            }

            table.cir_left {
                width: 276px;
            }

            table.twelve_four {
                width: 200px;
            }

            table.twelve_five {
                width: 337px;
            }

            td.ser_one {
                height: 45px;
            }

            table.foo {
                width: 370px;
            }
        }

        @media only screen and (max-width: 640px) {
            td.ser_one {
                height: 60px;
            }

            .top-bottom-bg {
                width: 420px !important;
                height: auto !important;
            }

            table.container-middle.navi-grid {
                width: 360px !important;
            }

            table.logo {
                width: 45%;
            }
        }

        @media only screen and (max-width: 600px) {
            table.container.top-header-left {
                width: 535px;
            }

            table.container-middle {
                width: 485px;
            }


            table.ser_left_one {
                width: 151px;
            }

            table.ser_left_two {
                width: 86px;
            }

            table.twelve_one {
                width: 239px;
            }

            table.twelve_two {
                width: 221px;
            }

            table.twelve_three {
                width: 100%;
            }

            img.full {
                width: inherit;
            }

            table.cir_left {
                width: 230px;
            }

            table.cir_left img {
                width: 54%;
                height: inherit;
            }

            img.full.team_img {
                width: 100% !important;
                height: inherit;
            }

            table.twelve_four {
                width: 160px;
            }

            table.twelve_five {
                width: 298px;
            }

            td.team_pad {
                height: 0;
            }

            table.foo {
                width: 100%;
            }

            td.ser_text.editable {
                text-align: center;
            }

            table.foo1 {
                width: 100%;
            }
        }

        @media only screen and (max-width: 568px) {
            /*-- w3layouts --*/
            table.container.top-header-left {
                width: 495px !important;
            }

            table.ban_info {
                width: 400px;
            }

            td.future {
                font-size: 1.8em !important;
            }


            table.container-middle {
                width: 449px;
            }

            table.twelve_two {
                width: 190px;
            }

            td.ser_one {
                height: 34px;
            }

            td.ser_two {
                height: 21px;
            }

            table.cir_left {
                width: 100%;
            }

            table.cir_left img {
                width: 30%;
                height: inherit;
            }

            table.twelve_four {
                width: 100%;
            }

            img.full.team_img {
                width: 45% !important;
                height: inherit;
            }

            table.twelve_five {
                width: 100%;
            }

            td.text_team {
                text-align: center;
            }

            td.twel_pad {
                height: 25px;
            }
        }

        /*-- agileits --*/
        @media only screen and (max-width: 480px) {
            .container {
                width: 290px !important;
            }

            .container-middle {
                width: 85% !important;
            }

            .mainContent {
                width: 240px !important;
            }

            .top-bottom-bg {
                width: 260px !important;
                height: auto !important;
            }

            table.logo {
                width: 33% !important;
            }

            table.container.top-header-left {
                width: 422px !important;
            }

            table.container-middle.navi-grid {
                width: 399px !important;
            }

            table.container-middle.nav-head {
                width: 350px !important;
            }

            table.twelve_one {
                width: 100%;
            }

            table.ser_left_one {
                width: 271px;
            }

            table.twelve_two {
                width: 100%;
            }

            td.pic_one {
                text-align: center !important;
            }

            td.pic_one img {
                width: 70%;
                height: inherit;
            }

            td.ser_pad {
                height: 32px;
            }

            td.future {
                font-size: 1.5em !important;
            }

            table.ban_info {
                width: 348px;
            }

            table.logo {
                width: 43% !important;
            }



            td.ban_pad {
                height: 24px;
            }

            table.logo {
                width: 54% !important;
            }

            td.ser_text {
                font-size: 13px !important;
            }
        }

        @media only screen and (max-width: 414px) {
            table.container.top-header-left {
                width: 397px !important;
            }

            table.container-middle.navi-grid {
                width: 372px !important;
            }

            table.container.top-header-left {
                width: 370px !important;
            }

            .container-middle {
                width: 95% !important;
            }

            table.ser_left_one {
                width: 255px;
            }
        }

        @media only screen and (max-width: 384px) {

            table.container-middle.navi-grid, table.container.top-header-left {
                width: 300px !important;
            }

            table.ban_info {
                width: 297px;
            }

            td.future {
                font-size: 1.3em !important;
            }

            .container-middle {
                width: 90% !important;
            }

            table.ban_info {
                width: 310px;
            }

            /*-- agileits --*/
            table.container-middle.nav-head {
                width: 340px !important;
            }

            table.ser_left_one {
                width: 216px;
            }

            table.mail_left, table.mail_right {
                width: 100%;
                height: 38px;
            }


            td.ser_one {
                height: 11px;
            }
        }

        @media only screen and (max-width: 320px) {
            td.wel_text {
                font-size: 1.9em !important;
            }

            img.full {
                width: 100%;
            }

            table.container.top-header-left {
                width: 284px !important;
            }

            table.container-middle.nav-head {
                width: 257px !important;
            }

            table.ban_info {
                width: 257px;
            }

            td.future {
                font-size: 1.2em !important;
            }

            td.ban_tex {
                height: 10px;
            }


            table.logo {
                width: 56% !important;
            }

            td.top_mar {
                height: 6px;
            }

            table.mail_left, table.mail_right {
                width: 100%;
                height: 29px;
            }

            table.ser_left_one {
                width: 181px;
            }

            table.ser_left_two {
                width: 73px;
            }

            td.pic_one img {
                width: 100%;
            }

            table.cir_left img {
                width: 37%;
            }

            td.thompson {
                font-size: 1.5em !important;
            }

            table.follow {
                width: 100%;
            }

            table.follow td {
                text-align: center !important;
            }

            table.logo {
                width: 69% !important;
            }
        }
    </style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table border="0" width="100%" cellpadding="0" cellspacing="0">

    <tr>
        <td width="100%" align="center" valign="top" bgcolor="062f3c" style="">
            <table>
                <tr>
                    <td class="top_mar" height="50"></td>
                </tr>
            </table>
            <!-- main content -->
            <table style="box-shadow:0px 0px 0px 0px #E0E0E0;" width="800" border="0" cellpadding="0" cellspacing="0"
                   align="center" class="container top-header-left">
                <tr bgcolor="2771ca">
                    <td>
                        <table border="0" width="650" align="center" cellpadding="0" cellspacing="0"
                               class="container-middle nav-head">
                            <tr>
                                <td height="15"></td>
                            </tr>
                            <tr>
                                <td>
                                    <table border="0" align="center" cellpadding="0" cellspacing="0"
                                           style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
                                           class="logo">
                                        <tbody>

                                        <tr>
                                            <td align="right">
                                                <a href="#" class="logo-text" style="text-decoration:none; color:#fff;">
                                                    <h1>ACTUALIZACIÓN DE CONTRASEÑA</h1>
                                                </a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td height="15"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr bgcolor="#3f5367">
                    <td>
                        <table border="0" width="650" align="center" cellpadding="0" cellspacing="0"
                               class="container-middle nav-head">
                            <tr>
                                <td height="15"></td>
                            </tr>
                            <tr>
                                <td>
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0"
                                           style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <table class="mail_left" align="left" border="0" cellpadding="0"
                                                       cellspacing="0" width="450">
                                                    <tbody>
                                                    <tr>
                                                        <td class="mail_gd" align="center"
                                                            style=" text-align: left; font-size:1.2em; font-family:Candara; color: #FFFFFF;">
                                                            <a href="mailto:contacto@intesystem.com.mx"
                                                               style="color:#fff;text-decoration:none">
                                                                <img src="http://soporte.intesystem.net/email/images/envelope.png"
                                                                     alt="" border="0"
                                                                     height="18" width="18">&nbsp; &nbsp;
                                                                contacto@intesystem.com.mx
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <table class="mail_right" align="right" border="0" cellpadding="0"
                                                       cellspacing="0" width="200">
                                                    <tbody>
                                                    <tr>
                                                        <td align="center"
                                                            style="font-size:14px;color:#f5f5f5;font-family:Arial,serif">
                                                            <img src="http://soporte.intesystem.net/email/images/1.png"
                                                                 alt="" border="0" height="16"
                                                                 width="16">&nbsp;
                                                            &nbsp;33 3629 3619 / 33 1816 3347 / 33 2005 2286
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td height="15"></td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr bgcolor="#ffffff">
                    <td>
                        <table border="0" width="650" align="center" cellpadding="0" cellspacing="0"
                               class="container-middle">
                            <tbody>
                            <!-- padding-top -->
                            <tr>
                                <td class="ser_pad" height="70"></td>

                            </tr>
                            <!-- //padding-top -->
                            <tr>
                                <td>
                                    <h1>Estimad@ ' . $fullname . '</h1>
                                    <p style="font-size: 22px; color: #414141;">
                                        Ahora ya puedes acceder al portal con tus nuevos accesos.
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="wel_text lcolor" align="center">
                                    <div align="left">
                                        <a href="http://soporte.intesystem.net/"
                                           target="_blank" class="btn-ini" style="color:#fff">
                                            Entrar
                                        </a>
                                    </div>
                                </td>

                            </tr>


                            <!-- padding-top -->
                            <tr>
                                <td class="ser_pad" height="70"></td>
                            </tr>
                            <!-- //padding-top -->
                            </tbody>
                        </table>
                    </td>
                </tr>


                <tr>
                    <td>
                        <table align="center" cellpadding="0" cellspacing="0" style="border-bottom:1px solid #f7f7f7">
                        </table>
                    </td>
                </tr>

                <tr bgcolor="2771ca">
                    <td>
                        <table border="0" width="650" align="center" cellpadding="0" cellspacing="0"
                               class="container-middle">
                            <tr>
                                <td height="10" style="font-size: 1px; line-height: 10px;">&nbsp;</td>
                            </tr>

                            <tr>
                                <td>

                                    <table class="foo" width="375" border="0" align="left" cellpadding="0"
                                           cellspacing="0">
                                        <tr>
                                            <td class="ser_text editable"
                                                style="font-family: Candara; font-size: 1em; color: #ffffff; line-height: 24px;">
                                                © 2019 INTESYSTEM . All Rights Reserved.</a>
                                            </td>
                                            <td style="float: right;">
                                                <a href="intesystem.com.mx" target="_blank"
                                                   style="font-family: Candara; font-size: 1em; color: #ffffff; float: right; line-height: 24px;">
                                                    intesystem.com.mx
                                                </a>
                                            </td>
                                        </tr>
                                    </table>

                                    <!-- SPACE -->
                                    <table width="1" height="10" border="0" cellpadding="0" cellspacing="0"
                                           align="left">
                                        <tr>
                                            <td height="10"
                                                style="font-size: 0;line-height: 0;border-collapse: collapse;padding-left: 24px;">
                                                &nbsp;
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- END SPACE -->

                                    <table class="foo1" width="170" border="0" align="right" cellpadding="0"
                                           cellspacing="0">
                                        <tr>
                                            <td class="ser_text editable"
                                                style="font-family: Candara; font-size: 1em; color: #ffffff; line-height: 24px;">


                                            </td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>

                            <tr>
                                <td height="10" style="font-size: 1px; line-height: 10px;">&nbsp;</td>
                            </tr>

                        </table>
                    </td>
                </tr>

            </table>
            <table>
                <tr>
                    <td class="top_mar" height="50"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
         ';
        $mail->IsHTML(true);
        $mail->AddAddress($email);
//        $mail->AddCC('freddarvizu@gmail.com');

        if (!$mail->send()) {
            echo 'Message was not sent.';
            echo 'Mailer error: ' . $mail->ErrorInfo;
            error_log("\n" . date('Y-m-d h:i:s A') . " ===> El mensaje no ha sido enviado. (" . $email . ")", 3, "../log/Email_ForgotPass.log");
        } else {
            error_log("\n" . date('Y-m-d h:i:s A') . " ===> El mensaje ha sido enviado. (" . $email . ")", 3, "../log/Email_ForgotPass.log");
        }


    }

    /* -----------------------Notify Customers - Notices   ------------------------------- */
    public function sendMail_notices($notify, $fullname, $email, $title, $error, $description, $solution)
    {


        if (!empty($error)) {
            $error = "
                      <tr>
                               <td>
                                    <h3>ERROR</h3>
                                   <p style='font-size: 22px; color: #414141;'>
                                       '.$error.'
                                   </p>
                               </td>
                      </tr>
        ";
        } else {
            $error = "";
        }


        if (!empty($description)) {

            $description = "
                           <tr>
                                <td>
                                    <h3>DESCRIPCIÓN</h3>
                                    <p style='font-size: 22px; color: #414141;'>
                                        '.$description.'
                                    </p>
                                </td>
                            </tr>
            ";
        } else {
            $description = "";
        }


        if (!empty($solution)) {
            $solution = "
                 <tr>
                                <td>
                                    <h3>SOLUCIÓN</h3>
                                    <p style='font-size: 22px; color: #414141;'>
                                        '.$solution.'
                                    </p>
                                </td>
                            </tr>
                ";
        } else {
            $solution = "";
        }

        $mail = new PHPMailer(true);
        $mail->CharSet = "UTF-8";
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = 'login';
        $mail->Username = 'no-reply@intesystem.net';
        $mail->Password = 'UN!cyHcInv3o';
        $mail->SMTPSecure = 'ssl';
        $mail->Host = 'mail.intesystem.net';
        $mail->Port = 465;
        $mail->SetFrom('no-reply@intesystem.net', 'INTESYSTEM');
        $mail->Subject = '¡IMPORTANTE! [' . $title . ']';
        $mail->Body = '
        
        <!DOCTYPE html>
<html>
<head>
    <title>Sistema de Tickets | INTESYSTEM</title>
    <link href=http://soporte.intesystem.net/favicon.ico rel="shortcut icon">
    <!-- Custom Theme files -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="keywords" content=""/>
    <!-- //Custom Theme files -->
    <!-- Responsive Styles and Valid Styles -->
    <style type="text/css">

        body {
            width: 100%;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        html {
            width: 100%;
        }

        table {
            font-size: 14px;
            border: 0;
        }

        .btn-ini {
            -moz-box-shadow: inset 0px 1px 0px 0px #ffffff;
            -webkit-box-shadow: inset 0px 1px 0px 0px #ffffff;
            box-shadow: inset 0px 1px 0px 0px #ffffff;
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #2770ca), color-stop(1, #3f5367));
            background: -moz-linear-gradient(top, #2770ca 5%, #3f5367 100%);
            background: -webkit-linear-gradient(top, #2770ca 5%, #3f5367 100%);
            background: -o-linear-gradient(top, #2770ca 5%, #3f5367 100%);
            background: -ms-linear-gradient(top, #2770ca 5%, #3f5367 100%);
            background: linear-gradient(to bottom, #2770ca 5%, #3f5367 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=\' #2770ca\', endColorstr=\' #3f5367\', GradientType=0);
            background-color: #2770ca;
            -moz-border-radius: 6px;
            -webkit-border-radius: 6px;
            border-radius: 6px;
            border: 1px solid #ffffff;
            display: inline-block;
            cursor: pointer;
            color: #ffffff;
            font-family: Arial;
            font-size: 15px;
            font-weight: bold;
            padding: 10px 48px;
            text-decoration: none;
            text-shadow: 0px 1px 0px #ffffff;
        }

        .btn-ini:hover {
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #3f5367), color-stop(1, #2770ca));
            background: -moz-linear-gradient(top, #3f5367 5%, #2770ca 100%);
            background: -webkit-linear-gradient(top, #3f5367 5%, #2770ca 100%);
            background: -o-linear-gradient(top, #3f5367 5%, #2770ca 100%);
            background: -ms-linear-gradient(top, #3f5367 5%, #2770ca 100%);
            background: linear-gradient(to bottom, #3f5367 5%, #2770ca 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=\' #3f5367\', endColorstr=\' #2770ca\', GradientType=0);
            background-color: #3f5367;
        }

        .btn-ini:active {
            position: relative;
            top: 1px;
        }

        .lcolor {
            font-size: 1.1em;
            padding: 5px 20px;
            border-left-width: 10px;
            border-left-style: solid;
            border-left-color: orange;
        }

        /* ----------- responsivity ----------- */
        @media only screen and (max-width: 800px) {
            table.container.top-header-left {
                width: 726px;
            }
        }

        @media only screen and (max-width: 736px) {
            table.container.top-header-left {
                width: 684px;
            }
        }

        @media only screen and (max-width: 667px) {
            table.container.top-header-left {
                width: 600px;
            }

            table.container-middle {
                width: 565px;
            }

            a.logo-text img {
                width: 100%;
                height: inherit;
            }

            table.logo {
                width: 40%;
            }

            table.mail_left {
                width: 200px;
            }

            td.mail_gd, td.mail_gd a {
                text-align: center !important;
            }

            td.ban_pad {
                height: 48px;
            }

            td.future {
                font-size: 2em !important;
            }

            td.ban_tex {
                height: 18px;
            }


            td.ser_pad {
                height: 50px;
            }

            td.wel_text {
                font-size: 2.1em !important;
            }

            td.ser_text {
                line-height: 2em !important;
                font-size: 1em !important;
            }

            table.twelve_one {
                width: 316px;
            }

            table.twelve_two {
                width: 229px;
            }

            td.pic_one img {
                width: 100%;
                height: inherit;
            }

            table.ser_left_two {
                width: 100px;
            }

            table.ser_left_one {
                width: 200px;
            }

            img.full {
                width: 100%;
            }

            table.twelve_three {
                width: 272px;
            }

            td.ser_text2 {
                font-size: 1.5em !important;
            }

            table.cir_left {
                width: 276px;
            }

            table.twelve_four {
                width: 200px;
            }

            table.twelve_five {
                width: 337px;
            }

            td.ser_one {
                height: 45px;
            }

            table.foo {
                width: 370px;
            }
        }

        @media only screen and (max-width: 640px) {
            td.ser_one {
                height: 60px;
            }

            .top-bottom-bg {
                width: 420px !important;
                height: auto !important;
            }

            table.container-middle.navi-grid {
                width: 360px !important;
            }

            table.logo {
                width: 45%;
            }
        }

        @media only screen and (max-width: 600px) {
            table.container.top-header-left {
                width: 535px;
            }

            table.container-middle {
                width: 485px;
            }


            table.ser_left_one {
                width: 151px;
            }

            table.ser_left_two {
                width: 86px;
            }

            table.twelve_one {
                width: 239px;
            }

            table.twelve_two {
                width: 221px;
            }

            table.twelve_three {
                width: 100%;
            }

            img.full {
                width: inherit;
            }

            table.cir_left {
                width: 230px;
            }

            table.cir_left img {
                width: 54%;
                height: inherit;
            }

            img.full.team_img {
                width: 100% !important;
                height: inherit;
            }

            table.twelve_four {
                width: 160px;
            }

            table.twelve_five {
                width: 298px;
            }

            td.team_pad {
                height: 0;
            }

            table.foo {
                width: 100%;
            }

            td.ser_text.editable {
                text-align: center;
            }

            table.foo1 {
                width: 100%;
            }
        }

        @media only screen and (max-width: 568px) {
            /*-- w3layouts --*/
            table.container.top-header-left {
                width: 495px !important;
            }

            table.ban_info {
                width: 400px;
            }

            td.future {
                font-size: 1.8em !important;
            }


            table.container-middle {
                width: 449px;
            }

            table.twelve_two {
                width: 190px;
            }

            td.ser_one {
                height: 34px;
            }

            td.ser_two {
                height: 21px;
            }

            table.cir_left {
                width: 100%;
            }

            table.cir_left img {
                width: 30%;
                height: inherit;
            }

            table.twelve_four {
                width: 100%;
            }

            img.full.team_img {
                width: 45% !important;
                height: inherit;
            }

            table.twelve_five {
                width: 100%;
            }

            td.text_team {
                text-align: center;
            }

            td.twel_pad {
                height: 25px;
            }
        }

        /*-- agileits --*/
        @media only screen and (max-width: 480px) {
            .container {
                width: 290px !important;
            }

            .container-middle {
                width: 85% !important;
            }

            .mainContent {
                width: 240px !important;
            }

            .top-bottom-bg {
                width: 260px !important;
                height: auto !important;
            }

            table.logo {
                width: 33% !important;
            }

            table.container.top-header-left {
                width: 422px !important;
            }

            table.container-middle.navi-grid {
                width: 399px !important;
            }

            table.container-middle.nav-head {
                width: 350px !important;
            }

            table.twelve_one {
                width: 100%;
            }

            table.ser_left_one {
                width: 271px;
            }

            table.twelve_two {
                width: 100%;
            }

            td.pic_one {
                text-align: center !important;
            }

            td.pic_one img {
                width: 70%;
                height: inherit;
            }

            td.ser_pad {
                height: 32px;
            }

            td.future {
                font-size: 1.5em !important;
            }

            table.ban_info {
                width: 348px;
            }

            table.logo {
                width: 43% !important;
            }


            td.ban_pad {
                height: 24px;
            }

            table.logo {
                width: 54% !important;
            }

            td.ser_text {
                font-size: 13px !important;
            }
        }

        @media only screen and (max-width: 414px) {
            table.container.top-header-left {
                width: 397px !important;
            }

            table.container-middle.navi-grid {
                width: 372px !important;
            }

            table.container.top-header-left {
                width: 370px !important;
            }

            .container-middle {
                width: 95% !important;
            }

            table.ser_left_one {
                width: 255px;
            }
        }

        @media only screen and (max-width: 384px) {

            table.container-middle.navi-grid, table.container.top-header-left {
                width: 300px !important;
            }

            table.ban_info {
                width: 297px;
            }

            td.future {
                font-size: 1.3em !important;
            }

            .container-middle {
                width: 90% !important;
            }

            table.ban_info {
                width: 310px;
            }

            /*-- agileits --*/
            table.container-middle.nav-head {
                width: 340px !important;
            }

            table.ser_left_one {
                width: 216px;
            }

            table.mail_left, table.mail_right {
                width: 100%;
                height: 38px;
            }


            td.ser_one {
                height: 11px;
            }
        }

        @media only screen and (max-width: 320px) {
            td.wel_text {
                font-size: 1.9em !important;
            }

            img.full {
                width: 100%;
            }

            table.container.top-header-left {
                width: 284px !important;
            }

            table.container-middle.nav-head {
                width: 257px !important;
            }

            table.ban_info {
                width: 257px;
            }

            td.future {
                font-size: 1.2em !important;
            }

            td.ban_tex {
                height: 10px;
            }


            table.logo {
                width: 56% !important;
            }

            td.top_mar {
                height: 6px;
            }

            table.mail_left, table.mail_right {
                width: 100%;
                height: 29px;
            }

            table.ser_left_one {
                width: 181px;
            }

            table.ser_left_two {
                width: 73px;
            }

            td.pic_one img {
                width: 100%;
            }

            table.cir_left img {
                width: 37%;
            }

            td.thompson {
                font-size: 1.5em !important;
            }

            table.follow {
                width: 100%;
            }

            table.follow td {
                text-align: center !important;
            }

            table.logo {
                width: 69% !important;
            }
        }
    </style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table border="0" width="100%" cellpadding="0" cellspacing="0">

    <tr>
        <td width="100%" align="center" valign="top" bgcolor="062f3c" style="">
            <table>
                <tr>
                    <td class="top_mar" height="50"></td>
                </tr>
            </table>
            <!-- main content -->
            <table style="box-shadow:0px 0px 0px 0px #E0E0E0;" width="800" border="0" cellpadding="0" cellspacing="0"
                   align="center" class="container top-header-left">
                <tr bgcolor="2771ca">
                    <td>
                        <table border="0" width="650" align="center" cellpadding="0" cellspacing="0"
                               class="container-middle nav-head">
                            <tr>
                                <td height="15"></td>
                            </tr>
                            <tr>
                                <td>
                                    <table border="0" align="center" cellpadding="0" cellspacing="0"
                                           style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
                                           class="logo">
                                        <tbody>

                                        <tr>
                                            <td align="right">
                                                <a href="#" class="logo-text" style="text-decoration:none; color:#fff;">
                                                    <h1>' . $title . '</h1>
                                                </a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td height="15"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr bgcolor="#3f5367">
                    <td>
                        <table border="0" width="650" align="center" cellpadding="0" cellspacing="0"
                               class="container-middle nav-head">
                            <tr>
                                <td height="15"></td>
                            </tr>
                            <tr>
                                <td>
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0"
                                           style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <table class="mail_left" align="left" border="0" cellpadding="0"
                                                       cellspacing="0" width="450">
                                                    <tbody>
                                                    <tr>
                                                        <td class="mail_gd" align="center"
                                                            style=" text-align: left; font-size:1.2em; font-family:Candara; color: #FFFFFF;">
                                                            <a href="mailto:contacto@intesystem.com.mx"
                                                               style="color:#fff;text-decoration:none">
                                                                <img src="http://soporte.intesystem.net/email/images/envelope.png"
                                                                     alt="" border="0"
                                                                     height="18" width="18">&nbsp; &nbsp;
                                                                contacto@intesystem.com.mx
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <table class="mail_right" align="right" border="0" cellpadding="0"
                                                       cellspacing="0" width="200">
                                                    <tbody>
                                                    <tr>
                                                        <td align="center"
                                                            style="font-size:14px;color:#f5f5f5;font-family:Arial,serif">
                                                            <img src="http://soporte.intesystem.net/email/images/1.png"
                                                                 alt="" border="0" height="16"
                                                                 width="16">&nbsp;
                                                            &nbsp;33 3629 3619 / 33 1816 3347 / 33 2005 2286
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td height="15"></td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr bgcolor="#ffffff">
                    <td>
                        <table border="0" width="650" align="center" cellpadding="0" cellspacing="0"
                               class="container-middle">
                            <tbody>
                            <!-- padding-top -->
                            <tr>
                                <td class="ser_pad" height="70"></td>

                            </tr>
                            <!-- //padding-top -->
                            <tr>
                                <td>
                                    <h1>Estimad@ ' . $fullname . '</h1>
                                    <p>
                                        ' . $notify . '
                                    </p>

                                </td>
                            </tr>
                          
                       
                                        ' . $error . '
                         
                  
                                        ' . $description . '
                              
                 
                                        ' . $solution . '
                          
                            <!--<tr>-->
                            <!--<td class="wel_text lcolor" align="center">-->
                            <!--<div align="left">-->
                            <!--<a href="http://soporte.intesystem.net/"-->
                            <!--target="_blank" class="btn-ini" style="color:#fff">-->
                            <!--Entrar-->
                            <!--</a>-->
                            <!--</div>-->
                            <!--</td>-->

                            <!--</tr>-->


                            <!-- padding-top -->
                            <tr>
                                <td class="ser_pad" height="70"></td>
                            </tr>
                            <!-- //padding-top -->
                            </tbody>
                        </table>
                    </td>
                </tr>


                <tr>
                    <td>
                        <table align="center" cellpadding="0" cellspacing="0" style="border-bottom:1px solid #f7f7f7">
                        </table>
                    </td>
                </tr>

                <tr bgcolor="2771ca">
                    <td>
                        <table border="0" width="650" align="center" cellpadding="0" cellspacing="0"
                               class="container-middle">
                            <tr>
                                <td height="10" style="font-size: 1px; line-height: 10px;">&nbsp;</td>
                            </tr>

                            <tr>
                                <td>

                                    <table class="foo" width="375" border="0" align="left" cellpadding="0"
                                           cellspacing="0">
                                        <tr>
                                            <td class="ser_text editable"
                                                style="font-family: Candara; font-size: 1em; color: #ffffff; line-height: 24px;">
                                                © 2019 INTESYSTEM . All Rights Reserved.</a>
                                            </td>
                                            <td style="float: right;">
                                                <a href="intesystem.com.mx" target="_blank"
                                                   style="font-family: Candara; font-size: 1em; color: #ffffff; float: right; line-height: 24px;">
                                                    intesystem.com.mx
                                                </a>
                                            </td>
                                        </tr>
                                    </table>

                                    <!-- SPACE -->
                                    <table width="1" height="10" border="0" cellpadding="0" cellspacing="0"
                                           align="left">
                                        <tr>
                                            <td height="10"
                                                style="font-size: 0;line-height: 0;border-collapse: collapse;padding-left: 24px;">
                                                &nbsp;
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- END SPACE -->

                                    <table class="foo1" width="170" border="0" align="right" cellpadding="0"
                                           cellspacing="0">
                                        <tr>
                                            <td class="ser_text editable"
                                                style="font-family: Candara; font-size: 1em; color: #ffffff; line-height: 24px;">


                                            </td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>

                            <tr>
                                <td height="10" style="font-size: 1px; line-height: 10px;">&nbsp;</td>
                            </tr>

                        </table>
                    </td>
                </tr>

            </table>
            <table>
                <tr>
                    <td class="top_mar" height="50"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
        
         ';
        $mail->IsHTML(true);
        $mail->AddAddress($email);
//        $mail->AddCC('freddarvizu@gmail.com');

        if (!$mail->send()) {
            echo 'Message was not sent.';
            echo 'Mailer error: ' . $mail->ErrorInfo;
            error_log("\n" . date('Y-m-d h:i:s A') . " ===> El mensaje no ha sido enviado. (" . $email . ")", 3, "../log/Email_Notices.log");
        } else {
            error_log("\n" . date('Y-m-d h:i:s A') . " ===> El mensaje ha sido enviado. (" . $email . ")", 3, "../log/Email_Notices.log");
        }


    }

}
