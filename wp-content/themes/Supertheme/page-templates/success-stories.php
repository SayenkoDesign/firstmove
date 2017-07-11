<?php
/**
 * Template Name: Success Stories
 *
 * @package Supertheme
 */
use Timber\Timber;

/** @var $timber Timber */
$timber = $container->get('timber');
$context = $timber::get_context();
$args = array(
    'post_type' => 'success_stories',
);
$context['posts'] = Timber::get_posts($args);
$context['post'] = Timber::get_post();
$timber::render('pages/success-story-archive.html.twig', $context);
