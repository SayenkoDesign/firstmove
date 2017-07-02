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
$context['steps'] = get_field('home_steps');
$context['slider'] = get_field('slider');
$timber::render('pages/how-it-works.html.twig', $context);
