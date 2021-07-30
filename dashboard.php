<?php
$page = "dashboard";
include "includes/messages.php";
include "includes/head.php";
include "config/presence.php";
include "includes/sidebar.php";


$role = $global->getRol($user_id);


if ($role['id'] == 4 OR $role['id'] == 5) {
    include "includes/dashboard/customer.php";
} else if ($role['id'] == 3) {
    include "includes/dashboard/developer.php";
} else if ($role['id'] == 2) {
    include "includes/dashboard/support.php";
} else if ($role['id'] == 1) {
    include "includes/dashboard/management.php";
} else {
    echo "<div class='container'><h1>Error de conexón</h1></div>";

}



include "includes/footer.php";

?>
<script type="text/javascript" src="js/ticket.js"></script>
<script type="text/javascript">
    Highcharts.chart('graph', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Mis Tickets'
        },
        subtitle: {
            text: 'Relación de tickets Mensual'
        },
        xAxis: {
            categories: [
                <?php
                foreach ($StatusData as $s) {
                    echo "' Estatus de Tickets',";
                }
                ?>
            ],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'N° Tickets'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y} </b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [
            <?php

            foreach ($TicketStatus as $t) {
                foreach ($StatusData as $s) {
                    echo "{";
                    echo "name: '" . $s['name'] . "',";
                    echo "data:[" . $t[$s['name']] . "]";
                    echo "},";
                }
            }
            ?>
        ]
    });


</script>
</body>
</html>