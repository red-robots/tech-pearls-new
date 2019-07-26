<?php 
/* Custom Post Types */

//DASH ICONS = https://developer.wordpress.org/resource/dashicons/
add_action('init', 'js_custom_init', 1);
function js_custom_init() {
    $post_types = array(
        array(
            'post_type' => 'custom_certificates',
            'menu_name' => 'Custom Certificates',
            'plural'    => 'Custom Certificates',
            'single'    => 'Custom Certificate',
            'menu_icon' => 'dashicons-awards',
            'supports'  => array('title','editor'),
            'menu_position'=> 3,
            'rewrite'   => array('slug' => 'mycertificates','with_front' => false)
        ),
        array(
            'post_type' => 'team',
            'menu_name' => 'Team',
            'plural'    => 'Team',
            'single'    => 'Team',
            'menu_icon' => 'dashicons-groups',
            'supports'  => array('title','editor','thumbnail')
        ),
        array(
            'post_type' => 'testimonial',
            'menu_name' => 'Testimonials',
            'plural'    => 'Testimonials',
            'single'    => 'Testimonial',
            'menu_icon' => 'dashicons-format-quote',
            'supports'  => array('title','editor','thumbnail')
        )
    );
    
    if($post_types) {
        foreach ($post_types as $p) {
            $p_type = ( isset($p['post_type']) && $p['post_type'] ) ? $p['post_type'] : ""; 
            $single_name = ( isset($p['single']) && $p['single'] ) ? $p['single'] : "Custom Post"; 
            $plural_name = ( isset($p['plural']) && $p['plural'] ) ? $p['plural'] : "Custom Post"; 
            $menu_name = ( isset($p['menu_name']) && $p['menu_name'] ) ? $p['menu_name'] : $p['plural']; 
            $menu_icon = ( isset($p['menu_icon']) && $p['menu_icon'] ) ? $p['menu_icon'] : "dashicons-admin-post"; 
            $supports = ( isset($p['supports']) && $p['supports'] ) ? $p['supports'] : array('title','editor','custom-fields','thumbnail'); 
            $taxonomies = ( isset($p['taxonomies']) && $p['taxonomies'] ) ? $p['taxonomies'] : array(); 
            $parent_item_colon = ( isset($p['parent_item_colon']) && $p['parent_item_colon'] ) ? $p['parent_item_colon'] : ""; 
            $menu_position = ( isset($p['menu_position']) && $p['menu_position'] ) ? $p['menu_position'] : 20; 
            $rewrite = ( isset($p['rewrite']) && $p['rewrite'] ) ? $p['rewrite'] : true; 
            
            if($p_type) {
                
                $labels = array(
                    'name' => _x($plural_name, 'post type general name'),
                    'singular_name' => _x($single_name, 'post type singular name'),
                    'add_new' => _x('Add New', $single_name),
                    'add_new_item' => __('Add New ' . $single_name),
                    'edit_item' => __('Edit ' . $single_name),
                    'new_item' => __('New ' . $single_name),
                    'view_item' => __('View ' . $single_name),
                    'search_items' => __('Search ' . $plural_name),
                    'not_found' =>  __('No ' . $plural_name . ' found'),
                    'not_found_in_trash' => __('No ' . $plural_name . ' found in Trash'), 
                    'parent_item_colon' => $parent_item_colon,
                    'menu_name' => $menu_name
                );
            
            
                $args = array(
                    'labels' => $labels,
                    'public' => true,
                    'publicly_queryable' => true,
                    'show_ui' => true, 
                    'show_in_menu' => true, 
                    'query_var' => true,
                    'rewrite' => $rewrite,
                    'capability_type' => 'post',
                    'has_archive' => false, 
                    'hierarchical' => false, // 'false' acts like posts 'true' acts like pages
                    'menu_position' => $menu_position,
                    'menu_icon'=> $menu_icon,
                    'supports' => $supports
                ); 
                
                register_post_type($p_type,$args); // name used in query
                
            }
            
        }
    }
}

