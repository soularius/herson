<?php


require_once('page-custom/average_poll_filter_table.php');
require_once('page-custom/average_general_poll_filter_table.php');

?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Promedios de Encuestas General</h1>
    </div>
<?php

$averagePoolGral = new Average_General_Poll_Filter_Table();
$averagePoolGral->prepare_items();
$averagePoolGral->display();

?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Promedios de Encuestas por Sedes</h1>
    </div>
<?php

$averagePool = new Average_Poll_Filter_Table();
$averagePool->prepare_items();
$averagePool->display();

?>