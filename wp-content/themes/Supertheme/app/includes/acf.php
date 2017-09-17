<?php
// save acf as json
use Supertheme\ACFFieldGenerator;

add_filter('acf/settings/save_json', function ($path) use($container) {
    return $container->getParameterBag()->resolveValue($container->getParameter('wordpress.acf_path'));
});

// show/hide acf menus
add_filter('acf/settings/show_admin', function ($show) use($container) {
    return $container->getParameter('wordpress.acf_menu');
});

// check if acf function is defined before creating fields
if(!function_exists('acf_add_local_field_group')){
    return;
}

// create fields based on yml
$parser = new \Symfony\Component\Yaml\Parser();
$fields = $parser->parse(file_get_contents(__DIR__.'/../../app/acf/theme_options.yml'));
acf_add_local_field_group($fields);

$parser = new \Symfony\Component\Yaml\Parser();
$fields = $parser->parse(file_get_contents(__DIR__ . '/../acf/header.yml'));
acf_add_local_field_group($fields);

// define slider for reuse[
$acf_slider = [
    ACFFieldGenerator::tab('slider_tab', 'Slider', 'slider_tab'),
    ACFFieldGenerator::repeater('slider', 'Slider', 'slider', [
        ACFFieldGenerator::text('slide_title', 'Title', 'slide_title'),
        ACFFieldGenerator::image('slide_headshot', 'Title', 'slide_headshot','id', '', false),
        ACFFieldGenerator::wysiwyg(
            'slide_content',
            'Content',
            'slide_content',
            ACFFieldGenerator::WYSIWYG_TAB_ALL,
            ACFFieldGenerator::WYSIWYG_TOOLBAR_BASIC,
            false
        ),
        ACFFieldGenerator::text('slide_source_title', 'Source Title', 'slide_source_title', '', null, true, 50),
        ACFFieldGenerator::url('slide_source_url', 'Source URL', 'slide_source_url', '', null, true, 50),
        ACFFieldGenerator::image('slide_image', 'Background Image', 'slide_image', 'id'),
    ], 2),
];

// home page
acf_add_local_field_group([
    'key' => 'group_home',
    'title' => 'Home',
    'fields' => [
        ACFFieldGenerator::tab('home_works_tab', 'How it Works', 'home_works_tab'),
        ACFFieldGenerator::text('home_title', 'Title', 'home_title', '', null, true, 50),
        ACFFieldGenerator::image('home_image', 'Footer Image', 'home_image', 'id',  '', true, 50),
        ACFFieldGenerator::repeater('home_steps', 'Steps', 'home_steps', [
            ACFFieldGenerator::text('home_step_title', 'Title', 'home_step_title', '', null, true, 50),
            ACFFieldGenerator::url('home_step_url', 'URL', 'home_step_url', '', null, false, 50),
            ACFFieldGenerator::wysiwyg(
                'home_step_content',
                'Content',
                'home_step_content',
                ACFFieldGenerator::WYSIWYG_TAB_ALL,
                ACFFieldGenerator::WYSIWYG_TOOLBAR_BASIC,
                false,
                '',
                '',
                true,
                50
            ),
            ACFFieldGenerator::image('home_step_image', 'Image', 'home_step_image', 'id', '', true, 50),
        ]),
        $acf_slider,
    ],
    'location' => [
        [
            [
                'param' => 'page_template',
                'operator' => '==',
                'value' => 'page-templates/how-it-works.php',
            ],
        ],
    ],
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => ['the_content'],
    'active' => 1,
    'description' => '',
]);

