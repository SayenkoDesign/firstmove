<?php
require_once __DIR__.'/app/bootstrap.php';

add_filter('jpeg_quality', function(){
    return 100;
});

add_action( 'login_enqueue_scripts', function() {
    wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/web/stylesheets/admin.css' );
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

add_filter( 'login_headerurl', function ($url) {
    return get_home_url();
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
       $educator->add_cap('read_private_posts');
       $educator->add_cap('read_private_pages');
   } else {
        add_role(
            'educator',
            __( 'Educator' ),
            [
                'read'               => true,
                'read_video'         => true,
                'read_private_posts' => true,
                'read_private_pages' => true,
            ]
        );
   }
});

add_action ('admin_init', function () {
    global $wp_roles;
    if($educator = get_role('educator')) {
        $educator = get_role('educator');
        $educator->add_cap('read_private_posts');
    }
});

add_filter('login_redirect', function ($redirect_to, $request, $user) {
    //is there a user to check?
    if (isset($user->roles) && is_array($user->roles)) {
        //check for admins
        if ( in_array( 'educator', $user->roles ) ) {
            // redirect them to the default place
            return get_field('login_redirect', 'options');
        }
    }

    return $redirect_to;
}, 10, 3 );

// show private post in appearance menu
add_filter('nav_menu_meta_box_object', function($args) {
    if($args->name == 'page') {
        $args->_default_query['post_status'] = ['publish','private'];
    }

    return $args;
});

// remove menu bar links for educators
add_action('wp_before_admin_bar_render', function() {
    $user = get_userdata(get_current_user_id());
    if(!in_array('educator', $user->roles)){
        return;
    }
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('wp-logo');
    if(!is_admin()) {
        $wp_admin_bar->remove_menu('site-name');
    }
    $wp_admin_bar->remove_menu('search');
});

// remove dashboard link for educators
add_action('admin_menu', function () {
    $user = get_userdata(get_current_user_id());
    if(!in_array('educator', $user->roles)){
        return;
    }
    remove_menu_page("index.php");
});

// only allow educators access to profile page
add_action('admin_init', function (){
    global $pagenow;
    $user = get_userdata(get_current_user_id());
    if(!in_array('educator', $user->roles)){
        return;
    }
    if ($pagenow == "index.php" && !defined( 'DOING_AJAX' ) && !current_user_can('administrator') ){
        wp_redirect(site_url('/wp-admin/profile.php'));
        exit;
    }
});

// add school field
add_filter('user_contactmethods', function ($profile_fields) {
    $profile_fields['school'] = 'School';

    return $profile_fields;
});

//add first and last name to registration form
add_action('register_form', function() {
    ?>
    <label><?php _e( 'First Name'); ?>
        <br />
        <input name="first_name" type="text" class="input" id="first_name" />
    </label>
    <label><?php _e( 'Last Name'); ?>
        <br />
        <input name="last_name" type="text" class="input" id="last_name" />
    </label>
    <label><?php _e( 'School'); ?>
        <br />
        <select name="school" class="input" id="school">
            <option value="1">School 1</option>
            <option value="2">School 2</option>
            <option value="3">School 3</option>
        </select>
    </label>
    <?php
}, 9);


// check registration fields
add_filter( 'registration_errors', function ($errors, $sanitized_user_login, $user_email)
{
    if(!isset($_POST['first_name']) && !$_POST['first_name']) {
        $errors->add('registration_error', __('<strong>ERROR</strong>: First Name is required.'));
    }
    if(!isset($_POST['last_name']) && !$_POST['last_name']) {
        $errors->add('registration_error', __('<strong>ERROR</strong>: Last Name is required.'));
    }
    if(!isset($_POST['school']) && !$_POST['school']) {
        $errors->add('registration_error', __('<strong>ERROR</strong>: School is required.'));
    }

    return $errors;
}, 20, 3);

// save registration fields
add_action('user_register', function($user_id) {
    if(isset($_POST['first_name']) && $_POST['first_name']) {
        update_user_meta($user_id, 'first_name', $_POST['first_name']);
    }
    if(isset($_POST['last_name']) && $_POST['last_name']) {
        update_user_meta($user_id, 'last_name', $_POST['last_name']);
    }
    if(isset($_POST['school']) && $_POST['school']) {
        update_user_meta($user_id, 'school', $_POST['school']);
    }
});