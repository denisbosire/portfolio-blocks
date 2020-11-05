<?php

/**
 * Plugin functions
 *
 * @link       https://themespla.net
 * @since      1.0.0
 *
 * @package    Pro_Portfolio
 * @subpackage Pro_Portfolio/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Pro_Portfolio
 * @subpackage Pro_Portfolio/includes
 * @author     Em Kerubo <afrothemes@gmail.com>
 */
class Pro_Portfolio_Functions {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */

	protected $plugin_name;

	public function portfolio_cpts() {

		/**
		 * Post Type: Portfolio.
		 */

		$labels = array(
			"name" => __( "Portfolio", "pro-portfolio" ),
			"singular_name" => __( "Portfolio", "pro-portfolio" ),
		);

		$args = array(
			"label" => __( "Portfolio", "pro-portfolio" ),
			"labels" => $labels,
			"description" => "Add Your personal portfolio here",
			"public" => true,
			"publicly_queryable" => true,
			"show_ui" => true,
			"show_in_rest" => false,
			"rest_base" => "",
			"has_archive" => false,
			"show_in_menu" => true,
			"show_in_nav_menus" => true,
			"exclude_from_search" => false,
			"capability_type" => "post",
			"map_meta_cap" => true,
			"hierarchical" => false,
			"rewrite" => array( "slug" => "portfolio", "with_front" => true ),
			"query_var" => true,
			"menu_icon" => plugin_dir_url( __DIR__ ) . 'public/icons/gallery.png',
			'show_in_rest' => true,
			"supports" => array( "title", "editor", "thumbnail" ),
	);

	register_post_type( "portfolio", $args );
	}

	public function portfolio_taxes() {

		/**
		 * Taxonomy: Project Types.
		 */

		$labels = array(
			"name" => __( "Project Types", "pro-portfolio" ),
			"singular_name" => __( "Project Type", "pro-portfolio" ),
		);

		$args = array(
			"label" => __( "Project Types", "pro-portfolio" ),
			"labels" => $labels,
			"public" => true,
			"hierarchical" => true,
			"label" => "Project Types",
			"show_ui" => true,
			"show_in_menu" => true,
			"show_in_nav_menus" => true,
			"query_var" => true,
			"rewrite" => array( 'slug' => 'project-type', 'with_front' => true, ),
			"show_admin_column" => true,
			"show_in_rest" => true,
			"rest_base" => "project-type",
			"show_in_quick_edit" => false,
	);
	register_taxonomy( "project-type", array( "portfolio" ), $args );
	}

	public function pro_customize_register($wp_customize ) {
		$wp_customize->add_setting( 'link_color' , array(
		    'default' => '#e74c3c',
		    'sanitize_callback' => 'sanitize_hex_color',
		) );

		$wp_customize->add_control(new WP_Customize_Color_Control( $wp_customize,'link_color', 
			array(
			'label'      => esc_html__( 'Link Color', 'adri' ),
			'section'    => 'colors',
			'settings'   => 'link_color',
		) ) 
	);
	}

	/**
	 * Add color styling from theme
	 */
	function adri_customizer_styles() {
	    wp_enqueue_style( 'pro-portfolio', plugin_dir_url( __DIR__ ) . 'public/css/pro-portfolio-public.css' );
	        $color = get_theme_mod( 'link_color' ); //E.g. #FF0000
	        $custom = "
	                a,
	                .adri-social a{
	                        color: {$color};
	                }";
	        wp_add_inline_style( 'pro-portfolio', $custom );
	}


	public function pro_type(){
		global $post;

	    $terms = get_the_terms( $post->ID, 'project-type');
	    if (is_array($terms) || is_object($terms)) {
	    foreach($terms as $term) {
	        echo '<a class="post-cat" href="' . get_term_link($term) . '"><span>' . $term->name . '</span></a>';
	        break; 
	    }
		}

	}


	function add_img_column($columns) {
	  $columns = array_slice($columns, 0, 2, true) + array("img" => "Featured Image") + array_slice($columns, 1, count($columns) - 1, true);
	  return $columns;
	}

	function manage_img_column($column_name, $post_id) {
	 if( $column_name == 'img' ) {
	  echo get_the_post_thumbnail($post_id, 'thumbnail');
	 }
	 return $column_name;
}

function portfolio_demo_import() {
	return array(
	  array(
		'import_file_name'             => 'Demo Import 1',
		'local_import_file'            => plugin_dir_path( __FILE__) . 'libraries/demo-files/content.xml',
		'local_import_widget_file'     => plugin_dir_path( __FILE__) . 'libraries/demo-files/widgets.json',
		//'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'ocdi/customizer.dat',
		'import_notice'                => __( 'After you import this demo, go to settings > reading and select home, as your static homepage. This demo might vary depending on which of our portfolio themes you are using', 'pro_portfolio' ),
		'preview_url'                  => 'http://aperturewp.com',
	  ),
	);
}
function portfolio_after_import_setup() {
	// Assign menus to their locations.
	$main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );

	set_theme_mod( 'nav_menu_locations', array(
			'main-menu'   => $main_menu->term_id,
		)
	);

	// Assign front page and blog page.
	$front_page_id = get_page_by_title( 'home' );

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $front_page_id->ID );

}

function portfolio_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(


		array(
			'name'      => 'One Click Demo Import',
			'slug'      => 'one-click-demo-import',
			'required'  => false,
		),

	);

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => 'pro-portfolio',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'plugins.php',            // Parent menu slug.
		'capability'   => 'manage_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	
	);

	tgmpa( $plugins, $config );
}

function portfolio_load_more_ajax_handler(){
	global $WP_Query;
	// prepare our arguments for the query, the line below breaks everything, but it's ok.
	//$args = json_decode( stripslashes( $_POST['query'] ), true );
	$args['paged'] = $_POST['page'] + 1; // we need next page to be loaded
	$args['post_status'] = 'publish';
	$args['post_type'] = 'portfolio';
 
	// use wp_query
    $portfolio = new WP_Query($args);
    if ( $portfolio->have_posts() ) :

        /* Start the Loop */
        while ( $portfolio->have_posts() ) :
            $portfolio->the_post();  
            get_template_part( 'template-parts/content', 'preview' );
        endwhile;


		endif;
 die; // here we exit the script and even no wp_reset_query() required!
}



function myplugin_ajaxurl() {

   echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
} 
}