<?php

function read_file($file, $start, $part)
{
        // Crear una instancia de la clase InMemoryListener para manejar los datos del archivo
        $listener = new \JsonStreamingParser\Listener\InMemoryListener();
    
        $stream = fopen($file, 'r');
        try {
                $parser = new \JsonStreamingParser\Parser($stream, $listener);
                $parser->parse();
                $all_obj = $listener->getJson();
                $fragment = array_slice($all_obj, $start, $part);
                fclose($stream);
                return $fragment;
        } catch (Exception $e) {
        fclose($stream);
                throw $e;
        }
        return null;
}

function count_item_file($file)
{
        $listener = new \JsonStreamingParser\Listener\InMemoryListener();
        $stream = fopen($file, 'r');
        $parser = new \JsonStreamingParser\Parser($stream, $listener);
        $parser->parse();
        fclose($stream);
        return count($listener->getJson());
}

function format_json($file)
{
    $json = file_get_contents($file);
    $json = utf8_encode($json);
    $data = json_decode($json, true);
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($file, $json);
}

function write_log_error($msg)
{
    $logfile = __DIR__."/../ERP/tmp/log.json";

    $log = fopen($logfile, 'a');
    $msg = date('Y-m-d H:i:s') .
            " -> {$msg}:";
    fwrite($log, $msg . PHP_EOL);
    fclose($log);
}

function write_log_error_item($item, $retry)
{
    $logfile = __DIR__."/../ERP/tmp/log.json";
    $id_item = $item["Id_Item"];

    $log = fopen($logfile, 'a');
    $strRetry = $retry > 10 ? '0'.$retry : $retry;
    $msg = date('Y-m-d H:i:s') .
            " -> Erro al procesar el producto con ID #{$id_item} En el Reinteno #{$strRetry}:";
    fwrite($log, $msg . PHP_EOL);
    fwrite($log, json_encode($item, JSON_UNESCAPED_UNICODE) . PHP_EOL . PHP_EOL);
    fclose($log);
}

function write_log_process_item($item, $pos, $process)
{
        $logfile = __DIR__."/../ERP/tmp/log_process.json";
        $id_item = $item["Id_Item"];

        $log = fopen($logfile, 'a');

        $msg = "<br>------- SE HA: {$process} ----------<br>";
        fwrite($log, $msg . PHP_EOL);
        $msg = date('Y-m-d H:i:s') .
                " -> Procesado el producto con ID #{$id_item} En la posicion #{$pos}:";
        fwrite($log, $msg . PHP_EOL);
        fwrite($log, json_encode($item, JSON_UNESCAPED_UNICODE) . PHP_EOL . PHP_EOL);
        fclose($log);
}