<?php

/**
 * The plugin 
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.salesmate.io
 * @since             1.0
 * @package           Salesmate_Messenger
 *
 * @wordpress-plugin
 * Plugin Name:       Salesmate Messenger
 * Plugin URI:        
 * Description:       Allows you to configure messenger in easy steps.
 * Version:           1.1
 * Author:            Salesmate
 * Author URI:        https://www.salesmate.io
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       salesmate-messenger
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SALESMATE_MESSENGER_VERSION', '1.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-salesmate-messenger-activator.php
 */
function activate_salesmate_messenger() {
	
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-salesmate-messenger-deactivator.php
 */
function deactivate_salesmate_messenger() {
	
}

register_activation_hook( __FILE__, 'activate_salesmate_messenger' );
register_deactivation_hook( __FILE__, 'deactivate_salesmate_messenger' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-salesmate-messenger.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0
 */
function run_salesmate_messenger() {

	$plugin = new Salesmate_Messenger();
	$plugin->run();
	add_action( 'wp_footer', 'add_salesmate_messenger');

}
function add_salesmate_messenger() {
	
	$salesmate_messenger_options = get_option( 'salesmate_messenger_option_name' ); // Array of All Options
	$workspace_id_0 = $salesmate_messenger_options['workspace_id_0']; // Workspace ID
	$appkey_1 = $salesmate_messenger_options['appkey_1']; // Appkey
	$tenant_id_2 = $salesmate_messenger_options['tenant_id_2']; // Tenant ID
	$exclude_3 = $salesmate_messenger_options['exclude_3']; // Exclude 
	$hide_icon_4 = $salesmate_messenger_options['hide_icon']; // Hide Icon 
	$hide_icon = false;
	
	//creating exclude's array 
	$exclude_array = array();
	$exclude_array = explode(',',$exclude_3);
	
	if($workspace_id_0 != "" && $appkey_1 != "" && $tenant_id_2 != "" ){
		if(!is_page($exclude_array)){
			if($hide_icon_4 == 1){
				$hide_icon = true;
			}
	?>
	<script>
			window.salesmateSettings = {
				workspace_id: "<?php esc_html_e( $workspace_id_0 ); ?>",	
				app_key: "<?php esc_html_e( $appkey_1 ); ?>",
				tenant_id: "<?php esc_html_e( $tenant_id_2 ); ?>",
				hide_default_launcher: "<?php esc_html_e( $hide_icon ); ?>"
			}
	</script>
	
	<script>
			! function(e, t, a, i, d, n, o) {
				e.Widget = i, e[i] = e[i] || function() {
					(e[i].q = e[i].q || []).push(arguments)
				}, n = t.createElement(a), o = t.getElementsByTagName(a)[0], n.id = i, n.src = d, window._salesmate_widget_script_url = d, n.async = 1, o.parentNode.insertBefore(n, o)
			}(window, document, "script", "loadwidget", "https://<?php esc_html_e( $tenant_id_2 ); ?>/messenger-platform/messenger-platform-main.js"), loadwidget("init", {}), loadwidget("load_widget", "Widget Loading...!");
		</script>
	<?php
		}
	}
	}
	run_salesmate_messenger();
