<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://themespla.net
 * @since      1.0.0
 *
 * @package    Pro_Portfolio
 * @subpackage Pro_Portfolio/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Pro_Portfolio
 * @subpackage Pro_Portfolio/includes
 * @author     Em Kerubo <afrothemes@gmail.com>
 */
class Pro_Portfolio {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Pro_Portfolio_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PRO-PORTFOLIO' ) ) {
			$this->version = PRO-PORTFOLIO;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'pro-portfolio';

		$this->constants();
		$this->load_dependencies();
		$this->set_locale();
		$this->functions();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}
		public function constants() {
			define( 'PRO_DIR', plugin_dir_path( dirname( __FILE__ ) ) );
		}
	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Pro_Portfolio_Loader. Orchestrates the hooks of the plugin.
	 * - Pro_Portfolio_i18n. Defines internationalization functionality.
	 * - Pro_Portfolio_Admin. Defines all hooks for the admin area.
	 * - Pro_Portfolio_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pro-portfolio-loader.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pro-portfolio-functions.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pro-portfolio-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-pro-portfolio-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-pro-portfolio-public.php';

		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/libraries/TGM/class-tgm-plugin-activation.php';


		$this->loader = new Pro_Portfolio_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Pro_Portfolio_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Pro_Portfolio_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}


	private function functions() {

		$functions = new Pro_Portfolio_Functions();

		$this->loader->add_action( 'init', $functions, 'portfolio_cpts' );
		$this->loader->add_action( 'init', $functions, 'portfolio_taxes' );
		//$this->loader->add_action( 'init', $functions , 'portfolio_register_required_plugins');
		$this->loader->add_action( 'wp_enqueue_scripts', $functions, 'adri_customizer_styles' );
		$this->loader->add_action( 'customize_register', $functions, 'pro_customize_register' );
		$this->loader->add_action( 'pro-portfolio-type', $functions, 'pro_type' );
		$this->loader->add_filter('manage_posts_columns', $functions,'add_img_column');
		$this->loader->add_filter('manage_posts_custom_column', $functions,'manage_img_column', 10, 2);
		$this->loader->add_filter( 'pt-ocdi/import_files',$functions, 'portfolio_demo_import' );
		$this->loader->add_action( 'pt-ocdi/after_import',$functions, 'portfolio_after_import_setup' ); 
		$this->loader->add_action('wp_ajax_load_more',$functions, 'portfolio_load_more_ajax_handler');
		$this->loader->add_action('wp_ajax_nopriv_load_more',$functions, 'portfolio_load_more_ajax_handler');
		$this->loader->add_action('wp_head', $functions, 'myplugin_ajaxurl');


	}
	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Pro_Portfolio_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Pro_Portfolio_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Pro_Portfolio_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
