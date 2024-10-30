<?php
/**
 * PHP and WordPress version check
 *
 * @package MNML_Footnotes
 */

if ( ! class_exists( 'MNML_Version_Check' ) ) {

	/**
	 * Checks PHP and WP versions against provided minimal required ones and
	 * deactivates this plugin if they are not met.
	 */
	class MNML_Version_Check {


		/**
		 * Defines the minimal WordPress version required for this plugin
		 *
		 * @since 0.0.1
		 * @var   string
		 */
		private $wp;

		/**
		 * Defines the minimal php version required for this plugin
		 *
		 * @since 0.0.1
		 * @var   string
		 */
		private $php;

		/**
		 * Defines the plugin name
		 *
		 * @since 0.0.1
		 * @var   string
		 */
		private $name;

		/**
		 * Defies the plugin textdomain to translate the error messages
		 *
		 * @since 0.0.1
		 * @var   string
		 */
		private $textdomain;

		/**
		 * Path to the main plugin file for deactivation
		 *
		 * @since 0.0.1
		 * @var   string
		 */
		private $file;

		/**
		 * Sets up the plugin specifications
		 *
		 * @since 0.0.1
		 * @param array $specs Associative array defining all properties of this class.
		 */
		function __construct( $specs ) {
			$this->wp = $specs['wp'];
			$this->php = $specs['php'];
			$this->name = $specs['name'];
			$this->textdomain = $specs['textdomain'];
			$this->file = $specs['file'];
		}

		/**
		 * Checks whether versions are matched and makes the necessary calls otherwise
		 *
		 * @since 0.0.1
		 */
		public function check() {
			if ( version_compare( phpversion(), $this->php, '<=' ) ) {
				add_action( 'admin_notices', array( $this, 'php_failed' ), 100 );
			}

			if ( version_compare( get_bloginfo( 'version' ), $this->wp, '<=' ) ) {
				add_action( 'admin_notices', array( $this, 'wp_failed' ), 100 );
			}
		}

		/**
		 * Deactivates the plugin and hides the 'Plugin activated.' message.
		 *
		 * @since 0.0.1
		 */
		public function deactivate() {
			deactivate_plugins( plugin_basename( $this->file ) );

			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}
		}

		/**
		 * Outputs an error that the PHP version is too old
		 *
		 * @since 0.0.1
		 */
		public function php_failed() {
			$this->deactivate();
			?>

			<div class="error">
				<p><?php printf( '%s requires at least PHP version %s. Please consider upgrading.', esc_html( $this->name ), esc_html( $this->wp ) ); ?></p>
			</div>

			<?php
		}

		/**
		 * Outputs and error that the WordPress version is too old
		 *
		 * @since 0.0.1
		 */
		public function wp_failed() {
			$this->deactivate();
			?>

			<div class="error">
				<p><?php printf( '%s requires at least WordPress version %s. Please consider upgrading.', esc_html( $this->name ), esc_html( $this->wp ) ); ?></p>
			</div>

			<?php
		}
	}

}// End if().
