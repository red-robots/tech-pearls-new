<?php
/**
 * Custom theme functions.
 *
 * 
 *
 * @package ACStarter
 */

/**
* user_search_by_multiple_parameters
* 
* Modifies the wp_user_query to allow for User searching (within the WordPress dashboard > Users > All Users) by:
* first_name
* last_name
* nickname
* any other custom meta_key added to user profiles (manually or through something like Advanced Custom Fields)
*
* @param    object  $wp_user_query  a WordPress query object
*
* @return   object  $wp_user_query  a modified version of the WordPress query object parameter
*/
function user_search_by_multiple_parameters($wp_user_query) {
    if (false === strpos($wp_user_query->query_where, '@') && !empty($_GET["s"])) {
        global $wpdb;

        $user_ids = array();
        $user_ids_per_term = array();

    // Usermeta fields to search
    $usermeta_keys = array('first_name', 'last_name', 'nickname');

    $query_string_meta = "";
    $search_terms = $_GET["s"];
    $search_terms_array = explode(' ', $search_terms);
    
    // Search users for each search term (word) individually
    foreach ($search_terms_array as $search_term) {
      // reset ids per loop
      $user_ids_per_term = array();
      
      // add all custom fields into the query
      if (!empty($usermeta_keys)) {
        $query_string_meta = "meta_key='" . implode("' OR meta_key='", $wpdb->escape($usermeta_keys)) . "'";
      }

      // Query usermeta table
            $usermeta_results = $wpdb->get_results($wpdb->prepare("SELECT DISTINCT user_id FROM $wpdb->usermeta WHERE (" . $query_string_meta . ") AND LOWER(meta_value) LIKE '%%%s%%'", $search_term));

            foreach ($usermeta_results as $usermeta_result) {
              if (!in_array($usermeta_result->user_id, $user_ids_per_term)) {
                  array_push($user_ids_per_term, $usermeta_result->user_id);
                }
            }
      
      // Query users table
            $users_results = $wpdb->get_results($wpdb->prepare("SELECT DISTINCT ID FROM $wpdb->users WHERE LOWER(user_nicename) LIKE '%%%s%%' OR LOWER(user_email) LIKE '%%%s%%' OR LOWER(display_name) LIKE '%%%s%%'", $search_term, $search_term, $search_term));

            foreach ($users_results as $users_result) {
                if (!in_array($users_result->ID, $user_ids_per_term)) {
                    array_push($user_ids_per_term, $users_result->ID);
                }
            }
            
            // Limit results to matches of all search terms
            if (empty($user_ids)) {
              $user_ids = array_merge($user_ids, $user_ids_per_term);
            } else {
                if (!empty($user_ids_per_term)) {
                    $user_ids = array_unique(array_intersect($user_ids, $user_ids_per_term));
                }
            }
        }
    
    // Convert IDs to comma separated string
        $ids_string = implode(',', $user_ids);

    if (!empty($ids_string)) {
      // network users search (multisite)
      $wp_user_query->query_where = str_replace("user_nicename LIKE '" . $search_terms . "'", "ID IN(" . $ids_string . ")", $wp_user_query->query_where);
      
      // site (blog) users search
            $wp_user_query->query_where = str_replace("user_nicename LIKE '%" . $search_terms . "%'", "ID IN(" . $ids_string . ")", $wp_user_query->query_where);
            
            // network/site users search by number (WordPress assumes user ID number)
            $wp_user_query->query_where = str_replace("ID = '" . $search_terms . "'", "ID = '" . $search_terms . "' OR ID IN(" . $ids_string . ")", $wp_user_query->query_where);
    }
    }

    return $wp_user_query;
}
add_action('pre_user_query', 'user_search_by_multiple_parameters');




