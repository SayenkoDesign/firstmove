<?php
/**
 * Template Name: Videos
 *
 * @package Supertheme
 */
use Timber\Timber;

/** @var $timber Timber */
$timber = $container->get('timber');
$context = $timber::get_context();
$current_year = get_field('year');
$context['post'] = $timber::get_post();
$context['posts'] = $timber::get_posts([
    'post_type'         => 'videos',
    'posts_per_page'    => 100,
    'meta_key'			=> 'video_lesson',
    'orderby'			=> 'meta_value_num',
    'order'				=> 'ASC',
    'meta_query'	    => [
        'relation' => 'AND',
        [
            'key' => 'video_year',
            'value' => $current_year,
            'compare' => '=',
        ],
    ],
]);
$context['year'] = get_field('year');
$context['activities'] = get_field('login_redirect', 'options');

$timber::render('pages/archive-videos.html.twig', $context);
