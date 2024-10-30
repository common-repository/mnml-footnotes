<?php
/**
 * Main class of MNML Footnotes
 *
 * @package MNML_Footnotes
 */

namespace MauroBringolf\MNML_Footnotes;
require_once 'class-shortcodes.php';
require_once 'class-settings.php';

/**
 * Handles basic internal stuff and sets up hooks and filters for custom classes.
 */
final class Main {

	/**
	 * Stores the plugin instance
	 *
	 * @var Main
	 */
	public static $instance;

	/**
	 * Hopefully unique prefix for textdomain, html classes et cetera
	 *
	 * @var String
	 */
	private $prefix;

	/**
	 * Version number
	 *
	 * @var String
	 */
	private $version;

	/**
	 * Location of the main plugin file
	 *
	 * @var String
	 */
	private $file;

	/**
	 * Get the singleton instance. Makes sure that there will always be at most one of them.
	 *
	 * @param String $file Location of the main plugin file.
	 */
	public static function instance( $file ) {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Main ) ) {
			self::$instance = new Main( $file );
		}
		return self::$instance;
	}

	/**
	 * Singleton constructor. Sets up basic plugin information
	 *
	 * @param String $file Location of the main plugin file.
	 */
	private function __construct( $file ) {

		$this->prefix = 'mnml-footnotes';
		$this->version = '0.3.0';
		$this->file = $file;
		$this->settings = new Settings();
		$this->shortcodes = new Shortcodes( $this->settings );

	}

	/**
	 * Starts the plugin applying methods to the relevant actions and filters
	 */
	public function run() {
		add_filter( 'plugin_row_meta', array( $this, 'additional_admin_information_links' ), 10, 2 );
		add_filter( 'the_content', array( $this->shortcodes, 'autolist_footnotes' ), 12 );
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		add_action( 'init', array( $this, 'register_shortcodes' ) );
		add_action( 'admin_init', array( $this->settings, 'setup' ) );
		add_action( 'wp_head', array( $this->shortcodes, 'styles' ) );
	}

	/**
	 * Setup translations for textdomain defined as prefix to be in /lang directory
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'mnml-footnotes' , false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	}

	/**
	 * Adds the GitHub link to the plugin admin screen
	 *
	 * @param Array  $links Array of links to be rendered for this plugin.
	 * @param String $file  File identifiert of the plugin for the current row.
	 */
	function additional_admin_information_links( $links, $file ) {
		if ( plugin_basename( $this->file ) === $file ) {
			$links[] = '<a href="https://github.com/maurobringolf/mnml-footnotes">' . __( 'GitHub repository', 'mnml-footnotes' ) . '</a>';
		}
		return $links;
	}

	/**
	 * Registers shortcodes for footnotes
	 *
	 * @uses MauroBringolf\MNML_Footnotes\Shortcodes $this->shortcodes
	 */
	public function register_shortcodes() {
		add_shortcode( 'mnml_footnote', array( $this->shortcodes, 'footnote_reference' ) );
	}

}
