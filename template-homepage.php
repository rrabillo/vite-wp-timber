<?php
/**
 * Template Name: Home Page
 */

$context = Timber::context();
$context['post'] = Timber::get_post();
$templates = array( 'template-homepage.twig' );
Timber::render( $templates, $context );
