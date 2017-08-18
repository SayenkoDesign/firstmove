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
    $data['contact'] = gravity_form(get_field('contact', 'option'), false,false,false,null,true,1,false);
    $data['contact_heading'] = get_field('contact_heading', 'option');
    $data['contact_content'] = get_field('contact_content', 'option');
    $data['twitter'] = get_field('twitter', 'option');
    $data['facebook'] = get_field('facebook', 'option');
    $data['instagram'] = get_field('instagram', 'option');

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

add_shortcode('button', function($atts, $content = null){
    $atts = shortcode_atts([
        'type' => '',
        'color' => 'secondary',
        'size' => '',
        'url' => '',
        'target' => '_self',
    ], $atts);

    if($atts['type'] == 'fancybox') {
        return <<<HTML
<a href="javascript:;" class="fancybox {$atts['size']} {$atts['color']} button" data-src="{$atts['url']}" target="{$atts['target']}">$content</a>
HTML;
    } elseif($atts['type'] == 'url') {
        return <<<HTML
<a href="{$atts['url']}" class="{$atts['size']} {$atts['color']} button" target="{$atts['target']}">$content</a>
HTML;
    }
});

add_action("after_switch_theme", function(){
    if($admins = get_role( 'administrator')) {
        $admins->add_cap('edit_videos');
        $admins->add_cap('edit_videos');
        $admins->add_cap('edit_other_videos');
        $admins->add_cap('publish_videos');
        $admins->add_cap('read_video');
        $admins->add_cap('read_private_videos');
        $admins->add_cap('delete_video');
    }

   if($educator = get_role('educator')) {
       $educator->add_cap('read_video');
   } else {
        add_role(
            'basic_contributor',
            __( 'Basic Contributor' ),
            [
               'read'         => true,
               'read_video'   => true,
            ]
        );
   }
});