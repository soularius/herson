<?php

function sedesERP() {
        $point_access = get_field('pto_de_conexion', 'option');
        $path = "BisWs/api/Bis/GetAgencias";
        $credentials = "Credenciales=ecommerce:a79bc5ab04a5c165870f6ac9e061a0fcec0f560e7cd42c2c5210f3824f4ec30e";

        $endPoint = "{$point_access}/{$path}?{$credentials}";
        try {
                $curl = @curl_init();
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