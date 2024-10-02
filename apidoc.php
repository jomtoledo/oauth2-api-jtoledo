# apidoc.php
<?php

require __DIR__.'/vendor/autoload.php';

use Crada\Apidoc\Builder;
use Crada\Apidoc\Exception;

$classes = array(
    'App\Http\Controllers\Api\AuthController',
    'App\Http\Controllers\Api\CustomerController',
);

//$output_dir  = __DIR__.'/apidocs';
$output_dir = __DIR__.'/public/apidocs';
$output_file = 'api.html'; // defaults to index.html

try {
    $builder = new Builder($classes, $output_dir, 'OAuth2 API App', $output_file);
    $builder->generate();
} catch (Exception $e) {
    echo 'There was an error generating the documentation: ', $e->getMessage();
}