// Add new taxonomy, make it hierarchical (like categories)
add_action( 'init', 'ii_custom_taxonomies', 0 );
function ii_custom_taxonomies() {
        // $posts = array(
        //     array(
        //         'post_type' => 'position',
        //         'menu_name' => 'Categories',
        //         'plural'    => 'Assignment Categories',
        //         'single'    => 'Category',
        //         'taxonomy'  => 'position_categories'
        //     )
        // );
    $posts = array(); 
    if($posts) {
        foreach($posts as $p) {
            $p_type = ( isset($p['post_type']) && $p['post_type'] ) ? $p['post_type'] : ""; 
            $single_name = ( isset($p['single']) && $p['single'] ) ? $p['single'] : "Custom Post"; 
            $plural_name = ( isset($p['plural']) && $p['plural'] ) ? $p['plural'] : "Custom Post"; 
            $menu_name = ( isset($p['menu_name']) && $p['menu_name'] ) ? $p['menu_name'] : $p['plural'];
            $taxonomy = ( isset($p['taxonomy']) && $p['taxonomy'] ) ? $p['taxonomy'] : "";
            
            
            if( $taxonomy && $p_type ) {
                $labels = array(
                    'name' => _x( $menu_name, 'taxonomy general name' ),
                    'singular_name' => _x( $single_name, 'taxonomy singular name' ),
                    'search_items' =>  __( 'Search ' . $plural_name ),
                    'popular_items' => __( 'Popular ' . $plural_name ),
                    'all_items' => __( 'All ' . $plural_name ),
                    'parent_item' => __( 'Parent ' .  $single_name),
                    'parent_item_colon' => __( 'Parent ' . $single_name . ':' ),
                    'edit_item' => __( 'Edit ' . $single_name ),
                    'update_item' => __( 'Update ' . $single_name ),
                    'add_new_item' => __( 'Add New ' . $single_name ),
                    'new_item_name' => __( 'New ' . $single_name ),
                  );

              register_taxonomy($taxonomy,array($p_type), array(
                'hierarchical' => true,
                'labels' => $labels,
                'show_ui' => true,
                'query_var' => true,
                'rewrite' => array( 'slug' => $taxonomy ),
              ));
            }
            
        }
    }
}

// Add the custom columns to the position post type:
add_filter( 'manage_posts_columns', 'set_custom_cpt_columns' );
function set_custom_cpt_columns($columns) {
    global $wp_query;
    $query = isset($wp_query->query) ? $wp_query->query : '';
    $post_type = ( isset($query['post_type']) ) ? $query['post_type'] : '';
    
    if($post_type=='custom_certificates') {
        unset( $columns['date'] );
        $columns['is_active'] = __( 'Active', 'acstarter' );
        $columns['date'] = __( 'Date', 'acstarter' );
    }

    if($post_type=='team') {
        unset( $columns['date'] );
        $columns['photo'] = __( 'Photo', 'acstarter' );
        $columns['date'] = __( 'Date', 'acstarter' );
    }
    
    return $columns;
}

// Add the data to the custom columns for the book post type:
add_action( 'manage_posts_custom_column' , 'custom_post_column', 10, 2 );
function custom_post_column( $column, $post_id ) {
    global $wp_query;
    $query = isset($wp_query->query) ? $wp_query->query : '';
    $post_type = ( isset($query['post_type']) ) ? $query['post_type'] : '';
    if($post_type=='team') {
        switch ( $column ) {
            case 'photo' :
                $post_thumbnail_id = get_post_thumbnail_id( $post_id );
                $img = wp_get_attachment_image_src($post_thumbnail_id,'medium');
                $img_src = ($img) ? $img[0] : '';
                if($img_src) {
                    $the_photo = '<span class="tmphoto" style="display:inline-block;width:50px;height:50px;background:url('.$img_src.') top center no-repeat #e2e1e1;background-size:cover;text-align:center;"></span>';
                } else {
                    $the_photo = '<span class="tmphoto" style="display:inline-block;width:50px;height:50px;background:#e2e1e1;text-align:center;">';
                    $the_photo .= '<i class="dashicons dashicons-businessman" style="font-size:33px;position:relative;top:8px;left:-6px;opacity:0.3;"></i>';
                    $the_photo .= '</span>';
                }
                
                echo $the_photo;
        }
    }

    if($post_type=='custom_certificates') {
        switch ( $column ) {
            case 'is_active' :
                $active = get_field('activate',$post_id);
                echo ($active) ? '<span style="color:#2b9d0c;font-size:28px;line-height:1" class="dashicons dashicons-yes"></span>':'<strong>&mdash;</strong>';
        }
    }
    
}
