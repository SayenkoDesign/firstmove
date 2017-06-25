<?php
require_once __DIR__.'/app/bootstrap.php';
use Timber\Timber;

/** @var $timber Timber */
$timber = $container->get('timber');
$context = $timber::get_context();
$context['post'] = $timber::get_post();
if($title = get_field('header_options_title')) {
    $context['title'] = $title;
}
if($title = get_field('header_subtitle')) {
    $context['header_subtitle'] = $title;
}
if($video = get_field('header_video', false, false)) {
    $context['header_video'] = $video;
}
$timber::render('pages/page.html.twig', $context);
