<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.salesmate.io
 * @since      1.0
 *
 * @package    Salesmate_Messenger
 * @subpackage Salesmate_Messenger/includes
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
 * @since      1.0
 * @package    Salesmate_Messenger
 * @subpackage Salesmate_Messenger/includes
 * @author     Salesmate <info@salesmate.io>
 */
class Salesmate_Messenger {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0
	 * @access   protected
	 * @var      Salesmate_Messenger_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0
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
	 * @since    1.0
	 */
	public function __construct() {
		if ( defined( 'SALESMATE_MESSENGER_VERSION' ) ) {
			$this->version = SALESMATE_MESSENGER_VERSION;
		} else {
			$this->version = '1.0';
		}
		$this->plugin_name = 'salesmate-messenger';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		add_action( 'admin_menu', array( $this, 'salesmate_messenger_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'salesmate_messenger_page_init' ) );

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Salesmate_Messenger_Loader. Orchestrates the hooks of the plugin.
	 * - Salesmate_Messenger_i18n. Defines internationalization functionality.
	 * - Salesmate_Messenger_Admin. Defines all hooks for the admin area.
	 * - Salesmate_Messenger_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-salesmate-messenger-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-salesmate-messenger-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-salesmate-messenger-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-salesmate-messenger-public.php';

		$this->loader = new Salesmate_Messenger_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Salesmate_Messenger_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Salesmate_Messenger_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Salesmate_Messenger_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Salesmate_Messenger_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}
	public function salesmate_messenger_add_plugin_page() {
		add_menu_page(
			'Salesmate Messenger', // page_title
			'Salesmate Messenger', // menu_title
			'manage_options', // capability
			'salesmate-messenger', // menu_slug
			array( $this, 'salesmate_messenger_create_admin_page' ), // function
			'dashicons-admin-comments', // icon_url
			60 // position
		);
	}

	public function salesmate_messenger_create_admin_page() {
		$this->salesmate_messenger_options = get_option( 'salesmate_messenger_option_name' ); ?>

		<div class="wrap">
			<h2>Salesmate Messenger</h2>
			<p>Please configure following details from the salesmate.</p>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'salesmate_messenger_option_group' );
					do_settings_sections( 'salesmate-messenger-admin' ); ?>
					<?php submit_button();
				?>
			</form>
		</div>
	<?php }

	public function salesmate_messenger_page_init() {
		register_setting(
			'salesmate_messenger_option_group', // option_group
			'salesmate_messenger_option_name', // option_name
			array( $this, 'salesmate_messenger_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'salesmate_messenger_setting_section', // id
			'Settings', // title
			array( $this, 'salesmate_messenger_section_info' ), // callback
			'salesmate-messenger-admin' // page
		);

		add_settings_field(
			'workspace_id_0', // id
			'Workspace ID', // title
			array( $this, 'workspace_id_0_callback' ), // callback
			'salesmate-messenger-admin', // page
			'salesmate_messenger_setting_section' // section
		);

		add_settings_field(
			'appkey_1', // id
			'Appkey', // title
			array( $this, 'appkey_1_callback' ), // callback
			'salesmate-messenger-admin', // page
			'salesmate_messenger_setting_section' // section
		);

		add_settings_field(
			'tenant_id_2', // id
			'Tenant ID', // title
			array( $this, 'tenant_id_2_callback' ), // callback
			'salesmate-messenger-admin', // page
			'salesmate_messenger_setting_section' // section
		);

		add_settings_field(
			'exclude_3', // id
			'Exclude ', // title
			array( $this, 'exclude_3_callback' ), // callback
			'salesmate-messenger-admin', // page
			'salesmate_messenger_setting_section' // section
		);

		add_settings_field(
			'hide_icon', // id
			'Hide Launcher Icon', // title
			array( $this, 'hide_icon_callback' ), // callback
			'salesmate-messenger-admin', // page
			'salesmate_messenger_setting_section' // section
		);
	}

	public function salesmate_messenger_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['workspace_id_0'] ) ) {
			$sanitary_values['workspace_id_0'] = sanitize_text_field( $input['workspace_id_0'] );
		}

		if ( isset( $input['appkey_1'] ) ) {
			$sanitary_values['appkey_1'] = sanitize_text_field( $input['appkey_1'] );
		}

		if ( isset( $input['tenant_id_2'] ) ) {
			$tenant_id = $input['tenant_id_2'];
			$tenant_id = trim($tenant_id, '/');
			$tenant_id = preg_replace( "#^[^:/.]*[:/]+#i", "", $tenant_id );
			$sanitary_values['tenant_id_2'] = sanitize_text_field( $tenant_id );
		}

		if ( isset( $input['exclude_3'] ) ) {
			$sanitary_values['exclude_3'] = sanitize_text_field( $input['exclude_3'] );
		}

		if ( isset( $input['hide_icon'] ) ) {
			$sanitary_values['hide_icon'] = sanitize_text_field( $input['hide_icon'] );
		}

		return $sanitary_values;
	}

	public function salesmate_messenger_section_info() {
		
	}

	public function workspace_id_0_callback() {
		printf(
			'<input class="regular-text" type="text" name="salesmate_messenger_option_name[workspace_id_0]" id="workspace_id_0" value="%s">',
			isset( $this->salesmate_messenger_options['workspace_id_0'] ) ? esc_attr( $this->salesmate_messenger_options['workspace_id_0']) : ''
		);
	}

	public function appkey_1_callback() {
		printf(
			'<input class="regular-text" type="text" name="salesmate_messenger_option_name[appkey_1]" id="appkey_1" value="%s">',
			isset( $this->salesmate_messenger_options['appkey_1'] ) ? esc_attr( $this->salesmate_messenger_options['appkey_1']) : ''
		);
	}

	public function tenant_id_2_callback() {
		printf(
			'<input class="regular-text" type="text" name="salesmate_messenger_option_name[tenant_id_2]" id="tenant_id_2" value="%s">',
			isset( $this->salesmate_messenger_options['tenant_id_2'] ) ? esc_attr( $this->salesmate_messenger_options['tenant_id_2']) : ''
		);
	}

	public function exclude_3_callback() {
		printf(
			'<input class="regular-text" type="text" name="salesmate_messenger_option_name[exclude_3]" id="exclude_3" value="%s"><p>Please add page id with comma seprated value in text box.</p>',
			isset( $this->salesmate_messenger_options['exclude_3'] ) ? esc_attr( $this->salesmate_messenger_options['exclude_3']) : ''
		);
	}

	public function hide_icon_callback() {
		$checked = "";
		if(isset($this->salesmate_messenger_options['hide_icon']) && $this->salesmate_messenger_options['hide_icon'] == 1){
			$checked = "checked";
		}
		printf(
			'<label class="checkbox_custom" for="hide_launcher"><input id="hide_launcher" class="regular-text" type="checkbox" name="salesmate_messenger_option_name[hide_icon]" id="hide_icon" value="1"'.$checked.'><p>This allows you to track your website visitors without having to chat with them.</p></label>'
		);
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0
	 * @return    Salesmate_Messenger_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