// about page
acf_add_local_field_group([
    'key' => 'group_about',
    'title' => 'About',
    'fields' => [
        // callout
        ACFFieldGenerator::tab('about_callout_1_tab', 'Call Out', 'about_callout_1_tab'),
        ACFFieldGenerator::text('about_callout_1_title', 'Title', 'about_callout_1_title'),
        ACFFieldGenerator::wysiwyg(
            'about_callout_1_content',
            'Content',
            'about_callout_1_content',
            ACFFieldGenerator::WYSIWYG_TAB_ALL,
            ACFFieldGenerator::WYSIWYG_TOOLBAR_BASIC,
            false
        ),
        // members
        ACFFieldGenerator::tab('about_members_tab', 'Members', 'about_members_tab'),
        ACFFieldGenerator::text('about_members_title', 'Title', 'about_members_title'),
        ACFFieldGenerator::repeater('about_members', 'Members', 'about_members', [
            ACFFieldGenerator::image('about_member_headshot', 'Headshot', 'about_member_headshot', 'id'),
            ACFFieldGenerator::text('about_member_name', 'Name', 'about_member_name', '', null, true,50),
            ACFFieldGenerator::text('about_member_position', 'Position', 'about_member_position', '', null, true,50),
            ACFFieldGenerator::email('about_member_email', 'Email', 'about_member_email', '', null, false, 50),
            ACFFieldGenerator::url('about_member_linkedin', 'Linkedin', 'about_member_linkedin', '', null, false, 50),
            ACFFieldGenerator::wysiwyg(
                'about_member_bio',
                'Bio',
                'about_member_bio',
                ACFFieldGenerator::WYSIWYG_TAB_ALL,
                ACFFieldGenerator::WYSIWYG_TOOLBAR_BASIC,
                false
            ),
        ]),
        // call out
        ACFFieldGenerator::tab('about_callout_2_tab', 'Call Out', 'about_callout_2_tab'),
        ACFFieldGenerator::text('about_callout_2_title', 'Title', 'about_callout_2_title'),
        ACFFieldGenerator::wysiwyg(
            'about_callout_2_content',
            'Content',
            'about_callout_2_content',
            ACFFieldGenerator::WYSIWYG_TAB_ALL,
            ACFFieldGenerator::WYSIWYG_TOOLBAR_BASIC,
            false
        ),
        // checklist
        ACFFieldGenerator::tab('about_checklist_tab', 'Checklist', 'about_checklist_tab'),
        ACFFieldGenerator::text('about_checklist_left_title', 'Left Title', 'about_checklist_left_title', '', null, true, 50),
        ACFFieldGenerator::text('about_checklist_right_title', 'Right Title', 'about_checklist_right_title', '', null, true, 50),
        ACFFieldGenerator::repeater('about_checklist_left_checklist', 'Checklist', 'about_left_checklist', [
            ACFFieldGenerator::text('about_checklist_left_item', 'Item', 'about_checklist_left_item'),
            ], 1, null, true, 50
        ),
        ACFFieldGenerator::repeater('about_checklist_right_checklist', 'Checklist', 'about_right_checklist', [
            ACFFieldGenerator::text('about_checklist_right_item', 'Item', 'about_checklist_right_item'),
            ], 1, null, true, 50
        ),
        // board
        ACFFieldGenerator::tab('about_board_tab', 'Board', 'about_board_tab'),
        ACFFieldGenerator::text('about_board_members_title', 'Board Members Title', 'about_board_members_title'),
        ACFFieldGenerator::repeater('about_board_members', 'Board Members', 'about_board_members', [
            ACFFieldGenerator::text('about_board_members_name', 'Name', 'about_board_members_name', '&nbsp;', null, true, 50),
            ACFFieldGenerator::text('about_board_members_position', 'Position', 'about_board_members_position', 'use &lt;br /&gt; to force a line break', null, true, 50),
            ACFFieldGenerator::wysiwyg(
                'about_board_members_bio',
                'Lightbox Content',
                'about_board_members_bio',
                ACFFieldGenerator::WYSIWYG_TAB_ALL,
                ACFFieldGenerator::WYSIWYG_TOOLBAR_BASIC,
                false
            ),
        ]),
        ACFFieldGenerator::text('about_board_advisory_title', 'Board Advisory Title', 'about_board_advisory_title'),
        ACFFieldGenerator::repeater('about_board_advisory', 'Board Advisory', 'about_board_advisory', [
            ACFFieldGenerator::text('about_board_advisory_name', 'Name', 'about_board_advisory_name', '&nbsp;', null, true, 50),
            ACFFieldGenerator::text('about_board_advisory_position', 'Position', 'about_board_advisory_position', 'use &lt;br /&gt; to force a line break', null, true, 50),
            ACFFieldGenerator::wysiwyg(
                'about_board_advisory_bio',
                'Lightbox Content',
                'about_board_advisory_bio',
                ACFFieldGenerator::WYSIWYG_TAB_ALL,
                ACFFieldGenerator::WYSIWYG_TOOLBAR_BASIC,
                false
            ),
        ]),
    ],
    'location' => [
        [
            [
                'param' => 'page_template',
                'operator' => '==',
                'value' => 'page-templates/about.php',
            ],
        ],
    ],
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => ['the_content'],
    'active' => 1,
    'description' => '',
]);

