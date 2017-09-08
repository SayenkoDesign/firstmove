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
            <option value="Acton Elementary School">Acton Elementary School</option>
            <option value="Adams Elementary (Seattle Public Schools)">Adams Elementary (Seattle Public Schools)</option>
            <option value="Adams Elementary (Spokane Public Schools)">Adams Elementary (Spokane Public Schools)</option>
            <option value="Aldridge Elementary">Aldridge Elementary</option>
            <option value="Alex Haley Elementary Academy">Alex Haley Elementary Academy</option>
            <option value="Alfred Nobel Elementary">Alfred Nobel Elementary</option>
            <option value="Amelia Earhart  School">Amelia Earhart  School</option>
            <option value="Ariel Community Academy">Ariel Community Academy</option>
            <option value="Ashburn Community School">Ashburn Community School</option>
            <option value="Audubon Elementary">Audubon Elementary</option>
            <option value="Bailey Elementary">Bailey Elementary</option>
            <option value="Barry Elementary">Barry Elementary</option>
            <option value="Bass Elementary">Bass Elementary</option>
            <option value="Beach Elementary">Beach Elementary</option>
            <option value="Beasley Academic Center">Beasley Academic Center</option>
            <option value="Beethoven Elementary">Beethoven Elementary</option>
            <option value="Bemiss Elementary">Bemiss Elementary</option>
            <option value="Bennett Elementary">Bennett Elementary</option>
            <option value="Beulah Shoesmith Elementary">Beulah Shoesmith Elementary</option>
            <option value="Borger ISD">Borger ISD</option>
            <option value="Bradwell Elementary">Bradwell Elementary</option>
            <option value="Brawner Intermediate School">Brawner Intermediate School</option>
            <option value="Bret Harte School">Bret Harte School</option>
            <option value="Briarcrest Elementary School">Briarcrest Elementary School</option>
            <option value="Brunner Elementary School">Brunner Elementary School</option>
            <option value="Burnett Elementary">Burnett Elementary</option>
            <option value="Burnham Math & Science Academy">Burnham Math & Science Academy</option>
            <option value="Carnegie Elementary">Carnegie Elementary</option>
            <option value="Carroll Elementary">Carroll Elementary</option>
            <option value="Carver Elementary (Chicago Public Schools)">Carver Elementary (Chicago Public Schools)</option>
            <option value="Carver Elementary (Garland ISD)">Carver Elementary (Garland ISD)</option>
            <option value="Casals School of Excellence">Casals School of Excellence</option>
            <option value="Cedar Wood Elementary">Cedar Wood Elementary</option>
            <option value="Challenger Elementary">Challenger Elementary</option>
            <option value="Chapman Hill Elementary">Chapman Hill Elementary</option>
            <option value="Chestnut Elementary">Chestnut Elementary</option>
            <option value="Chicago Academy Elementary School">Chicago Academy Elementary School</option>
            <option value="Coleman Place Elementary">Coleman Place Elementary</option>
            <option value="Coles Elementary School (NJ)">Coles Elementary School (NJ)</option>
            <option value="Coles Elementary School (NJ)">Coles Elementary School (NJ)</option>
            <option value="Collegiate Preparatory">Collegiate Preparatory</option>
            <option value="Conant Elementary School">Conant Elementary School</option>
            <option value="Cooper Elementary">Cooper Elementary</option>
            <option value="Council Elementary">Council Elementary</option>
            <option value="Crescent Heights Elementary">Crescent Heights Elementary</option>
            <option value="Curtis School of Excellence">Curtis School of Excellence</option>
            <option value="Dearborn Park Elementary">Dearborn Park Elementary</option>
            <option value="Deneen School of Excellence">Deneen School of Excellence</option>
            <option value="Dewey School of Excellence">Dewey School of Excellence</option>
            <option value="Dodge Renaissance Academy">Dodge Renaissance Academy</option>
            <option value="Donnelly Elementary">Donnelly Elementary</option>
            <option value="Dore Elementary">Dore Elementary</option>
            <option value="Downing Elementary">Downing Elementary</option>
            <option value="Dunlap School">Dunlap School</option>
            <option value="Eagle County Charter Academy">Eagle County Charter Academy</option>
            <option value="Earle STEM Academy">Earle STEM Academy</option>
            <option value="Eastover Elementary">Eastover Elementary</option>
            <option value="ECOLE Kenwood K-8 French Immersion">ECOLE Kenwood K-8 French Immersion</option>
            <option value="Edison Elementary">Edison Elementary</option>
            <option value="Eisenhower Academy">Eisenhower Academy</option>
            <option value="Eliot Elementary School">Eliot Elementary School</option>
            <option value="Ellington Elementary School">Ellington Elementary School</option>
            <option value="Emerson Elementary">Emerson Elementary</option>
            <option value="Emma Roberson Elementary">Emma Roberson Elementary</option>
            <option value="Emmett Till Math Science Academy">Emmett Till Math Science Academy</option>
            <option value="Evergreen School">Evergreen School</option>
            <option value="Fairmount Park Elementary">Fairmount Park Elementary</option>
            <option value="Farnsworth Aerospace Lower">Farnsworth Aerospace Lower</option>
            <option value="Forest North Elementary">Forest North Elementary</option>
            <option value="Franklin Elementary (Spokane Public Schools)">Franklin Elementary (Spokane Public Schools)</option>
            <option value="Franklin Elementary (Tacoma Public Schools)">Franklin Elementary (Tacoma Public Schools)</option>
            <option value="Frazier Elem">Frazier Elem</option>
            <option value="Frazier International Magnet">Frazier International Magnet</option>
            <option value="Frederic Chopin Elementary School">Frederic Chopin Elementary School</option>
            <option value="Fuller School of Excellence">Fuller School of Excellence</option>
            <option value="Gallistel Elementary">Gallistel Elementary</option>
            <option value="Gardens">Gardens</option>
            <option value="Genoa Elementary">Genoa Elementary</option>
            <option value="George F. Cassell Elementary School">George F. Cassell Elementary School</option>
            <option value="Golden Acres">Golden Acres</option>
            <option value="Gordon Elementary">Gordon Elementary</option>
            <option value="Gregory Elementary">Gregory Elementary</option>
            <option value="Grimes Fleming Elementary">Grimes Fleming Elementary</option>
            <option value="Hancock Elementary">Hancock Elementary</option>
            <option value="Harvard School of Excellence">Harvard School of Excellence</option>
            <option value="Helen Stafford Elementary">Helen Stafford Elementary</option>
            <option value="Henderson Elementary">Henderson Elementary</option>
            <option value="Henry Clay Elementary School">Henry Clay Elementary School</option>
            <option value="Herfurth Elementary">Herfurth Elementary</option>
            <option value="Hillside Academy">Hillside Academy</option>
            <option value="Holmes Elementary">Holmes Elementary</option>
            <option value="Houston Elementary">Houston Elementary</option>
            <option value="Howe School of Excellence">Howe School of Excellence</option>
            <option value="Hughes C Elementary">Hughes C Elementary</option>
            <option value="Hughes L Elementary School">Hughes L Elementary School</option>
            <option value="Hutchison Beach Elementary">Hutchison Beach Elementary</option>
            <option value="Independence Elementary">Independence Elementary</option>
            <option value="Indian Trail">Indian Trail</option>
            <option value="Jackie Robinson Elementary">Jackie Robinson Elementary</option>
            <option value="Jahn School">Jahn School</option>
            <option value="James Shields Elementary School">James Shields Elementary School</option>
            <option value="James Ward Elementary">James Ward Elementary</option>
            <option value="Jensen Elementary">Jensen Elementary</option>
            <option value="Jessup Elementary">Jessup Elementary</option>
            <option value="JJ Hill Montessori Magnet School">JJ Hill Montessori Magnet School</option>
            <option value="John B. Drake Elementary">John B. Drake Elementary</option>
            <option value="John Muir Elementary">John Muir Elementary</option>
            <option value="John Pershing Magnet">John Pershing Magnet</option>
            <option value="Jones Elementary">Jones Elementary</option>
            <option value="Kalapya Elementary">Kalapya Elementary</option>
            <option value="Keller Regional Gifted Center">Keller Regional Gifted Center</option>
            <option value="Kozminski Elementary">Kozminski Elementary</option>
            <option value="Kruse Elementary">Kruse Elementary</option>
            <option value="Langford Elementary">Langford Elementary</option>
            <option value="LaSalle II Magnet Elementary School">LaSalle II Magnet Elementary School</option>
            <option value="Laura Bush">Laura Bush</option>
            <option value="Laurel Hill Primary School">Laurel Hill Primary School</option>
            <option value="Lenart Elementary">Lenart Elementary</option>
            <option value="Lewis School of Excellence">Lewis School of Excellence</option>
            <option value="LF Smith Elementary">LF Smith Elementary</option>
            <option value="Lillian Elementary">Lillian Elementary</option>
            <option value="Lincoln Elementary School">Lincoln Elementary School</option>
            <option value="Linwood Elementary">Linwood Elementary</option>
            <option value="Lister Elementary">Lister Elementary</option>
            <option value="Logan Elementary">Logan Elementary</option>
            <option value="Lone Pine Elementary">Lone Pine Elementary</option>
            <option value="Lone Star Elementary">Lone Star Elementary</option>
            <option value="Lowell School">Lowell School</option>
            <option value="Luna Elementary">Luna Elementary</option>
            <option value="Madison Elementary">Madison Elementary</option>
            <option value="Mae Smythe Elementary">Mae Smythe Elementary</option>
            <option value="Mambrino School">Mambrino School</option>
            <option value="Manitou Park Elementary School">Manitou Park Elementary School</option>
            <option value="Mann Elementary School">Mann Elementary School</option>
            <option value="Marcus Garvey Elementary School">Marcus Garvey Elementary School</option>
            <option value="Mariano Azuela Elementary School">Mariano Azuela Elementary School</option>
            <option value="Markham Elementary">Markham Elementary</option>
            <option value="Marquette Elementary School">Marquette Elementary School</option>
            <option value="Matthys Elementary">Matthys Elementary</option>
            <option value="McCarver Elementary">McCarver Elementary</option>
            <option value="McDonald Elementary">McDonald Elementary</option>
            <option value="McMasters Elementary">McMasters Elementary</option>
            <option value="McNair Elementary">McNair Elementary</option>
            <option value="Meador Elem">Meador Elem</option>
            <option value="Meeting Street Academy Spartanburg">Meeting Street Academy Spartanburg</option>
            <option value="Milwaukee Parkside School for Arts">Milwaukee Parkside School for Arts</option>
            <option value="Montclair Elementary School">Montclair Elementary School</option>
            <option value="Montessori of Englewood">Montessori of Englewood</option>
            <option value="Moore">Moore</option>
            <option value="Moos Elementary">Moos Elementary</option>
            <option value="Morales Elementary">Morales Elementary</option>
            <option value="Morton School of Excellence">Morton School of Excellence</option>
            <option value="Murray Elementary">Murray Elementary</option>
            <option value="Nate Mack Elementary">Nate Mack Elementary</option>
            <option value="National Teachers Academy">National Teachers Academy</option>
            <option value="Nettie Baccus Elementary">Nettie Baccus Elementary</option>
            <option value="Northlake Elementary">Northlake Elementary</option>
            <option value="Oak Woods School">Oak Woods School</option>
            <option value="Odyssey Elementary School">Odyssey Elementary School</option>
            <option value="Park Glen Elementary">Park Glen Elementary</option>
            <option value="Parks Elementary">Parks Elementary</option>
            <option value="Philip D. Armour Elementary">Philip D. Armour Elementary</option>
            <option value="Piccolo School of Excellence">Piccolo School of Excellence</option>
            <option value="Poe Elementary Classical School">Poe Elementary Classical School</option>
            <option value="Point Defiance Elementary School">Point Defiance Elementary School</option>
            <option value="Pomeroy Elementary">Pomeroy Elementary</option>
            <option value="Prieto Elementary">Prieto Elementary</option>
            <option value="Pulaski Elementary-Middle">Pulaski Elementary-Middle</option>
            <option value="Pulaski International">Pulaski International</option>
            <option value="Ray Elementary">Ray Elementary</option>
            <option value="Reavis Elementary">Reavis Elementary</option>
            <option value="Richey Elemetnary">Richey Elemetnary</option>
            <option value="Ridgeview Elementary (Keller ISD)">Ridgeview Elementary (Keller ISD)</option>
            <option value="Ridgeview Elementary School (Spokane Public Schools)">Ridgeview Elementary School (Spokane Public Schools)</option>
            <option value="Riverside Elementary">Riverside Elementary</option>
            <option value="Robert Nathaniel Dett Elementary">Robert Nathaniel Dett Elementary</option>
            <option value="Salem Heights Elementary">Salem Heights Elementary</option>
            <option value="Santo Elementary">Santo Elementary</option>
            <option value="School One">School One</option>
            <option value="Shady Grove Elementary">Shady Grove Elementary</option>
            <option value="Sharpstein Elementary">Sharpstein Elementary</option>
            <option value="Sheridan Elementary School">Sheridan Elementary School</option>
            <option value="Sherman Elementary (Tacoma Public Schools)">Sherman Elementary (Tacoma Public Schools)</option>
            <option value="Sherman Elementary School (Chicago Public Schools)">Sherman Elementary School (Chicago Public Schools)</option>
            <option value="Sherwood Elementary">Sherwood Elementary</option>
            <option value="Shoop Academy">Shoop Academy</option>
            <option value="South Belt Elementary">South Belt Elementary</option>
            <option value="Southeast Area Elementary">Southeast Area Elementary</option>
            <option value="Southside Elementary">Southside Elementary</option>
            <option value="Spencer Technology Academy">Spencer Technology Academy</option>
            <option value="St Anthony Park Elementary">St Anthony Park Elementary</option>
            <option value="Stagg School of Excellence">Stagg School of Excellence</option>
            <option value="Stevens Elementary (Seattle Public Schools)">Stevens Elementary (Seattle Public Schools)</option>
            <option value="Stevens Elementary School (Spokane Public Schools)">Stevens Elementary School (Spokane Public Schools)</option>
            <option value="Stevenson Elementary">Stevenson Elementary</option>
            <option value="Sullivan's Island Elementary">Sullivan's Island Elementary</option>
            <option value="Talcott Fine Arts">Talcott Fine Arts</option>
            <option value="Taylor Elementary School">Taylor Elementary School</option>
            <option value="Telpochcalli Elementary School">Telpochcalli Elementary School</option>
            <option value="Thomas Edison Charter School">Thomas Edison Charter School</option>
            <option value="Turner Elem">Turner Elem</option>
            <option value="Vernon School">Vernon School</option>
            <option value="Walnut Glen Academy">Walnut Glen Academy</option>
            <option value="Washington Elementary">Washington Elementary</option>
            <option value="Waters Elementary">Waters Elementary</option>
            <option value="Way Elementary">Way Elementary</option>
            <option value="Wedgwood Elementary">Wedgwood Elementary</option>
            <option value="Wellstone Elementary">Wellstone Elementary</option>
            <option value="Wentworth Elementary">Wentworth Elementary</option>
            <option value="West Park Elementary">West Park Elementary</option>
            <option value="West Seattle Elementary">West Seattle Elementary</option>
            <option value="Westside Academy">Westside Academy</option>
            <option value="Westview Elementary">Westview Elementary</option>
            <option value="Whitman Elementary School">Whitman Elementary School</option>
            <option value="William J. McGinn School">William J. McGinn School</option>
            <option value="Williams Elementary">Williams Elementary</option>
            <option value="Woodlawn Community School">Woodlawn Community School</option>
            <option value="Young Elementary (Pasadena ISD)">Young Elementary (Pasadena ISD)</option>
            <option value="Young Elementary (Chicago Public Schools)">Young Elementary (Chicago Public Schools)</option>
            <option value="Zarrow Elementary">Zarrow Elementary</option>
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