<?php

class Pool_List_Table extends WP_List_Table {

    public $args_order = array();
    public $base = 3;
    public $per_page = 10;
   

    function get_all_items($search = null)
    {
        $args = array(
            'numberposts' => -1,
            'meta_key'      => 'checkPoll',
            'meta_value'    => true,
            'meta_compare'  => '=',
        );
        
        $this->args_with_search($args, $search);        
        if(is_null($args)) {
            return 0;
        }
        $orders = $this->generate_list_order($args);
        if(is_array($orders)) {
            return count($orders);
        }
        else {
            return 1;
        }
    }

    function generate_data_columns($per_page, $page = 1, $search = null,  $orderby = 'id', $order = 'DESC')
    {
        $args = array(
            'orderby' => $orderby, //has no effect as its a meta field 
            'order' => $order,
            'limit' => $per_page,
            'paged' => $page,
            'meta_key'      => 'checkPoll',
            'meta_value'    => true,
            'meta_compare'  => '=',
        );

        $this->args_with_search($args, $search);        
        if(is_null($args)) {
            return null;
        }
        $orders = $this->generate_list_order($args);
        $this->generate_array($orders);
    }

    function generate_list_order($args)
    {
        if(is_array($args)) {
            return wc_get_orders( $args );
        } else {
            return wc_get_order( $args );
        }

    }

    function args_with_search(&$args, $search)
    {
        if(!is_null($search) && !empty($search)) 
        {
            if(is_numeric($search)) {
                $args = intval($search);
            }
            else {            
                $user_ids = (array) $this->get_users_id_by_search($search);
                if(!empty($user_ids)) {
                    $args['customer_id'] = $user_ids;
                }
                else {
                    $this->args_order = array();
                    $args = null;
                }
            }
        }
    }

    function get_users_id_by_search($search)
    {
        return get_users([
            //'role'       => 'customer',
            'number'     => - 1,
            'fields'     => 'ID',
            'meta_query' => [
                'relation' => 'OR',
                [
                    'key'     => 'billing_first_name',
                    'compare' => 'LIKE',
                    'value'   => $search
                ],
                [
                    'key'     => 'billing_last_name',
                    'compare' => 'LIKE',
                    'value'   => $search
                ]
            ],
        ]);
    }

    function generate_array($orders)
    {
        if(is_null($orders) || empty($orders)) return;
        if(!is_array($orders)) {
            $this->push_data($orders);
        }
        else {
            foreach( $orders as $order ){
                $this->push_data($order);
            }
        }
    }

    function push_data($order) 
    {
        $dat = array();
        $order_id = $order->get_id();
        $q1 = get_field("pregunta_1", $order->get_id());
        $q2 = get_field("pregunta_2", $order->get_id());
        $q3 = get_field("pregunta_3", $order->get_id());
        $q4 = get_field("pregunta_4", $order->get_id());        
        $prom = (intval($q1) + intval($q2) + intval($q3)) / $this->base;
        
        $dat['id_order'] = $order_id;
        $dat['client'] = $order->get_billing_first_name() ." ". $order->get_billing_last_name() ;
        $dat['q1'] = $q1;
        $dat['q2'] = $q2;
        $dat['q3'] = $q3;        
        $dat['q4'] = $q4;
        $dat['prom'] = $prom;
        array_push($this->args_order, $dat);
    }

    function get_columns ()
    { 
        $columns = array (
              'id_order' => 'ID Orden', 
              'client' => 'Cliente', 
              'q1' => 'Pregunta 1', 
              'q2' => 'Pregunta 2',
              'q3' => 'Pregunta 3',
              'q4' => 'Pregunta 4',
              'prom' => 'Promedio',
            ); 
      
        return $columns; 
    }

    function prepare_items ()
    {
        $columns = $this -> get_columns ();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array( $columns, $hidden, $sortable );
        $search = null;

        $current_page = $this->get_pagenum();
        if(isset($_REQUEST['s'])) {
            $search = $_REQUEST['s'];
            $this->generate_data_columns($this->per_page, $current_page, $search);
        } else {
            $this->generate_data_columns($this->per_page, $current_page);
        }
        $total_items = $this->get_all_items($search);

        $this->set_pagination_args( array(
            'total_items' => $total_items,
            'per_page'    => $this->per_page
        ));

        usort( $this->args_order, array( &$this, 'usort_reorder' ) );
        $this->items = $this->args_order;
    }

    function get_sortable_columns()
    {
        $sortable_columns = array(
          'id_order' => array( 'id_order',false ),
          'client' => array( 'client', false ),
          'q1' => array( 'q1', false ),
          'q2' => array( 'q2', false ),
          'q3' => array( 'q3', false ),          
          'prom'  => array( 'prom', false )
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
    
    function column_client( $item )
    {
        $user_id = $this->searchIdCustomerByOrder($item['id_order']);
        $actions = array(
                            'editar' => sprintf('<a href="user-edit.php?user_id=%s">Edit client</a>',$user_id ),
                        );
      
        return sprintf('%1$s %2$s', $item['client'], $this->row_actions( $actions ) );
    }

    function column_id_order( $item )
    {
        $actions = array(
                            'editar' => sprintf('<a href="post.php?post=%s&action=%s">Edit order</a>',$item['id_order'],'edit' ),
                        );
      
        return sprintf('%1$s %2$s', $item['id_order'], $this->row_actions( $actions ) );
    }

    function searchIdCustomerByOrder($id_order) 
    {
        $order = wc_get_order($id_order);
        return $order->get_user_id();
    }

    function column_default( $item, $column_name )
    {
        switch( $column_name ) { 
            case 'id_order':
                return $item[ $column_name ];
            case 'client':
                return $item[ $column_name ];
            case 'q1':
                return $item[ $column_name ];
            case 'q2':
                return $item[ $column_name ];
            case 'q3':
                return $item[ $column_name ];            
            case 'q4':
                return $item[ $column_name ];
            case 'prom':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ) ;
        }
    }
}