// curriculum page
acf_add_local_field_group([
    'key' => 'group_curriculum',
    'title' => 'Curriculum',
    'fields' => [
        // callout
        ACFFieldGenerator::tab('curriculum_callout_tab', 'Call Out', 'curriculum_callout_tab'),
        ACFFieldGenerator::text('curriculum_callout_title', 'Title', 'curriculum_callout_title'),
        ACFFieldGenerator::wysiwyg(
            'curriculum_callout_content',
            'Content',
            'curriculum_callout_content',
            ACFFieldGenerator::WYSIWYG_TAB_ALL,
            ACFFieldGenerator::WYSIWYG_TOOLBAR_BASIC,
            false
        ),
        // images + content
        ACFFieldGenerator::tab('curriculum_alt_content_tab', 'Download', 'curriculum_alt_content_tab'),
        ACFFieldGenerator::repeater('curriculum_alt_content', 'Content', 'curriculum_alt_content', [
            ACFFieldGenerator::image('curriculum_alt_content_image', 'Image', 'curriculum_alt_content_image', 'id'),
            ACFFieldGenerator::text('curriculum_alt_content_title', 'Title', 'curriculum_alt_content_title'),
            ACFFieldGenerator::wysiwyg(
                'curriculum_alt_content_content',
                'Content',
                'curriculum_alt_content_content',
                ACFFieldGenerator::WYSIWYG_TAB_ALL,
                ACFFieldGenerator::WYSIWYG_TOOLBAR_BASIC,
                false
            ),
        ]),
        // slider
        ACFFieldGenerator::tab('curriculum_slider_tab', 'Fan Art', 'curriculum_slider_tab'),
        ACFFieldGenerator::text('curriculum_slider_title', 'Title', 'curriculum_slider_title'),
        ACFFieldGenerator::repeater('curriculum_slider', 'Content', 'curriculum_slider', [
            ACFFieldGenerator::text('curriculum_slide_title', 'Title', 'curriculum_slide_title', '', null, true, 50),
            ACFFieldGenerator::text('curriculum_slide_description', 'Description', 'curriculum_slide_description', '', null, true, 50),
            ACFFieldGenerator::image('curriculum_slide_image', 'Image', 'curriculum_slide_image', 'id'),
        ], 6),
        // accordion
        ACFFieldGenerator::tab('curriculum_accordions_tab', 'Accordions', 'curriculum_accordions_tab'),
        ACFFieldGenerator::text('curriculum_accordions_title', 'Title', 'curriculum_accordions_title'),
        ACFFieldGenerator::repeater('curriculum_accordions', 'Content', 'curriculum_accordions', [
            ACFFieldGenerator::text('curriculum_accordion_left_title', 'Title', 'curriculum_accordion_left_title', '', null, true, 50),
            ACFFieldGenerator::text('curriculum_accordion_right_title', 'Title', 'curriculum_accordion_right_title', '', null, true, 50),
            ACFFieldGenerator::wysiwyg(
                'curriculum_accordion_left_content',
                'Content',
                'curriculum_accordion_left_content',
                ACFFieldGenerator::WYSIWYG_TAB_ALL,
                ACFFieldGenerator::WYSIWYG_TOOLBAR_BASIC,
                false,
                '',
                null,
                true,
                50
            ),
            ACFFieldGenerator::wysiwyg(
                'curriculum_accordion_right_content',
                'Content',
                'curriculum_accordion_right_content',
                ACFFieldGenerator::WYSIWYG_TAB_ALL,
                ACFFieldGenerator::WYSIWYG_TOOLBAR_BASIC,
                false,
                '',
                null,
                true,
                50
            ),
        ], 2),
        $acf_slider,
    ],
    'location' => [
        [
            [
                'param' => 'page_template',
                'operator' => '==',
                'value' => 'page-templates/curriculum.php',
            ],
        ],
    ],
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => ['the_content'],
    'active' => 1,
    'description' => '',
]);