/*-------------------------------------
	Custom client login, link and title.
---------------------------------------*/
function my_login_logo() { 
  $custom_logo_id = get_theme_mod( 'custom_logo' );
  $logoImg = wp_get_attachment_image_src($custom_logo_id,'large');
  $logo_url = ($logoImg) ? $logoImg[0] : '';
  if($custom_logo_id) { ?>
  <style type="text/css">
    div#login h1 {
      padding: 10px;
      margin-bottom: 10px;
      background-color: #063748;
    }
    body.login div#login h1 a {
      <?php if($logo_url) { ?>
        background-image: url(<?php echo $logo_url; ?>);
      <?php } ?> 
      background-size: contain;
      background-color: #063748;
      width: 100%;
      height: 60px;
      margin: 0 0;
    }
  </style>
<?php }
}
add_action( 'login_enqueue_scripts', 'my_login_logo' );

// Change Link
function loginpage_custom_link() {
	return the_permalink();
}
add_filter('login_headerurl','loginpage_custom_link');


/*-------------------------------------
	Adds Options page for ACF.
---------------------------------------*/
if( function_exists('acf_add_options_page') ) {acf_add_options_page();}

/*-------------------------------------
  Hide Front End Admin Menu Bar
---------------------------------------*/
//show_admin_bar( false );
if ( ! current_user_can( 'manage_options' ) ) {
    show_admin_bar( false );
}
 /*-------------------------------------
  Move Yoast to the Bottom
---------------------------------------*/
function yoasttobottom() {
  return 'low';
}
add_filter( 'wpseo_metabox_prio', 'yoasttobottom');
/*-------------------------------------
  Custom WYSIWYG Styles

  If you are using the Plugin: MRW Web Design Simple TinyMCE

  Keep this commented out to keep from getting duplicate "Format" dropdowns

---------------------------------------*/
// function acc_custom_styles($buttons) {
//   array_unshift($buttons, 'styleselect');
//   return $buttons;
// }
// add_filter('mce_buttons_2', 'acc_custom_styles');


/*
* Callback function to filter the MCE settings


  But always use this to get the custom formats

*/
 
function my_mce_before_init_insert_formats( $init_array ) {  
 
// Define the style_formats array
 
  $style_formats = array(  
    // Each array child is a format with it's own settings
    
    // A block element
    array(  
      'title' => 'Block Color',  
      'block' => 'span',  
      'classes' => 'custom-color-block',
      'wrapper' => true,
      
    ),
    // inline color
    array(  
      'title' => 'Custom Color',  
      'inline' => 'span',  
      'classes' => 'custom-color',
      'wrapper' => true,
      
    ),
     array(
        'title' => 'Header 2',
        'format' => 'h2',
        //'icon' => 'bold'
    ),
    array(
        'title' => 'Header 3',
        'format' => 'h3'
    ),
    array(
        'title' => 'Paragraph',
        'format' => 'p'
    )
  );  
  // Insert the array, JSON ENCODED, into 'style_formats'
  $init_array['style_formats'] = json_encode( $style_formats );  
  
  return $init_array;  
  
} 
// Attach callback to 'tiny_mce_before_init' 
add_filter( 'tiny_mce_before_init', 'my_mce_before_init_insert_formats' ); 
// Add styles to WYSIWYG in your theme's editor-style.css file
function my_theme_add_editor_styles() {
    add_editor_style( 'editor-style.css' );
}
add_action( 'init', 'my_theme_add_editor_styles' );
/*-------------------------------------
  Change Admin Labels
---------------------------------------*/
function change_post_menu_label() {
    global $menu;
    global $submenu;
    $menu[5][0] = 'News';
    $submenu['edit.php'][5][0] = 'News';
    $submenu['edit.php'][10][0] = 'Add News Item';
    //$submenu['edit.php'][15][0] = 'Status'; // Change name for categories
    //$submenu['edit.php'][16][0] = 'Labels'; // Change name for tags
    echo '';
}

