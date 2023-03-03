<?php


class Average_Poll_Filter_Table extends WP_List_Table {

    public $args_sedes = array();
    public $base = 3;
    public $bodegas_qn = array();

    function get_columns ()
    { 
        $columns = array (
              'id_sede' => 'ID sede', 
              'sede' => 'Sede', 
              'q1' => 'Pregunta 1', 
              'q2' => 'Pregunta 2',
              'q3' => 'Pregunta 3',
              'q4' => 'Pregunta 4',
              'qty_poll' => 'Cant. encuestas',
              'prom' => 'Promedio',
            ); 
      
        return $columns; 
    }

    function column_default( $item, $column_name )
    {
        switch( $column_name ) { 
            case 'id_sede':
                return $item[ $column_name ];
            case 'sede':
                return $item[ $column_name ];
            case 'q1':
                return $item[ $column_name ];
            case 'q2':
                return $item[ $column_name ];
            case 'q3':
                return $item[ $column_name ];
            case 'q4':
                return $item[ $column_name ];            
            case 'qty_poll':
                return $item[ $column_name ];
            case 'prom':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ) ;
        }
    }

    
    function prepare_items ()
    {
        $columns = $this -> get_columns ();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array( $columns, $hidden, $sortable );
        $current_page = $this->get_pagenum();
        $this->generate_data_columns(-1, $current_page);

        usort( $this->args_sedes, array( &$this, 'usort_reorder' ) );
        $this->items = $this->args_sedes;
    }

    function get_sortable_columns()
    {
        $sortable_columns = array(
          'id_sede' => array('id_sede',false),
          'sede' => array('sede', false),
          'q1' => array('q1', false),
          'q2' => array('q2', false),
          'q3' => array('q3', false),          
          'prom'  => array('prom', false),
        );
        return $sortable_columns;
    }
    
    function usort_reorder( $a, $b )
    {
        $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'id';
        $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
        $result = strcmp( $a[$orderby], $b[$orderby] );
        return ( $order === 'asc' ) ? $result : -$result;
    }

    function generate_data_columns($per_page, $page = 1, $search = null,  $orderby = 'id', $order = 'DESC')
    {
        $bodegas = $this->getAllBodegaa();
        $this->init_array_with_bodegas($bodegas);
        $args = array(
            'orderby' => $orderby,
            'order' => $order,
            'limit' => $per_page,
            'paged' => $page,
            'meta_key'      => 'checkPoll',
            'meta_value'    => true,
            'meta_compare'  => '=',
        );

        if(is_null($args)) {
            return null;
        }
        $orders = $this->generate_list_order($args);
        $this->generate_array($orders, $bodegas);
    }

    function generate_list_order($args)
    {
        if(is_array($args)) {
            return wc_get_orders( $args );
        } else {
            return wc_get_order( $args );
        }

    }


    function generate_array($orders, $bodegas)
    {
        if(is_null($orders) || empty($orders)) return;
        if(!is_array($orders)) {
                $items_by_order = $orders->get_items();
                $bodega = $this->filter_product_by_order($items_by_order);
                if(!empty($bodega))
                {
                    $this->agroup_value_eq($bodega, $orders);
                }
        }
        else {
            foreach( $orders as $order ){
                $items_by_order = $order->get_items();
                $bodega = $this->filter_product_by_order($items_by_order);
                if(!empty($bodega))
                {
                    $this->agroup_value_eq($bodega, $order);
                }
            }
            $this->calculate_prom_qn();
            $this->push_data($bodegas);
        }
    }

    function filter_product_by_order($items_by_order)
    {
        $first_item = array_values($items_by_order)[0];
        $product = $first_item->get_product();
        if(!empty($product)) {
            $bodegas = $product->get_attribute('pa_bodegas');
            $bodegas = explode(",", $bodegas);
            return $bodegas;
        }
        return "";
    }

    function push_data($bodegas)
    {
        foreach($this->bodegas_qn as $key => $bodega) {
            $dat = array();
            $pos = array_search($key, array_column($bodegas, 'slug'));
            $obj_bodega = $bodegas[$pos];
            
            $q1 = $bodega['q1'];
            $q2 = $bodega['q2'];
            $q3 = $bodega['q3'];            
            $prom = (floatval($q1) + floatval($q2) + floatval($q3)) / $this->base;
            
            $id_sede = get_field('id_bodega', 'pa_bodegas_' . $obj_bodega->term_id);
            $dat['id_sede'] = $id_sede;
            $dat['sede'] = $obj_bodega->name;
            $dat['q1'] = $this->format_number_prom($q1);
            $dat['q2'] = $this->format_number_prom($q2);
            $dat['q3'] = $this->format_number_prom($q3);            
            $dat['q4'] = "N/A";
            $dat['qty_poll'] = intval($bodega['qty_orders']);
            $dat['prom'] = $this->format_number_prom($prom);
            array_push($this->args_sedes, $dat);
        }
    }

    function agroup_value_eq($bodegas, $order)
    {
        foreach ($bodegas as $bodega) {
            $name = $this->transfor_name_bodega($bodega);

            $q1 = get_field("pregunta_1", $order->get_id());
            $q2 = get_field("pregunta_2", $order->get_id());
            $q3 = get_field("pregunta_3", $order->get_id());            

            $this->bodegas_qn[$name]['q1'] = $this->bodegas_qn[$name]['q1'] + intval($q1);
            $this->bodegas_qn[$name]['q2'] = $this->bodegas_qn[$name]['q2'] + intval($q2);
            $this->bodegas_qn[$name]['q3'] = $this->bodegas_qn[$name]['q3'] + intval($q3);            
            $this->bodegas_qn[$name]['qty_orders']++;
        }
    }

    function calculate_prom_qn()
    {
        foreach ($this->bodegas_qn as $key => $bodega) {
            $this->bodegas_qn[$key]['q1'] = $bodega['qty_orders'] > 0 ? $bodega['q1'] / $bodega['qty_orders']: 0;
            $this->bodegas_qn[$key]['q2'] = $bodega['qty_orders'] > 0 ? $bodega['q2'] / $bodega['qty_orders']: 0;
            $this->bodegas_qn[$key]['q3'] = $bodega['qty_orders'] > 0 ? $bodega['q3'] / $bodega['qty_orders']: 0;            
        }
    }

    function format_number_prom($value)
    {
        return number_format(floatval($value), 2, ',', '.');
    }

    function getAllBodegaa()
    {
        $taxonomyName = "pa_bodegas";
        return get_terms( 
            $taxonomyName, 
            array( 
                'parent' => 0,
                'orderby' => 'slug',
                'hide_empty' => false,
                )
            );

    }

    function init_array_with_bodegas($bodegas)
    {
        $name_bodegas = array_column($bodegas, "name");
        foreach ($name_bodegas as $name) {
            $name = $this->transfor_name_bodega($name);
            $this->bodegas_qn[$name] = array();
            $this->bodegas_qn[$name]['qty_orders'] = 0;
        }
    }

    function transfor_name_bodega($name)
    {
        $name = $this->removeAccents($name);
        $name = $this->removeUnicodeCharacter($name);
        $name = strtolower($name);
        return trim($name);
    }
    
    function removeAccents($wordWork)
    {
        $accentsLetters = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿ';
        $noAccentsLetters = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyyby';
        $word = utf8_decode($wordWork);
        $word = strtr($word, utf8_decode($accentsLetters), $noAccentsLetters);

        return utf8_encode($word);
    }

    function removeUnicodeCharacter($word)
    {
        return preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $word);
    }
}

?>