// Donors
acf_add_local_field_group([
    'key' => 'group_donors_stories',
    'title' => 'Donors',
    'fields' => [
        // callout
        ACFFieldGenerator::tab('donors_callout_1_tab', 'Call Out', 'donors_callout_1_tab'),
        ACFFieldGenerator::text('donors_callout_1_title', 'Title', 'donors_callout_1_title'),
        ACFFieldGenerator::wysiwyg(
            'donors_callout_1_content',
            'Content',
            'donors_callout_1_content',
            ACFFieldGenerator::WYSIWYG_TAB_ALL,
            ACFFieldGenerator::WYSIWYG_TOOLBAR_BASIC,
            false
        ),
        // images + content
        ACFFieldGenerator::tab('donors_alt_content_tab', 'Download', 'donors_alt_content_tab'),
        ACFFieldGenerator::repeater('donors_alt_content', 'Content', 'donors_alt_content', [
            ACFFieldGenerator::image('donors_alt_content_image', 'Image', 'donors_alt_content_image', 'id', '', true, 50),
            ACFFieldGenerator::text('donors_alt_content_title', 'Title', 'donors_alt_content_title', '', null, true, 50),
            ACFFieldGenerator::wysiwyg(
                'donors_alt_content_content',
                'Content',
                'donors_alt_content_content',
                ACFFieldGenerator::WYSIWYG_TAB_ALL,
                ACFFieldGenerator::WYSIWYG_TOOLBAR_BASIC,
                false
            ),
            ACFFieldGenerator::text('donors_button_text', 'Button Text', 'donors_button_text', '', null, false, 50),
            ACFFieldGenerator::file(
                'donors_button_file',
                'Button File',
                'donors_button_file',
                '',
                ACFFieldGenerator::FILE_RETURN_URL,
                null,
                false,
                50
            ),
        ]),
        // section header
        ACFFieldGenerator::tab('donors_section_tab', 'Section Header', 'donors_section_tab'),
        ACFFieldGenerator::image('donors_section_background_image', 'Background Image', 'donors_section_background_image', 'id'),
        ACFFieldGenerator::image('donors_section_image', 'Image', 'donors_section_image', 'id'),
        // callout
        ACFFieldGenerator::tab('donors_callout_2_tab', 'Call Out', 'donors_callout_2_tab'),
        ACFFieldGenerator::text('donors_callout_2_title', 'Title', 'donors_callout_2_title'),
        ACFFieldGenerator::wysiwyg(
            'donors_callout_2_content',
            'Content',
            'donors_callout_2_content',
            ACFFieldGenerator::WYSIWYG_TAB_ALL,
            ACFFieldGenerator::WYSIWYG_TOOLBAR_BASIC,
            false
        ),
        // platinum
        ACFFieldGenerator::tab('donors_platinum_tab', 'Platinum', 'donors_platinum_tab'),
        ACFFieldGenerator::text('donors_platinum_title', 'Title', 'donors_platinum_title', '', 'Giving Platinum Level', true, 33),
        ACFFieldGenerator::text('donors_platinum_subtext', 'Subtext', 'donors_platinum_subtext', '', null, true, 33),
        ACFFieldGenerator::image('donors_platinum_icon', 'Icon', 'donors_platinum_icon', 'id', '', true, 34),
        ACFFieldGenerator::repeater('donors_platinum_donors', 'Donors', 'donors_platinum_donors', [
            ACFFieldGenerator::image('donors_platinum_donor_image', 'Donor Logo', 'donors_platinum_donor_image', 'id', '', true, 33),
            ACFFieldGenerator::text('donors_platinum_donor_text', 'Text', 'donors_platinum_donor_text', '', null, false, 33),
            ACFFieldGenerator::url('donors_platinum_donor_url', 'URL', 'donors_platinum_donor_url', '', null, false, 34),
        ]),
        // gold
        ACFFieldGenerator::tab('donors_gold_tab', 'Gold', 'donors_gold_tab'),
        ACFFieldGenerator::text('donors_gold_title', 'Title', 'donors_gold_title', '', 'Giving Gold Level', true, 33),
        ACFFieldGenerator::text('donors_gold_subtext', 'Subtext', 'donors_gold_subtext', '', null, true, 33),
        ACFFieldGenerator::image('donors_gold_icon', 'Icon', 'donors_gold_icon', 'id', '', true, 34),
        ACFFieldGenerator::repeater('donors_gold_donors', 'Donors', 'donors_gold_donors', [
            ACFFieldGenerator::image('donors_gold_donor_image', 'Donor Logo', 'donors_gold_donor_image', 'id', '', true, 33),
            ACFFieldGenerator::text('donors_gold_donor_text', 'Text', 'donors_gold_donor_text', '', null, false, 33),
            ACFFieldGenerator::url('donors_gold_donor_url', 'URL', 'donors_gold_donor_url', '', null, false, 34),
        ]),
        // silver
        ACFFieldGenerator::tab('donors_silver_tab', 'Silver', 'donors_silver_tab'),
        ACFFieldGenerator::text('donors_silver_title', 'Title', 'donors_silver_title', '', 'Giving Silver Level', true, 33),
        ACFFieldGenerator::text('donors_silver_subtext', 'Subtext', 'donors_silver_subtext', '', null, true, 33),
        ACFFieldGenerator::image('donors_silver_icon', 'Icon', 'donors_silver_icon', 'id', '', true, 34),
        ACFFieldGenerator::repeater('donors_silver_donors', 'Donors', 'donors_silver_donors', [
            ACFFieldGenerator::text('donors_silver_donor_text', 'Text', 'donors_silver_donor_text', '', null, true, 50),
            ACFFieldGenerator::url('donors_silver_donor_url', 'URL', 'donors_silver_donor_url', '', null, false, 50),
        ]),
        //copper
        ACFFieldGenerator::tab('donors_copper_tab', 'Copper', 'donors_copper_tab'),
        ACFFieldGenerator::text('donors_copper_title', 'Title', 'donors_copper_title', '', 'Giving Copper Level', true, 33),
        ACFFieldGenerator::text('donors_copper_subtext', 'Subtext', 'donors_copper_subtext', '', null, true, 33),
        ACFFieldGenerator::image('donors_copper_icon', 'Icon', 'donors_copper_icon', 'id', '', true, 34),
        ACFFieldGenerator::repeater('donors_copper_donors', 'Donors', 'donors_copper_donors', [
            ACFFieldGenerator::text('donors_copper_donor_text', 'Text', 'donors_copper_donor_text', '', null, true, 50),
            ACFFieldGenerator::url('donors_silver_copper_url', 'URL', 'donors_copper_donor_url', '', null, false, 50),
        ]),
        // callout
        ACFFieldGenerator::tab('donors_callout_3_tab', 'Call Out', 'donors_callout_3_tab'),
        ACFFieldGenerator::text('donors_callout_3_title', 'Title', 'donors_callout_3_title'),
        ACFFieldGenerator::wysiwyg(
            'donors_callout_3_content',
            'Content',
            'donors_callout_3_content',
            ACFFieldGenerator::WYSIWYG_TAB_ALL,
            ACFFieldGenerator::WYSIWYG_TOOLBAR_BASIC,
            false
        ),
        // supporters
        ACFFieldGenerator::tab('donors_supporters_tab', 'Supporters', 'donors_supporters_tab'),
        ACFFieldGenerator::text('donors_supporters_title', 'Title', 'donors_supporters_title'),
        ACFFieldGenerator::repeater('donors_supporters_donors', 'Supporters', 'donors_supporters_donors', [
            ACFFieldGenerator::image('donors_supporters_donor', 'Supporter', 'donors_supporters_donor', 'id'),
        ]),
        // callout
        ACFFieldGenerator::tab('donors_callout_4_tab', 'Call Out', 'donors_callout_4_tab'),
        ACFFieldGenerator::text('donors_callout_4_title', 'Title', 'donors_callout_4_title'),
        ACFFieldGenerator::wysiwyg(
            'donors_callout_4_content',
            'Content',
            'donors_callout_4_content',
            ACFFieldGenerator::WYSIWYG_TAB_ALL,
            ACFFieldGenerator::WYSIWYG_TOOLBAR_BASIC,
            false
        ),
        // donate
        ACFFieldGenerator::tab('donors_donate_tab', 'Donate', 'donors_donate_tab'),
        ACFFieldGenerator::text('donors_donate_button_text', 'Button Text', 'donors_donate_button_text', '', null, false, 50),
        ACFFieldGenerator::url('donors_donate_button_url', 'Button URL', 'donors_donate_button_url', '', null, false, 50),
    ],
    'location' => [
        [
            [
                'param' => 'page_template',
                'operator' => '==',
                'value' => 'page-templates/donors.php',
            ],
        ],
    ],
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => ['the_content'],
    'active' => 1,
    'description' => '',
]);

