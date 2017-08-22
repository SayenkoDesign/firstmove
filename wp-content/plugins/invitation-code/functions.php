<?php
/*
   Plugin Name: Invitation Code
   Description: Allow users to register with an invitation code
   Version: 1.0
   Author: Sayenko Design
   Author URI: http://sayenkodesign.com
   License: GPL2
   */

if(!function_exists('acf_add_options_page')) {
    trigger_error("ACF plugin is required for this theme.");
}

// add sha256 script
// @TODO restrict to the edit page hook only
add_action('admin_enqueue_scripts', function ($hook) {
    wp_enqueue_script('sha256', plugin_dir_url(__FILE__) . '/sha256.js');
});

// add generate code button
add_action('acf/render_field', function($field) {
    if($field['key'] == "invitation_code") {
        ?>
        <p><a id="code_generate" style="cursor: pointer">Generate Random Code</a></p>
        <script>
            Date.now = function() { return new Date().getTime(); }
            jQuery("#code_generate").on("click", function(){
                sha256(Date.now()).then(function(digest) {
                    jQuery('#acf-invitation_code').val(digest.toUpperCase())
                });
            });
        </script>
        <?php
    }
}, 10, 1 );

// register post type
add_action( 'init', function () {
    $args = array(
        'public' => true,
        'public' => false,
        'show_ui' => true,
        'label'  => 'Invitation Codes',
        'menu_position' => 70,
        'menu_icon' => 'dashicons-admin-network',
        'supports' => ['title']
    );
    register_post_type( 'invitation_code', $args );
});

// add fields to post type
$invitation_post_gruop = 'group_invitation_codes';
acf_add_local_field_group(array (
    'key' => $invitation_post_gruop,
    'title' => 'Options',
    'fields' => array (
    ),
    'location' => array (
        array (
            array (
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'invitation_code',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
));
acf_add_local_field([
        'key' => 'invitation_code',
        'label' => 'Invitation code',
        'name' => 'invitation_code',
        'type' => 'text',
        'instructions' => 'Unique Invitation Code',
        'required' => 1,
        'conditional_logic' => 0,
        'wrapper' => array (
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'readonly' => 0,
        'parent' => $invitation_post_gruop,
]);
acf_add_local_field([
    'key' => 'invitation_limit',
    'label' => 'Limit',
    'name' => 'invitation_limit',
    'type' => 'number',
    'instructions' => 'Limit how many times code can be used. Leave blank for no limit',
    'required' => 0,
    'conditional_logic' => 0,
    'wrapper' => array (
        'width' => 50,
        'class' => '',
        'id' => '',
    ),
    'readonly' => 0,
    'parent' => $invitation_post_gruop,
]);
acf_add_local_field([
    'key' => 'invitation_limit_used',
    'label' => 'Usage',
    'name' => 'invitation_limit_used',
    'type' => 'number',
    'instructions' => 'How many times the code has been used',
    'required' => 0,
    'conditional_logic' => 0,
    'wrapper' => array (
        'width' => 50,
        'class' => '',
        'id' => '',
    ),
    'readonly' => 1,
    'parent' => $invitation_post_gruop,
]);
acf_add_local_field([
    'key' => 'invitation_start',
    'label' => 'Invitation Start Date',
    'name' => 'invitation_start',
    'type' => 'date_picker',
    'display_format' => 'm/d/Y',
    'instructions' => 'Code will not work until this date',
    'required' => 0,
    'conditional_logic' => 0,
    'wrapper' => array (
        'width' => 50,
        'class' => '',
        'id' => '',
    ),
    'maxlength' => '',
    'readonly' => 0,
    'parent' => $invitation_post_gruop,
]);
acf_add_local_field([
    'key' => 'invitation_end',
    'label' => 'Invitation End Date',
    'name' => 'invitation_end',
    'type' => 'date_picker',
    'display_format' => 'm/d/Y',
    'instructions' => 'Code will not work after this date',
    'required' => 0,
    'conditional_logic' => 0,
    'wrapper' => array (
        'width' => 50,
        'class' => '',
        'id' => '',
    ),
    'default_value' => '',
    'readonly' => 0,
    'parent' => $invitation_post_gruop,
]);

// add to login page
add_action('register_form', function() {
?>
    <label><?php _e( 'Invitation Code'); ?>
        <br />
        <input name="invitation_code" tabindex="0" type="text" class="input" id="invitation_code" style="text-transform: uppercase" />
    </label>
<?php
});

// check registration code
add_filter( 'registration_errors', function ($errors, $sanitized_user_login, $user_email)
{
    if(!isset($_POST['invitation_code']) || !$_POST['invitation_code']) {
        $errors->add('invitation_error', __('<strong>ERROR</strong>: Invitation code is required.'));
        return $errors;
    } else {
        $code = $_POST['invitation_code'];
        $posts  = new WP_Query([
            'numberposts'	=> -1,
            'post_type'		=> 'invitation_code',
            'meta_query'	=> [
                'relation'		=> 'AND',
                [
                    'key'	 	=> 'invitation_code',
                    'value'	  	=> $code,
                    'compare' 	=> '=',
                ],
            ],
        ]);
        if(!$posts->have_posts()) {
            $errors->add('invitation_error', __('<strong>ERROR</strong>: Invitation code is not invalid.'));
        }
        while ($posts->have_posts()) {
            $posts->the_post();
            $limit = get_field("invitation_limit");
            $usage = get_field("invitation_limit_used");
            $start = get_field("invitation_start", false, false);
            $end = get_field("invitation_end", false, false);

            if($usage && $limit && $usage >= $limit) {
                $errors->add('invitation_error', __('<strong>ERROR</strong>: Invitation code limit reached.'));
            }

            $today = new DateTime('now');
            if($start) {
                $start_date = DateTime::createFromFormat('Ymd', $start);
                if($today->format('Ymd') < $start_date->format('Ymd')) {
                    $errors->add('invitation_error', __('<strong>ERROR</strong>: Invitation code not valid until '.$start_date->format('M jS Y')));
                }
            }


            if($end) {
                $end_date = DateTime::createFromFormat('Ymd', $end);
                if($today->format('Ymd') > $end->format('Ymd')) {
                    $errors->add('invitation_error', __('<strong>ERROR</strong>: Invitation code expired on '.$end_date->format('M jS Y')));
                }
            }
        }
    }

    return $errors;
}, 20, 3 );

// count usage, login user, disable email
add_action('user_register', function($user_id) {
    if(!isset($_POST['invitation_code']) || !$_POST['invitation_code']) {
        return;
    }

    $code = $_POST['invitation_code'];
    $posts  = new WP_Query([
        'numberposts'	=> -1,
        'post_type'		=> 'invitation_code',
        'meta_query'	=> [
            'relation'		=> 'AND',
            [
                'key'	 	=> 'invitation_code',
                'value'	  	=> $code,
                'compare' 	=> '=',
            ],
        ],
    ]);

    if(!$posts->have_posts()) {
        return;
    }

    $posts->the_post();
    $usage = (int) get_field('invitation_limit_used');
    update_field('invitation_limit_used', $usage+1);
}, 10, 1);