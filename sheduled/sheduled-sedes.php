<?php
    get_template_part('ERP/connect', 'sedes');
    get_template_part('SITE/connect', 'sedes');

    add_action('init', 'schedule_sedes_sync');

    function schedule_sedes_sync() {
        // Agendamiento del evento.
        $timestamp = strtotime(get_field('fecha_de_actualizacion_de_sedes', 'option'));
        $now = strtotime(date('Y-m-d H:i:s'));
        if ($timestamp < $now)
            wp_schedule_single_event( $timestamp, 'syncSedesByERP' );
    }
    
    add_action('syncSedesByERP', 'updateOrCreateSedesByERP', 10, 3);

    function updateOrCreateSedesByERP() {
        $sedesERP = sedesERP();
        $sedesSITE = sedesSITE();
        if ($sedesERP["httpStatus"] != 200){
            return;
        }

        $responseERP = $sedesERP["response"];
        $citysERP = array_column($responseERP, "Ciudad");

        $responseSITE = $sedesSITE["response"];
        $citysSITE = array_column($responseSITE, "name");
        

        foreach($citysERP as $k => $city) {
            $direction = is_null($responseERP[$k]->Direccion) ?
                            $responseERP[$k]->Ciudad :
                            $responseERP[$k]->Ciudad ." - ". $responseERP[$k]->Direccion;
            $dat = array(
                "name" => $responseERP[$k]->Ciudad,
                "slug" => removeAccents(strtolower($responseERP[$k]->Ciudad)),
                "description" => $responseERP[$k]->Descripcion,
                "id_bodega" => $responseERP[$k]->Id_Bodega,
                "direccion_de_la_agencia" => $direction,
                "numero_telefonico_de_la_agencia" => $responseERP[$k]->Telefono
            );
            $json = json_encode($dat);

            echo "<pre>";
            var_dump($citysERP);
            echo "</pre>";

            echo "<pre>";
            var_dump($citysSITE);
            echo "</pre>";

            $pos = array_search($city, $citysSITE);
            if ($pos === false) {
                // Create
                $res = createUpdateSedesSITE(null, $json, "POST");
            } else {
                // Update
                $res = createUpdateSedesSITE($responseSITE[$pos]->id, $json, "PUT");
            }
        }

        updateDataExecution();

    }

    function updateDataExecution() {
        $dataExe = get_field('fecha_de_actualizacion_de_sedes', 'option');
        $interval = get_field('cantidad_de_horas_sedes', 'option');
        $newDataExe = strtotime("+{$interval} hour", strtotime($dataExe));
        $formatData = date('Y-m-d H:i:s', $newDataExe);
        update_field( 'fecha_de_actualizacion_de_sedes', $formatData, 'option' );
    }


    function removeAccents($wordWork)
    {
        $accentsLetters = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿ';
        $noAccentsLetters = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyyby';
        $word = utf8_decode($wordWork);
        $word = strtr($word, utf8_decode($accentsLetters), $noAccentsLetters);
    
        return utf8_encode($word);
    }


/*    function unregister_updateOrCreateSedesByERP_event() {
        $timestamp = strtotime(get_field('fecha_de_actualizacion_de_sedes', 'option'));
        wp_unschedule_event( $timestamp, 'updateOrCreateSedesByERP' );
    }
     register_deactivation_hook( __FILE__, 'unregister_updateOrCreateSedesByERP_event' ); */
     