// case studies
acf_add_local_field_group([
    'key' => 'success_stories',
    'title' => 'Success Stories',
    'fields' => [
        // callout
        ACFFieldGenerator::tab('success_callout_tab', 'Call Out', 'success_callout_tab'),
        ACFFieldGenerator::text('success_callout_title', 'Title', 'success_callout_title'),
        ACFFieldGenerator::wysiwyg(
            'success_callout_content',
            'Content',
            'success_callout_content',
            ACFFieldGenerator::WYSIWYG_TAB_ALL,
            ACFFieldGenerator::WYSIWYG_TOOLBAR_BASIC,
            false
        ),
        // content
        ACFFieldGenerator::tab('success_content', 'Content', 'succes_content'),
        ACFFieldGenerator::text('success_left_title', 'Left Title', 'success_left_title', '', null, true, 50),
        ACFFieldGenerator::text('success_right_title', 'Right Title', 'success_right_title', '', null, true, 50),
        ACFFieldGenerator::wysiwyg(
            'Success_left_content',
            'Left Content',
            'success_left_content',
            ACFFieldGenerator::WYSIWYG_TAB_ALL,
            ACFFieldGenerator::WYSIWYG_TOOLBAR_BASIC,
            false,
            '',
            null,
            true,
            50
        ),
        ACFFieldGenerator::wysiwyg(
            'Success_right_content',
            'Right Content',
            'success_right_content',
            ACFFieldGenerator::WYSIWYG_TAB_ALL,
            ACFFieldGenerator::WYSIWYG_TOOLBAR_BASIC,
            false,
            '',
            null,
            true,
            50
        ),
    ],
    'location' => [
        [
            [
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'success_stories',
            ],
        ],
    ],
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => ['the_content'],
    'active' => 1,
    'description' => '',
]);

