<?php
/**
 * Master Naturalist - AgriFlex4
 *
 * @package      af4-masternaturalist
 * @author       Zachary Watkins
 * @copyright    2019 Texas A&M AgriLife Communications
 * @license      GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name:  Master Naturalist - AgriFlex4
 * Plugin URI:   https://github.com/AgriLife/af4-masternaturalist
 * Description:  A plugin for Master Naturalist websites on the AgriFlex4 theme.
 * Version:      0.1.0
 * Author:       Zachary Watkins
 * Author URI:   https://github.com/ZachWatkins
 * Author Email: zachary.watkins@ag.tamu.edu
 * Text Domain:  af4-masternaturalist
 * License:      GPL-2.0+
 * License URI:  http://www.gnu.org/licenses/gpl-2.0.txt
 */

/* Define some useful constants */
define( 'MNAF4_DIRNAME', 'af4-masternaturalist' );
define( 'MNAF4_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'MNAF4_DIR_FILE', __FILE__ );
define( 'MNAF4_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'MNAF4_TEXTDOMAIN', 'af4-masternaturalist' );
define( 'MNAF4_TEMPLATE_PATH', MNAF4_DIR_PATH . 'templates' );

/**
 * The core plugin class that is used to initialize the plugin
 */
require MNAF4_DIR_PATH . 'src/class-masternaturalist.php';
spl_autoload_register( 'MasterNaturalist::autoload' );
MasterNaturalist::get_instance();

/* Activation hooks */
register_activation_hook( __FILE__, 'masternaturalist_activation' );

/**
 * Helper option flag to indicate rewrite rules need flushing
 *
 * @since 0.1.0
 * @return void
 */
function masternaturalist_activation() {

	// Check for missing dependencies.
	$theme = wp_get_theme();
	if ( 'AgriFlex4' !== $theme->name ) {
		$error = sprintf(
			/* translators: %s: URL for plugins dashboard page */
			__(
				'Plugin NOT activated: The <strong>MasterNaturalist - AgriFlex4</strong> plugin needs the <strong>AgriFlex4</strong> theme to be installed and activated first. <a href="%s">Back to plugins page</a>',
				'af4-masternaturalist'
			),
			get_admin_url( null, '/plugins.php' )
		);
		wp_die( wp_kses_post( $error ) );
	}

}
