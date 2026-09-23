<?php
ob_start();
echo "Bla blabla";
file_put_contents('pj/INVENTORY_LOG/yourpage.html', ob_get_contents())
?>