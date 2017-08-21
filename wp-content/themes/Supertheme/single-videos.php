<?php
require_once __DIR__.'/app/bootstrap.php';
use Timber\Timber;

/** @var $timber Timber */
$timber = $container->get('timber');
$context = $timber::get_context();
$context['post'] = $timber::get_post();
$context['categories'] = Timber::get_terms('category');
$context['account'] = get_field('videos_account_id', 'options');
$context['ga'] = get_field('videos_google_analytics', 'options');

$current_year = get_field('video_year');
$current_lesson = get_field('video_lesson');
$has_previous_lesson = false;
$previous_lesson = null;
$has_next_lesson = false;
$next_lesson = null;
if($current_year && $current_lesson) {
    // get previous id
    $has_previous_lesson = $current_lesson - 1;
    $posts = get_posts([
        'posts_per_page'	=> -1,
        'post_type'			=> 'videos',
        'meta_query'	=> [
            'relation'		=> 'AND',
            [
                'key' => 'video_year',
                'value' => $current_year,
                'compare' => '=',
            ],
            [
                'key' => 'video_lesson',
                'value' => $has_previous_lesson,
                'compare' => '=',
            ]
        ],
    ]);
    if(!$posts) {
        $has_previous_lesson = false;
    } else {
        $previous_lesson = $posts[0]->ID;
    }

    // get next id
    $has_next_lesson = $current_lesson + 1;
    $posts = get_posts([
        'posts_per_page'	=> -1,
        'post_type'			=> 'videos',
        'meta_query'	=> [
            'relation'		=> 'AND',
            [
                'key' => 'video_year',
                'value' => $current_year,
                'compare' => '=',
            ],
            [
                'key' => 'video_lesson',
                'value' => $has_next_lesson,
                'compare' => '=',
            ]
        ],
    ]);
    if(!$posts) {
        $has_next_lesson = false;
    } else {
        $next_lesson = $posts[0]->ID;
    }
}
$context['next'] = $next_lesson;
$context['prev'] = $previous_lesson;

$timber::render('pages/single-video.html.twig', $context);
