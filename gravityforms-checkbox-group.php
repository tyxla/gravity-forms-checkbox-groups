<?php
/**
 * Plugin Name: Gravity Forms: Checkbox Group
 * Plugin URI: https://github.com/tyxla/gravity-forms-checkbox-groups
 * Description: Provides the option to add groups to your select checkbox fields in Gravity Forms. Requires the Gravity Forms plugin to be activated.
 * Version: 1.0
 * Author: Marin Atanasov
 * Author URI: http://marinatanasov.com/
 * Tested up to: 4.4.1
 * License: GPL2
 */

// main plugin constants
define('GFCB_PLUGIN_NAME', 'Gravity Forms: Checkbox Group');
define('GFCB_PLUGIN_VERSION', '1.0');
define('GFCB_PLUGIN_DIRNAME', basename(dirname(__FILE__)));
define('GFCB_PLUGIN_URL', WP_PLUGIN_URL . '/' . GFCB_PLUGIN_DIRNAME);
define('GFCB_PLUGIN_DIR', WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . GFCB_PLUGIN_DIRNAME);
define('GFCB_PLUGIN_INCLUDES_DIR', GFCB_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR);

// main plugin class
include_once(GFCB_PLUGIN_INCLUDES_DIR . 'class.Gravity-Forms-Checkbox-Group.php');

// initializing the plugin
$gfcb = Gravity_Forms_Checkbox_Group::instance();

?>