<?php
/**
 * Template Name: Donors
 *
 * @package Supertheme
 */
use Timber\Timber;

/** @var $timber Timber */
$timber = $container->get('timber');
$context = $timber::get_context();
$context['post'] = $timber::get_post();
$context['rows'] = get_field('donors_alt_content');
$context['platinum'] = get_field('donors_platinum_donors');
$context['gold'] = get_field('donors_gold_donors');
$context['silver'] = get_field('donors_silver_donors');
$context['copper'] = get_field('donors_copper_donors');
$context['supporters'] = get_field('donors_supporters_donors');
$timber::render('pages/donors.html.twig', $context);
