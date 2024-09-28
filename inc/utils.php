<?php

use Timber\Timber;

function get_related_articles($timberPost){
    $tags = array_map(function($tag) {
        return $tag->id;
    }, $timberPost->terms('category'));
    
    $args = [
        'category__in'        => $tags,
        'post__not_in'   => array($timberPost->id),
        'posts_per_page' => 3,      
        'orderby'        => 'date', 
        'order'          => 'DESC', 
    ];
    
    return Timber::get_posts($args);
}