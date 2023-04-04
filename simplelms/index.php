<?php
/*
 * Plugin Name:Simple LMS Plugin
 * Description: This plugin is  a simple one
 * Version: 1.0
 * Author: Md.Tanvir Islam Pulok
 * Author URL: http://github.com
 */


 /* Register Custom Post Type-- Courses */
function lms_course() {
  $labels = array(
    'name'               => _x( 'Courses', 'LMSplugin' ),
    'singular_name'      => _x( 'Courses', 'LMSplugin' ),
    'add_new'            => _x( 'Add New Course', 'book' ),
    'add_new_item'       => __( 'Add New Course' ),
    'edit_item'          => __( 'Edit Course' ),
    'new_item'           => __( 'New Course' ),
    'all_items'          => __( 'All Course' ),
    'view_item'          => __( 'View Course' ),
    'search_items'       => __( 'Search Course' ),
    'not_found'          => __( 'No Course found' ),
    'not_found_in_trash' => __( 'No Course found in the Trash' ),
    'menu_name'          => 'Course'
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'Holds our products and product specific data',
    'public'        => true,
    'menu_position' => 5,
    'supports'      => array( 'title', 'editor', 'thumbnail' ),
    'has_archive'   => true,
    'rewrite'     => array( 'slug' => 'Courses' ),
    'menu_icon' => 'dashicons-database-view'

  );
  register_post_type( 'course', $args ); 
}
add_action( 'init', 'lms_course' );

/* Add Custom Fields - Courses */ 


/* Register meta boxes. */
function CF_Courses_Main() {
    add_meta_box( 
        'course_id',
        'Course Custom Field',
        'Course_back', 
        'course',
        'normal',
        'low',
    );
}


/*  Meta box display callback. */
function Course_back() {
    wp_head();
    ?>
 
<div class = "BG-Blue-1">
<br>
  <div class = 'BG-Blue-2 col-90 MA center BR'>
    <h3 class="center">Subtitle</h3>
    <input type="text" name="subtitle" class= "col-90 MA">
    <br><br>
    </div>
<br>
</div>


<div class = "BG-Blue-1">
<br>
  <div class = 'BG-Blue-2 col-90 MA center BR'>
    <h3 class="center">Course Price</h3>
    <input type="text" name="price" class= "col-90 MA">
    <br><br>
    </div>
<br>
</div>

<div class = "BG-Blue-1">
<br>
  <div class = 'BG-Blue-2 col-90 MA center BR'>
    <h3 class="center">Video Trailer</h3>
    <input type="text" name="video" class= "col-90 MA">
    <br><br>
    </div>
<br>
</div>


<div class = "BG-Blue-1">
<br>

  <div class = 'BG-Blue-2 col-90 MA center BR'>
    <h3 class="center">Curriculum </h3>
    <input type="text" name="content" class= "col-90 MA">
    <br><br>
    </div>
<br>
</div>

  <?php
}

add_action( 'admin_init', 'CF_Courses_Main' );


/* Include CSS file in Plugin */


function add_style() {
    wp_register_style( "style", plugin_dir_url(__FILE__).'scripts/style.css' );
    wp_enqueue_Style("style", plugin_dir_url(__FILE__).'scripts/style.css');
}

add_action('wp_enqueue_scripts', 'add_style');

/* Load Course Template */
function template_courses($template){
  global $post;
  if('course'=== $post-> post_type &&  locate_template(array('template_courses')) !==$template){
    return plugin_dir_path(__FILE__).'templates/template_courses.php';
  }
  return $template;
}

add_filter('single_template','template_courses');

//Create a Database Table = Course_details
function database_table(){
  global $wpdb;
  $database_table_name = $wpdb->prefix."lms_course_details";
  $charset = $wpdb->get_charset_collate;
  $course_det = "CREATE TABLE $database_table_name (
  ID int(9) NOT NULL,
  title text(100) NOT NULL,
  subtitle text(500) NOT NULL,
  video varchar(100) NOT NULL,
  price float(9) NOT NULL,
  thumbnail text NOT NULL,
  content text NOT NULL,
  PRIMARY KEY (ID)
  ) $charset; ";
  require_once(ABSPATH.'wp-admin/includes/upgrade.php');
  dbDelta($course_det);
}
register_activation_hook(__FILE__,'database_table');



//** Save Course Details to Database */  

function save_custom_fields(){
  global $wpdb;
  $ID = get_the_id();
  $title = get_the_title();
  $subtitle = $_POST['subtitle'];
  $price = $_POST['price'];
  $video = $_POST['video'];
  $content = $_POST['content'];
  global $table_prefix;
  $wpdb-> insert (
    $wpdb -> prefix.'lms_course_details',//Database table name
    [
      'ID'        => $ID,
      'price'     => $price,
      'video'     => $video,
      'subtittle' => $subtitle,
      'tittle'    => $title,
      'content'   => $content,
    ]
  );
}
add_action('save_post','save_custom_fields'); 




?>