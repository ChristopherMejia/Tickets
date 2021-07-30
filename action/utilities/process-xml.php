<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 03/06/2019
 * Time: 12:53 PM
 */

include "../../config/config.php";//DB
$dir = $path."/public/xml";
$destination_path = $path.'/public/temp/';

foreach (scandir($dir, 1) as $f) {
    if ($f !== '.' and $f !== '..') {


        if (file_exists($dir . '/' . $f)) {

            $xml = simplexml_load_file($dir . '/' . $f);

        } else {
            exit('Error abriendo el archivo: ' . $f);

        }

        $ns = $xml->getNamespaces(true);
        $xml->registerXPathNamespace('c', $ns['cfdi']);
        $xml->registerXPathNamespace('t', $ns['tfd']);


        foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante) {

            $Version = $cfdiComprobante['Version'];
            $Serie = $cfdiComprobante['Serie'];
            $Folio = $cfdiComprobante['Folio'];


        }


        foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {

            $UUID = $tfd['UUID'];
            $FechaTimbrado = $tfd['FechaTimbrado'];

        }


        /* SQL */
        $sql = "INSERT INTO cfdi_uuid_cancel (Cuenta,Serie,Folio,UUID,Tipo,Version,Fecha,FechaTimbrado,Estatus)
                    VALUES ('','$Serie','$Folio','$UUID','','$Version',NOW(),'$FechaTimbrado','A')";


        $query_new_insert = mysqli_query($con, $sql);
        if ($query_new_insert) {

            echo '<div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    El UUID: <strong>' . $UUID . '</strong> fue guardado correctamente.
                </div>';
        } else {


            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>' . $UUID . '</strong> No se pudo guardar.
                </div>';
        }


        if (copy($dir . '/' . $f, $destination_path. '/' . $f)) {
            unlink($dir . '/' . $f);
        }

    }
}


$con->close();


?>
