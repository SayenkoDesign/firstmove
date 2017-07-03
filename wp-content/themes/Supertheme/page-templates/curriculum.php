<?php
/**
 * Template Name: Curriculum
 *
 * @package Supertheme
 */
use Timber\Timber;

/** @var $timber Timber */
$timber = $container->get('timber');
$context = $timber::get_context();
$context['post'] = $timber::get_post();
$context['downloads'] = get_field('curriculum_alt_content');
$context['fan_art'] = get_field('curriculum_slider');
$context['accordion'] = get_field('curriculum_accordions');
$context['slider'] = get_field('slider');
$timber::render('pages/curriculum.html.twig', $context);
