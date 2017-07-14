<?php
require_once __DIR__.'/app/bootstrap.php';
use Timber\Timber;
use Timber\Image;

/** @var $timber Timber */
$timber = $container->get('timber');
$context = $timber::get_context();
$context['posts'] = $timber::get_posts();
$context['categories'] = Timber::get_terms('category');
$context['title'] = 'Blog';
$context['header_image'] = new Image(get_field('header_image', get_queried_object()->ID));
$context['header_subtitle'] = get_field('header_subtitle', get_queried_object()->ID);
$context['header_video'] = get_field('header_video', get_queried_object()->ID);
$timber::render('pages/archive.html.twig', $context);


