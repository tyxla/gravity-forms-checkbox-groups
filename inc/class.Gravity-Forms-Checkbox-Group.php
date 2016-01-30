<?php
/**
 * Gravity Forms Checkbox Group - main class
 *
 * The main plugin class, used to install & setup everything
 */

final class Gravity_Forms_Checkbox_Group {
    /**
     * Instance container
	 *
	 * @var object
	 */
	static $instance = null;

    /**
     * Private constructor so nobody else can instance it
     *
     */
    private function __construct() {

    }

	/**
	 * Fetch/create the singleton instance
	 *
	 * @return object $instance
	 */
    public static function instance() {
        if (self::$instance === null) {
            self::$instance = new self();
    		add_action('admin_menu', array(self::instance(), 'init'), 30);
			add_filter('gform_field_content', array(self::instance(), 'gform_field_content'), 10, 5);
        }
        return self::$instance;
    }

	/**
	 * Initializing everything
	 *
	 * @return void
	 */
	function init() {
		// getting the GFCB instance
		$gfcb = self::instance();

		// if Gravity Forms is not activated, we should remain silent
		if (!class_exists('RGForms')) {
			return;
		}

		// scripts & styles
		add_action('admin_print_scripts', array($gfcb, 'config'), 1);
		add_action('admin_enqueue_scripts', array($gfcb, 'enqueue_scripts'));
		add_action('admin_enqueue_scripts', array($gfcb, 'enqueue_styles'));
		add_filter('gform_noconflict_scripts', array($gfcb, 'register_safe_scripts') );
		add_filter('gform_noconflict_styles', array($gfcb, 'register_safe_styles') );
	}

	/**
	 * Scripts configuration
	 *
	 * @return void
	 */	
	function config() {
		?>
		<script type="text/javascript">
			window.gfcb_plugin_url = '<?php echo GFCB_PLUGIN_URL; ?>';
		</script>
		<?php
	}

	/**
	 * Enqueue necessary scripts
	 *
	 * @return void
	 */
	function enqueue_scripts() {
		// jquery
		wp_enqueue_script('jquery');

		// init & config
		wp_enqueue_script('gfcb_plugin_main_js', GFCB_PLUGIN_URL . '/js/main.js', array('jquery'), GFCB_PLUGIN_VERSION);
	}

	/**
	 * Enqueue necessary styles
	 *
	 * @return void
	 */
	function enqueue_styles() {
		wp_enqueue_style('gfcb_plugin_main_css', GFCB_PLUGIN_URL . '/css/main.css', array(), GFCB_PLUGIN_VERSION);
	}

	/**
	 * Registering the scripts with Gravity Forms so that they get enqueued when running on no-conflict mode
	 *
	 * @return $scripts array of registered scripts
	 */
	function register_safe_scripts( $scripts ){
	    $scripts[] = "gfcb_plugin_main_js";
	    return $scripts;
	}

	/**
	 * Registering the styles with Gravity Forms so that they get enqueued when running on no-conflict mode
	 *
	 * @return $styles array of registered styles
	 */
	function register_safe_styles( $styles ){
	    $styles[] = "gfcb_plugin_main_css";
	    return $styles;
	}

	/**
	 * Where the magic happens - creating the groups from the target options
	 *
	 * @param string $field_content, the content of the field
	 * @param object $field, the field object
	 * @param string $value, the current field value 
	 * @param int $lead_id, the ID of the entry
	 * @param int $form_id, the ID of the form
	 * @return string the replaced content
	 */
	function gform_field_content($field_content, $field, $value, $lead_id, $form_id) {
		$gfcb = self::instance();

		// only checkbox are supported
		if ($field['type'] == 'checkbox') {

			// checking if we have groups in our dropdown field
			$has_groups = false;
			foreach ($field['choices'] as $key => $choice) {
				if (!empty($choice['isCheckboxGroup'])) {
					$has_groups = true;
					break;
				}
			}

			// if we have groups, proceeding with the magic
			if ($has_groups) {
				foreach ($field['choices'] as $key => $choice) {
					if (!empty($choice['isCheckboxGroup'])) {
						
						// processing in admin
						$search = "~(<li class='gchoice_" . $form_id . "_" . $field['id'] . "_" . ($key + 1) . "'>).*?(</li>)~s";
						$replace = "$1<em class='checkbox-group'>" . esc_attr($choice['text']) . "</em>$2";
						$field_content = preg_replace($search, $replace, $field_content);

						// processing in frontend
						$search = "~(<li class='gchoice_" . $field['id'] . "_" . ($key + 1) . "'>).*?(</li>)~s";
						$replace = "$1<em class='checkbox-group'>" . esc_attr($choice['text']) . "</em>$2";
						$field_content = preg_replace($search, $replace, $field_content);

					}
				}
			}

		}
		return $field_content;
	}

}

?>