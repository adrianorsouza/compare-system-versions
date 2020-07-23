<?php


require __DIR__ . '/vendor/autoload.php';


try {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Status: 200 OK');

    $data = file_get_contents(__DIR__ . '/_build/data.json');

    echo $data;

} catch (\Exception $e) {
    header('Content-Type: text/html');
    header('Status: 500 Internal Server Error');
    echo '{"error": "'.$e->getMessage().'"}';
}
