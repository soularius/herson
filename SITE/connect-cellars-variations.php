<?php

function getProductByIdSellersSITE($id_cellars) {
    $point_access = get_site_url();
    $path = "wp-json/erp/v1/attributes/cellars/{$id_cellars}";

    $endPoint = "{$point_access}/{$path}";

    try {
            $username = "usuario.prueba@g.com";
            $password = "9yhf lN2a TFsu P7W7 3cb3 hIPK";
            $curl = @curl_init();
            @curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $password);
            @curl_setopt($curl, CURLOPT_URL, $endPoint);
            @curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
            @curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            @curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            @curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
            @curl_setopt($curl, CURLOPT_TIMEOUT, 0);
            @curl_setopt($curl, CURLOPT_ENCODING, '');
            @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            @curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            
            $response = @curl_exec($curl);
            $httpStatus = @curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $curlErrors = curl_error($curl);
            @curl_close($curl);
    } catch (Exception $e) {
        return ['response' => null, 'err' => $e->getMessage(), 'httpStatus' => 400];
    }
    return ['response' => json_decode($response), 'err' => $curlErrors, 'httpStatus' => $httpStatus];
}