function change_post_object_label() {
        global $wp_post_types;
        $labels = &$wp_post_types['post']->labels;
        $labels->name = 'News';
        $labels->singular_name = 'News Item';
        $labels->add_new = 'Add News Item';
        $labels->add_new_item = 'Add News Item';
        $labels->edit_item = 'Edit News Item';
        $labels->new_item = 'News Item';
        $labels->view_item = 'View News Item';
        $labels->search_items = 'Search News';
        $labels->not_found = 'No News found';
        $labels->not_found_in_trash = 'No News found in Trash';
    }
add_action( 'init', 'change_post_object_label' );
add_action( 'admin_menu', 'change_post_menu_label' );

/*-------------------------------------
  Add a last and first menu class option
---------------------------------------*/

function ac_first_and_last_menu_class($items) {
  foreach($items as $k => $v){
    $parent[$v->menu_item_parent][] = $v;
  }
  foreach($parent as $k => $v){
    $v[0]->classes[] = 'first';
    $v[count($v)-1]->classes[] = 'last';
  }
  return $items;
}
add_filter('wp_nav_menu_objects', 'ac_first_and_last_menu_class');
/*-------------------------------------



 Limit File Size in Media Uploader




---------------------------------------*/
define('WPISL_DEBUG', false);

require_once ('wpisl-options.php');

class WP_Image_Size_Limit {

  public function __construct()  {  
      add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array($this, 'add_plugin_links') );
      add_filter('wp_handle_upload_prefilter', array($this, 'error_message'));
  }  

  public function add_plugin_links( $links ) {
    return array_merge(
      array(
        'settings' => '<a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin/options-media.php?settings-updated=true#wpisl-limit">Settings</a>'
      ),
      $links
    );
  }

  public function get_limit() {
    $option = get_option('wpisl_options');

    if ( isset($option['img_upload_limit']) ){
      $limit = $option['img_upload_limit'];
    } else {
      $limit = $this->wp_limit();
    }

    return $limit;
  }

  public function output_limit() {
    $limit = $this->get_limit();
    $limit_output = $limit;
    $mblimit = $limit / 1000;


    if ( $limit >= 1000 ) {
      $limit_output = $mblimit;
    }

    return $limit_output;
  }

  public function wp_limit() {
    $output = wp_max_upload_size();
    $output = round($output);
    $output = $output / 1000000; //convert to megabytes
    $output = round($output);
    $output = $output * 1000; // convert to kilobytes

    return $output;

  }

  public function limit_unit() {
    $limit = $this->get_limit();

    if ( $limit < 1000 ) {
      return 'KB';
    }
    else {
      return 'MB';
    }

  }

  public function error_message($file) {
    $size = $file['size'];
    $size = $size / 1024;
    $type = $file['type'];
    $is_image = strpos($type, 'image');
    $limit = $this->get_limit();
    $limit_output = $this->output_limit();
    $unit = $this->limit_unit();

    if ( ( $size > $limit ) && ($is_image !== false) ) {
       $file['error'] = 'Image files must be smaller than '.$limit_output.$unit;
       if (WPISL_DEBUG) {
        $file['error'] .= ' [ filesize = '.$size.', limit ='.$limit.' ]';
       }
    }
    return $file;
  }

  public function load_styles() {
    $limit = $this->get_limit();
    $limit_output = $this->output_limit();
    $mblimit = $limit / 1000;
    $wplimit = $this->wp_limit();
    $unit = $this->limit_unit();


    ?>
    <!-- .Custom Max Upload Size -->
    <style type="text/css">
    .after-file-upload {
      display: none;
    }
    <?php if ( $limit < $wplimit ) : ?>
    .upload-flash-bypass:after {
      content: 'Maximum image size: <?php echo $limit_output . $unit; ?>.';
      display: block;
      margin: 15px 0;
    }
    <?php endif; ?>

    </style>
    <!-- END Custom Max Upload Size -->
    <?php
  }


}
$WP_Image_Size_Limit = new WP_Image_Size_Limit;
add_action('admin_head', array($WP_Image_Size_Limit, 'load_styles'));