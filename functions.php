<?php
/**
 * 功能：转义评论内容，防止恶意代码
 */
function ludou_code_escape( $incoming_comment ) {
    $incoming_comment = strip_tags($incoming_comment);
    return $incoming_comment;
}

add_filter( 'comment_text', 'ludou_code_escape' );
add_filter( 'comment_text_rss', 'ludou_code_escape' );

/**
 * 功能：防止评论冒充博主
 */
function ludou_usecheck($incoming_comment) {
    $isSpam = 0;

    // 将以下代码中的 jason 改成博主昵称，昵称不区分大小写
    if (strcasecmp(trim($incoming_comment['comment_author']), 'jason') == 0)
        $isSpam = 1;
    if (strcasecmp(trim($incoming_comment['comment_author']), 'izhaojie') == 0)
        $isSpam = 1;
    if (strcasecmp(trim($incoming_comment['comment_author']), 'zhaojie') == 0)
        $isSpam = 1;

    // 将以下代码中的 example#ludou.org 改成博主Email
    if (strcasecmp(trim($incoming_comment['comment_author_email']), 'jasonz666@qq.com') == 0)
        $isSpam = 1;
    if (strcasecmp(trim($incoming_comment['comment_author_email']), '718395842@qq.com') == 0)
        $isSpam = 1;

    if(!$isSpam)
        return $incoming_comment;

    wp_die('请勿冒充博主发表评论');
}

if(!is_user_logged_in())
    add_filter( 'preprocess_comment', 'ludou_usecheck' );

/** 以上是自定义函数 **/

/**
 * functions and definitions
 *
 * @package WordPress
 * @subpackage Pure
 * @since Pure 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Pure 1.0
 */
if ( ! isset( $content_width ) ) {
    $content_width = 660;
}

if ( ! function_exists( 'pure_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * @since Pure 1.0
 */
function pure_setup()
{
    /*
     * Make theme available for translation.
     * Translations can be filed in the /languages/ directory.
     * If you're building a theme based on pure, use a find and replace
     * to change 'pure' to the name of your theme in all the template files
     */
    load_theme_textdomain( 'pure', get_template_directory() . '/languages' );

    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support( 'title-tag' );

    /*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * See: https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
     */
    add_theme_support( 'post-thumbnails' );
    set_post_thumbnail_size( 825, 510, true );

    // This theme uses wp_nav_menu() in two locations.
    register_nav_menus( array(
        'primary' => __( 'Primary Menu',      'pure' ),
        'social'  => __( 'Social Links Menu', 'pure' ),
    ) );

    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support( 'html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
    ) );

    /*
     * Enable support for Post Formats.
     *
     * See: https://codex.wordpress.org/Post_Formats
     */
    add_theme_support( 'post-formats', array(
        'aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio', 'chat'
    ) );
}
endif; // pure_setup
add_action( 'after_setup_theme', 'pure_setup' );

/**
 * Register widget area.
 *
 * @since Pure 1.0
 *
 * @link https://codex.wordpress.org/Function_Reference/register_sidebar
 */
function pure_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Widget Area', 'pure' ),
        'id'            => 'sidebar-1',
        'description'   => __( 'Add widgets here to appear in your sidebar.', 'pure' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'pure_widgets_init' );

/**
 * Enqueue scripts and styles.
 *
 * @since Pure 1.0
 */
function pure_scripts() {
    // Bootstrap core CSS
    wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/bootstrap/css/bootstrap.min.css', array(), '3.3.6');

    // font-awesome
    wp_enqueue_style( 'font-awesome', get_template_directory_uri() .'/assets/font-awesome/css/font-awesome.min.css', array(), '4.3.0');

    // Load our main stylesheet.
    wp_enqueue_style( 'pure-style', get_stylesheet_uri() );

    // jQuery
    wp_enqueue_script( 'jQuery', get_template_directory_uri() . '/assets/js/jquery-1.11.3.min.js', array(), '1.11.3', true);

    // Bootstrap core JavaScript
    wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/assets/bootstrap/js/bootstrap.min.js', array(), '3.3.6', true);

    // IE10 viewport hack for Surface/desktop Windows 8 bug
    wp_enqueue_script( 'viewport', get_template_directory_uri() . '/assets/js/ie10-viewport-bug-workaround.js', array(), '3.3.5', true);

    // 回复
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }

    // 自定义
    wp_enqueue_script( 'pure-script', get_template_directory_uri() . '/assets/js/functions.js', array( 'jQuery' ), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'pure_scripts' );

/* 不显示 admin bar */
//show_admin_bar( false );

/* 移除不需要的 action */
// remove_action( 'wp_head', 'wlwmanifest_link');
remove_action( 'wp_head', 'wp_generator');

/* 移除 emoji */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

function pure_excerpt_length($length) {
    return 120;
}

add_filter('excerpt_length', 'pure_excerpt_length');

function pure_replace_avatar($avatar) {
  $avatar = str_replace(array("www.gravatar.com", "0.gravatar.com", "1.gravatar.com", "2.gravatar.com"), "secure.gravatar.com", $avatar);
  return $avatar;
}

add_filter( 'get_avatar', 'pure_replace_avatar', 10, 3 );

function pure_comment_form_defaults( $defaults ) {
    $_defaults = array(
        'class_submit' => 'submit btn btn-custom',
    );

    return array_merge($defaults, $_defaults);
}

add_filter( 'comment_form_defaults', 'pure_comment_form_defaults' );

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Load custom nav walker
 */
require get_template_directory() . '/inc/wp_pure_navbar.php';

/**
 * Custom template tags for this theme.
 *
 * @since Pure 1.0
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 *
 * @since Pure 1.0
 */
require get_template_directory() . '/inc/customizer.php';

/* 自定义评论的默认头像 */
function newgravatar($avatar_defaults) {
    $myavatar = get_bloginfo('template_directory') . '/images/1.jpg'; 
    $avatar_defaults[$myavatar] = "自定义的头像"; 
    return $avatar_defaults; 
}

add_filter( 'avatar_defaults', 'newgravatar' );

/* 設定評論字數限制開始 */
function set_comments_length($commentdata) {
    $minCommentlength = 3;      //最少字數限制
    $maxCommentlength = 3000;   //最多字數限制
    $pointCommentlength = mb_strlen($commentdata['comment_content'],'UTF8');    //mb_strlen 1個中文字符當作1個長度
    if ($pointCommentlength < $minCommentlength){
        header("Content-type: text/html; charset=utf-8");
        wp_die('抱歉，您的评论字数过少，请至少输入' . $minCommentlength .'个字（目前字数：'. $pointCommentlength .'个字）');
        exit;
    }
    if ($pointCommentlength > $maxCommentlength){
        header("Content-type: text/html; charset=utf-8");
        wp_die('抱歉，您的评论字数过多，请少于' . $maxCommentlength .'个字（目前字数：'. $pointCommentlength .'个字）');
        exit;
    }
    return $commentdata;
}

add_filter('preprocess_comment', 'set_comments_length');
