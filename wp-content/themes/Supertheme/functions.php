<?php
require_once __DIR__.'/app/bootstrap.php';

if(function_exists('acf_add_local_field_group')) {
    $parser = new \Symfony\Component\Yaml\Parser();
    $fields = $parser->parse(file_get_contents(__DIR__ . '/app/acf/header.yml'));
    acf_add_local_field_group($fields);
}

add_filter('timber/context', function($data){
    // logos
    $data['menu'] = new Timber\Menu('primary_menu');

    return $data;
});

add_action('wp_enqueue_scripts', function(){
    $SP = [];
    $SP['settings'] = [
        "analyticsID" => get_field("google_analytics_id", "option"),
        "universalAnalytics" => true,
    ];
    wp_localize_script( 'app', 'SP', $SP);
});