// cvideos template
acf_add_local_field_group([
    'key' => 'video_playlist',
    'title' => 'Video playlist',
    'fields' => [
        ACFFieldGenerator::number('year', 'Year', 'year', '', null, true, 100),
    ],
    'location' => [
        [
            [
                'param' => 'page_template',
                'operator' => '==',
                'value' => 'page-templates/videos.php',
            ],
        ],
    ],
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => ['the_content'],
    'active' => 1,
    'description' => '',
]);

// videos post type
acf_add_local_field_group([
    'key' => 'group_videos',
    'title' => 'Video Settings',
    'fields' => [
        ACFFieldGenerator::number('video_year', 'Year', 'video_year', '', null, true, 33),
        ACFFieldGenerator::number('video_lesson', 'Lesson #', 'video_lesson', '', null, true, 33),
        ACFFieldGenerator::text('video', 'Video ID', 'video_id', '', null, true, 34),
        ACFFieldGenerator::wysiwyg(
            'video_transcript',
            'Transcript',
            'video_transcript',
            ACFFieldGenerator::WYSIWYG_TAB_ALL,
            ACFFieldGenerator::WYSIWYG_TOOLBAR_BASIC,
            false,
            '',
            null,
            false
        ),
        ACFFieldGenerator::wysiwyg(
            'video_content',
            'Additional Content',
            'video_content',
            ACFFieldGenerator::WYSIWYG_TAB_ALL,
            ACFFieldGenerator::WYSIWYG_TOOLBAR_FULL,
            true,
            '',
            null,
            false
        )
    ],
    'location' => [
        [
            [
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'videos',
            ],
        ],
    ],
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => ['the_content',],
    'active' => 1,
    'description' => '',
]);

// videos settings page
acf_add_options_page([
    'page_title'  => "Video Settings",
    'menu_title'  => "Settings",
    "menu_slug"   => "video-settings",
    "capability"  => "publish_videos",
    "parent_slug" => "edit.php?post_type=videos"
]);
acf_add_local_field_group([
    'key' => 'group_video_settings',
    'title' => 'Video Settings',
    'fields' => [
        ACFFieldGenerator::text('videos_account_id', 'Account ID', 'videos_account_id'),
        ACFFieldGenerator::text('videos_google_analytics', 'Google Analytics ID', 'videos_google_analytics'),
    ],
    'location' => [
        [
            [
                'param' => 'options_page',
                'operator' => '==',
                'value' => 'video-settings',
            ],
        ],
    ],
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => ['the_content'],
    'active' => 1,
    'description' => '',
]);
