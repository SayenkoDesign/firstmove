<?php
/**
 * Template Name: About
 *
 * @package Supertheme
 */
use Timber\Timber;

/** @var $timber Timber */
$timber = $container->get('timber');
$context = $timber::get_context();
$context['post'] = $timber::get_post();
$context['members'] = get_field('about_members');
$context['board_members'] = get_field('about_board_members');
$context['board_advisory'] = get_field('about_board_advisory');
$context['left_checklist'] = get_field('about_checklist_left_checklist');
$context['right_checklist'] = get_field('about_checklist_right_checklist');
$timber::render('pages/about.html.twig', $context);
