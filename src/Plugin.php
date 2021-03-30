<?php
namespace WordPress_Modern_Images;

/**
 * Main class for the plugin.
 *
 * @since 1.0.0
 */
class Plugin {

	/**
	 * Absolute path to the plugin main file.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $main_file;

	/**
	 * The setting instance.
	 *
	 * @since 1.0.0
	 * @var Setting
	 */
	protected $setting;

	/**
	 * Main instance of the plugin.
	 *
	 * @since 1.0.0
	 * @var Plugin|null
	 */
	protected static $instance = null;

	/**
	 * Admin Bar.
	 *
	 * @since 1.0.0
	 * @var Amin_Bar|null
	 */
	protected $admin_bar = null;

	/**
	 * Rest Route.
	 *
	 * @since 1.0.0
	 * @var Rest_Route|null
	 */
	protected $rest_route = null;

	/**
	 * Updater.
	 *
	 * @since 1.0.0
	 * @var Updater|null
	 */
	protected $updater = null;

	/**
	 * Sets the plugin main file.
	 *
	 * @since 1.0.0
	 *
	 * @param string $main_file Absolute path to the plugin main file.
	 */
	public function __construct( $main_file ) {
		$this->setting    = new Setting();
	}

	/**
	 * Registers the plugin with WordPress.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		$this->setting->register();

		// Filter the default output format.
		$format = $this->setting->get();
		if ( ! empty( $format ) && isset( $format['modern_image_output_format'] ) ) {
			add_filter( 'wp_image_editor_output_format', function( $formats ) {
				$formats['image/jpeg'] = $format['modern_image_output_format'];

				return $formats;
			} );
		}
	}

	/**
	 * Gets the plugin basename, which consists of the plugin directory name and main file name.
	 *
	 * @since 1.0.0
	 *
	 * @return string Plugin basename.
	 */
	public function basename() {
		return plugin_basename( $this->main_file );
	}

	/**
	 * Gets the absolute path for a path relative to the plugin directory.
	 *
	 * @since 1.0.0
	 *
	 * @param string $relative_path Optional. Relative path. Default '/'.
	 * @return string Absolute path.
	 */
	public function path( $relative_path = '/' ) {
		return plugin_dir_path( $this->main_file ) . ltrim( $relative_path, '/' );
	}

	/**
	 * Gets the full URL for a path relative to the plugin directory.
	 *
	 * @since 1.0.0
	 *
	 * @param string $relative_path Optional. Relative path. Default '/'.
	 * @return string Full URL.
	 */
	public function url( $relative_path = '/' ) {
		return plugin_dir_url( $this->main_file ) . ltrim( $relative_path, '/' );
	}

	/**
	 * Retrieves the main instance of the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return Plugin Plugin main instance.
	 */
	public static function instance() {
		return static::$instance;
	}

	/**
	 * Loads the plugin main instance and initializes it.
	 *
	 * @since 1.0.0
	 *
	 * @param string $main_file Absolute path to the plugin main file.
	 * @return bool True if the plugin main instance could be loaded, false otherwise.
	 */
	public static function load( $main_file ) {
		if ( null !== static::$instance ) {
			return false;
		}

		static::$instance = new static( $main_file );
		static::$instance->register();

		return true;
	}
}