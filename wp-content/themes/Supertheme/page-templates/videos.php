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
    'post_type' => 'videos',
    'meta_query'	=> [
        'relation'		=> 'AND',
        [
            'key' => 'video_year',
            'value' => $current_year,
            'compare' => '=',
        ],
    ],
]);
$context['year'] = get_field('year');

$timber::render('pages/archive-videos.html.twig', $context);
