<?php
/**
 * Template Name: Page de contenu
 */

$context = Timber::context();
$context['post'] = Timber::get_post();

Timber::render( 'template-content.twig', $context );
