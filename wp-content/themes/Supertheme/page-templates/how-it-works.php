<?php
/**
 * Template Name: How it works
 *
 * @package Supertheme
 */
use Timber\Timber;

/** @var $timber Timber */
$timber = $container->get('timber');
$context = $timber::get_context();
$context['post'] = $timber::get_post();
$timber::render('pages/page.html.twig', $context);
