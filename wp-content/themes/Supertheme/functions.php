<?php
require_once __DIR__.'/app/bootstrap.php';

add_filter('jpeg_quality', function(){
    return 100;
});

add_filter('timber/context', function($data) {
    $data['menu'] = new Timber\Menu('primary_menu');
    $data['footer_menu'] = new Timber\Menu('footer_menu');
    $data['header_image'] = new Timber\Image(get_field('header_image'));
    $data['signin'] = get_field('signin', 'option');
    $data['donate'] = get_field('donate', 'option');
    $data['phone'] = get_field('phone', 'option');
    $data['address'] = get_field('address', 'option');
    $data['subscribe'] = gravity_form(get_field('subscribe', 'option'), false,false,false,null,false,1,false);
    $data['contact'] = gravity_form(get_field('contact', 'option'), false,false,false,null,false,1,false);
    $data['twitter'] = get_field('twitter', 'option');
    $data['facebook'] = get_field('facebook', 'option');

    return $data;
});

add_action('wp_enqueue_scripts', function() {
    $SP = [];
    $SP['settings'] = [
        "analyticsID" => get_field("google_analytics_id", "option"),
        "universalAnalytics" => true,
    ];
    wp_localize_script( 'app', 'SP', $SP);
});

add_shortcode('lead', function($atts, $content = null){

    return "<p class='lead'>$content</p>";
});