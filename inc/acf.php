<?php
/* Option page ACF */
if( function_exists('acf_add_options_page') )
{
    acf_add_options_page(array('page_title' => 'Options du thème'));
}
/* Custom editors */
add_filter('acf/fields/wysiwyg/toolbars', function ($toolbars) {

    $toolbars['Very Simple' ] = array();
    $toolbars['Very Simple' ][1] = array('bold' , 'italic' , 'underline', 'bullist' );


    $toolbars['Bold only' ] = array();
    $toolbars['Bold only'][1] = array('bold');

    $toolbars['Italic only' ] = array();
    $toolbars['Italic only'][1] = array('italic');

    return $toolbars;
});

//function colors_field( $field ){
//
//    $field['choices'] = [];
//
//    return $field;
//}
//
//add_filter( "acf/load_field/key=field_66bc713e5f366", 'colors_field', 10, 1 );