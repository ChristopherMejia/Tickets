<?php

session_start();
include "../config/config.php";

$role = $global->getRol($_SESSION['user_id']);
$maintenance = $configuration->maintenance($role['id']);
if (!$maintenance) {
    header("location: ../");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href=favicon.ico rel="shortcut icon">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="http://fonts.googleapis.com/css?family=Reenie+Beanie:regular" rel="stylesheet" type="text/css">
    <!-- Bootstrap -->
    <link href="css/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
        body {
            font-family: "Helvetica Neue", Roboto, Arial, "Droid Sans", sans-serif;
            overflow-x: hidden;
            overflow-y: hidden;

        }

        body {
            background: #f7f7f7;
        }

        .wrap {
            margin: 0 auto;
            width: 1000px;
        }

        .logo {
            padding: 13% 5%;
        }

        img {
            margin: 0 auto;
        }

        .logo h3 {
            text-align: center;
            font-weight: 700;

        }

        .logo h1 {
            font-size: 70px;
            color: #4682b4;
            text-align: center;
            margin-bottom: 1px;
            text-shadow: 2px 2px 1px white;
        }

        .logo p {
            color: #B1A18D;
            font-size: 20px;
            margin-top: 1px;
            text-align: center;
        }

        .logo p span {
            color: lightgreen;
        }

        .sub a {
            color: #4682b4;
            text-decoration: none;
            padding: 5px;
            font-size: 13px;
            font-family: arial, serif;
            font-weight: bold;
        }

        .footer {
            color: #4682b4;
            position: absolute;
            right: 10px;
            bottom: 10px;
        }

        .footer a {
            color: #fff;
        }

        @media (max-width: 1024px) {
            .logo h1 {
                font-size: 170px;
                margin-top: 140px
            }

            .wrap {
                width: 100%;
            }

            .footer {
                font-size: 14px;
                line-height: 30px;
            }
        }

        @media (max-width: 991px) {
            .logo h1 {
                font-size: 150px;
            }
        }

        @media (max-width: 768px) {
            body {
                display: -webkit-flex;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100vh;
                padding: 0;
                margin: 0;
            }

            .logo h1 {
                margin-top: 0px
            }

            .footer {
                right: 0;
                width: 100%;
                text-align: center;
            }
        }

        @media (max-width: 736px) {
            .logo h1 {
                font-size: 120px;
            }
        }

        @media (max-width: 600px) {
            .logo h1 {
                font-size: 100px;
            }
        }

        @media (max-width: 568px) {
            .logo p {
                font-size: 15px;
                margin-bottom: 5px;
                margin-top: 1px;
            }
        }

        @media (max-width: 480px) {
            .logo p {
                margin-bottom: 10px;
            }
        }

        @media (max-width: 384px) {
            .footer {
                font-size: 13px;
                line-height: 25px;
            }
        }

        @media (max-width: 320px) {
            .logo h1 {
                font-size: 90px;
            }

            .logo p {
                font-size: 14px;
                margin-top: 10px;
                margin-bottom: 15px;
            }

            .footer {
                right: 10px;
                left: 10px;
                width: 94%;
            }
        }
    </style>
</head>


<body>
<div class="wrap">
    <div class="logo">
        <h3>Intesystem SO</h3>
        <h1>REGRESAMOS PRONTO</h1>
        <p>Tareas de mantenimiento en progreso, nuestro sistema de tiket´s estará inactivo temporalmente.</p>
        <div class="sub">
            <p><a href="mailto:info@intesystem.com.mx"> info@intesystem.com.mx</a></p>
        </div>

    </div>
</div>

<div class="footer">
    &copy 2019. All Rights Reserved |Intesystem
</div>

</body>