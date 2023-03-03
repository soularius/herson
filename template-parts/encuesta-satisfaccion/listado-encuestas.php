<?php
    require_once('page-custom/pool_list_table.php');
?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Estadísticas de Encuestas por Orden</h1>
    </div>
<?php
    $poolList = new Pool_List_Table();
    $poolList->prepare_items();
?>
    <form method="GET">
        <input type="hidden" name="page" value="<?= esc_attr($_REQUEST['page']) ?>" />
        <?php $poolList->search_box('search', 'search_id'); ?>
    </form>
<?php
    $poolList->display();
?>