<?php

function getProductByIdItemSITE_0($id_item) {
    $point_access = get_site_url();
    $path = "wp-json/erp/v1/product/{$id_item}";

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
    return ['response' => json_decode($response, JSON_UNESCAPED_UNICODE), 'err' => $curlErrors, 'httpStatus' => $httpStatus];
}

function createUpdateProductsSITE_0($id, $json, $type) {
    $point_access = get_site_url();
    $path = "wp-json/wc/v3/products/{$id}";
    $endPoint = "{$point_access}/{$path}";
    try {
            $username = "ck_f769616ad969ace9d5436c0c94372c9dbfe7095f";
            $password = "cs_2606455099bb39940576cd0214277e3e99b0f422";
            $curl = @curl_init();
            @curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $password);
            @curl_setopt($curl, CURLOPT_URL, $endPoint);
            @curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $type);
            @curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            @curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            @curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
            @curl_setopt($curl, CURLOPT_TIMEOUT, 0);
            @curl_setopt($curl, CURLOPT_ENCODING, '');
            @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            @curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            @curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
            
            $response = @curl_exec($curl);
            $httpStatus = @curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $curlErrors = curl_error($curl);
            @curl_close($curl);
    } catch (Exception $e) {
        return ['response' => null, 'err' => $e->getMessage(), 'httpStatus' => 400];
    }
    return ['response' => json_decode($response, JSON_UNESCAPED_UNICODE), 'err' => $curlErrors, 'httpStatus' => $httpStatus];

}