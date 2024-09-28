<?php

require_once __DIR__ . '/vendor/autoload.php';

Timber\Timber::init();

if(defined('ENV_DEV') && ENV_DEV){
    Timber::$dirname = [ 'templates', 'views', 'vite/public/images' ];
}
else{
    Timber::$dirname = [ 'templates', 'views', 'vite/dist/images' ];
}


/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;

class StarterSite extends Timber\Site {

    public function __construct() {
        add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
        add_filter( 'timber/context', array( $this, 'add_to_context' ) );
        add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
        add_action( 'init', array( $this, 'register_post_types' ) );
        add_action( 'init', array( $this, 'register_taxonomies' ) );
        add_action( 'init', array($this, 'add_sizes_img'));
        add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
        add_filter('upload_mimes', array ($this, 'allow_svg_upload'));

        $this->require_dependancies();

        parent::__construct();
    }

    public function register_post_types() {
        require_once 'inc/custom-posts-types.php';
    }

    public function register_taxonomies() {
        require_once 'inc/custom-taxonomies.php';
    }

    function require_dependancies(){
        require_once 'inc/hooks.php'; // Handle WordPress core hooks
        require_once 'inc/utils.php';
//        require_once 'inc/acf.php';
//        require_once 'inc/gravityform.php';
//        require_once 'inc/facet.php';
    }

    /** This is where you add some context
     *
     * @param string $context context['this'] Being the Twig's {{ this }}.
     */
    public function add_to_context( $context ) {

        if(class_exists('acf')){
            $context['options'] = get_fields('option');
        }
        $context['get'] = $_GET;
        $context['main_menu'] = Timber::get_menu('main_menu');
        $context['footer_menu'] = Timber::get_menu('footer_menu');
        $context['navigation_menu'] = Timber::get_menu('navigation_menu');
        $context['footer_menu_bottom'] = Timber::get_menu('footer_menu_bottom');
        $context['site'] = $this;
        $context['cookies'] = $_COOKIE;
        return $context;
    }

    public function theme_supports() {
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'menus' );
        register_nav_menus(array(
            'main_menu' => 'Menu principal',
            'footer_menu' => 'Menu footer',
            'navigation_menu' => 'Menu Navigation',
            'footer_menu_bottom' => 'Menu footer bas',
        ));
    }

    function load_scripts() {
        if (!is_admin()) {            
            if(defined('ENV_DEV') && ENV_DEV){
                wp_enqueue_script_module('vite_client', 'http://localhost:5173/@vite/client');
                wp_enqueue_script_module('global', 'http://localhost:5173/js/main.js', array(), '1.0.0', true  );
                wp_register_style('style', 'http://localhost:5173/scss/style.scss', array(), '1.0.0');
            }
            else{
                wp_register_style('style', get_template_directory_uri() .'/vite/dist/css/style.css', array(), '1.0.0');
                wp_enqueue_script_module('global', get_template_directory_uri() .'/vite/dist/js/main.js', array(), '1.0.0', true  );
            }
            wp_enqueue_style('style');
            wp_localize_script( 'global', 'ajaxurl', admin_url( 'admin-ajax.php' ) );
            wp_localize_script( 'global', 'template_directory', get_template_directory_uri() );
        }
    }

    function add_to_twig( $twig ) {
        $twig->addExtension( new Twig\Extension\StringLoaderExtension() );
//        $twig->addFilter( new Twig\TwigFilter( 'myfoo', array( $this, 'myfoo' ) ) );
        $twig->addFunction( new Twig\TwigFunction( 'get_related_articles', 'get_related_articles' ));
        $twig->addFunction( new Twig\TwigFunction( 'get_youtube_id', [$this, 'get_youtube_id'] ));
        $twig->addFunction( new Twig\TwigFunction( 'is_youtube_allowed', [$this, 'is_youtube_allowed'] ));
        return $twig;
    }

    function allow_svg_upload($mimes){
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }

    function add_sizes_img(){
//        add_image_size(  'slider',  868,  487, array('center', 'center'));
    }

    function get_youtube_id($string)
    {
        preg_match('/src="([^"]+)"/', $string, $match);
        $url = $match[1];
        $path = parse_url($url)['path'];
        $string = str_replace('/embed/', '', $path);
        return $string;
    }
    
    function is_youtube_allowed(){
        if(array_key_exists('borlabs-cookie', $_COOKIE)){
            $cookie = json_decode(stripslashes($_COOKIE['borlabs-cookie']), true);
            if(isset($cookie['consents']['external-media'])){
                return in_array('youtube', $cookie['consents']['external-media']);
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }
}

$site = new StarterSite();
