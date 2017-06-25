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
            ACFFieldGenerator::url('about_member_email', 'Email', 'about_member_email', '', null, false, 50),
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

        ACFFieldGenerator::repeater('about_board', 'Boards', 'about_board', [
            ACFFieldGenerator::text('about_board_group_title', 'Title', 'about_board_group_title'),
            ACFFieldGenerator::wysiwyg(
                'about_board_content',
                'Lightbox Content',
                'about_board_content',
                ACFFieldGenerator::WYSIWYG_TAB_ALL,
                ACFFieldGenerator::WYSIWYG_TOOLBAR_BASIC,
                false
            ),
            ACFFieldGenerator::repeater('about_board_members', 'Board Members', 'about_board_members', [
                ACFFieldGenerator::text('about_board_group_name', 'Name', 'about_board_group_name', '&nbsp;', null, true, 50),
                ACFFieldGenerator::text('about_board_group_position', 'Position', 'about_board_group_position', 'use &lt;br /&gt; to force a line break', null, true, 50),
            ]),
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
