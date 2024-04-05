<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://motion-media.de
 * @since      1.0.0
 *
 * @package    Mm_Update_Cache_Clear
 * @subpackage Mm_Update_Cache_Clear/includes
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
 * @package    Mm_Update_Cache_Clear
 * @subpackage Mm_Update_Cache_Clear/includes
 * @author     Motion Media <info@motion-media.de>
 */
class Mm_Update_Cache_Clear {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Mm_Update_Cache_Clear_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	 * @noinspection PhpUndefinedFunctionInspection
	 */
	public function __construct() {
		if ( defined( 'MM_UPDATE_CACHE_CLEAR_VERSION' ) ) {
			$this->version = MM_UPDATE_CACHE_CLEAR_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'mm-update-cache-clear';

		$this->load_dependencies();

		add_filter('wpmudev_dashboard_upgrader_get_plugin_data', function ($data, $file) {
			$this->schedule_cache_clear();
		});

		// Clear cache when a plugin update completes
		add_filter('update_plugin_complete_actions', function ($update_actions, $plugin) {
			$this->schedule_cache_clear();
			return $update_actions;
		});


		add_action('mm_clear_cache', array($this, 'clear_cache'));

		add_action( 'upgrader_process_complete', function() {
			$this->schedule_cache_clear();
		});

		// Clear cache when update to WP Core completes
		add_action( '_core_updated_successfully', function() {
			$this->schedule_cache_clear();
		});

		add_action('admin_notices', function() {
			if (get_transient("mm_toast"))
			{
				echo '<div class="notice notice-warning is-dismissible">
                 <p>Der Cache wurde wegen eines Updates geleert.</p>
             </div>';
			}
		});
	}

	private function schedule_cache_clear() {
		wp_schedule_single_event(time() + 2 * 60, 'mm_clear_cache');
	}

	private function clear_cache()
	{
		if ( function_exists( 'rocket_clean_domain' ) ) {
			rocket_clean_domain();
			set_transient( "mm_toast", true, 180 );
		}
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Mm_Update_Cache_Clear_Loader. Orchestrates the hooks of the plugin.
	 * - Mm_Update_Cache_Clear_i18n. Defines internationalization functionality.
	 * - Mm_Update_Cache_Clear_Admin. Defines all hooks for the admin area.
	 * - Mm_Update_Cache_Clear_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mm-update-cache-clear-loader.php';

		$this->loader = new Mm_Update_Cache_Clear_Loader();

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
	 * @return    Mm_Update_Cache_Clear_Loader    Orchestrates the hooks of the plugin.
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
