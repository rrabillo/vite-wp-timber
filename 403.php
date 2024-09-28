<?php
/**
 * 403.php
 */

$context = Timber::context();
$post = Timber::get_post();
$context['post'] = $post;
$templates = array( '403.twig' );
Timber::render( $templates, $context );