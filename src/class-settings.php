<?php
/**
 * The Settings class
 *
 * @package MNML_Footnotes
 */

namespace MauroBringolf\MNML_Footnotes;

/**
 * Sets up settings relevant to this plugin and provides a method for retrieving them.
 */
class Settings {

	/**
	 * Stores all options for the title tag setting
	 *
	 * @var Array
	 */
	public $title_tag_options;

	/**
	 * Defines all valid title tags
	 */
	public function __construct() {
		$this->title_tag_options = array( 'strong', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'span', 'p' );
	}

	/**
	 * Registers all settings in one settings section on the default options page 'writing'.
	 */
	public function setup() {

		register_setting( 'writing', 'mbch_mnml_footnotes', array( $this, 'sanitize' ) );

		add_settings_section(
			'mnml-footnotes-settings',
			__( 'Footnotes', 'mnml-footnotes' ),
			array( $this, 'render_section' ),
			'writing'
		);

		add_settings_field(
			'title_text',
			__( 'Title text', 'mnml-footnotes' ),
			array( $this, 'title_text' ),
			'writing',
			'mnml-footnotes-settings'
		);

		add_settings_field(
			'title_tag',
			__( 'Title tag', 'mnml-footnotes' ),
			array( $this, 'title_tag' ),
			'writing',
			'mnml-footnotes-settings'
		);

	}

	/**
	 * Echoes the description text for the settings section.
	 */
	public function render_section() {
		?>
		<p><?php esc_html_e( 'Control the appearance of your footnotes on posts.', 'mnml-footnotes' ); ?></p>
		<?php
	}

	/**
	 * Echoes the markup for the title_text field.
	 */
	public function title_text() {

		$field = 'title_text';
		$value = $this->get_option( $field );
		?>

		<input type="text" class="regular-text" name="<?php echo esc_attr( "mbch_mnml_footnotes[$field]" ); ?>" placeholder="<?php esc_attr_e( 'Leave empty to disable...', 'mnml-footnotes' ); ?>" value="<?php echo esc_attr( $value ); ?>" />

		<?php
	}

	/**
	 * Echoes the markup for the title_tag field.
	 */
	public function title_tag() {

		$field = 'title_tag';
		$value = $this->get_option( $field );

		?>

		<select name=<?php echo esc_attr( "mbch_mnml_footnotes[$field]" ); ?> >
			<?php
			array_map(
				function( $value, $selected ) {
					?>
					<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, $selected ); ?> ><?php echo esc_html( $value ); ?></option>
					<?php
				},
				$this->title_tag_options,
				array_pad( array(), count( $this->title_tag_options ), $value )
			);
			?>
		</select>

		<?php

	}

	/**
	 * Retrieve options nested within the container setting of this plugin.
	 *
	 * This function checks whether the option is set and uses default values otherwise.
	 *
	 * @param String $id ID of the settings field to be retrieved.
	 * @return String
	 */
	public function get_option( $id ) {
		$options = get_option( 'mbch_mnml_footnotes' );

		$options = wp_parse_args(
			$options,
			array(
				'title_text' => '',
				'title_tag' => 'strong',
			)
		);

		return $options[ $id ];
	}

	/**
	 * Sanitizes all the settings field.
	 *
	 * @param Array $input Associative array of all the settings to be stored.
	 * @return Array
	 */
	public function sanitize( $input ) {

		$input['title_text'] = sanitize_text_field( $input['title_text'] );
		$input['title_tag'] = in_array( $input['title_tag'], $this->title_tag_options, true ) ? $input['title_tag'] : $this->get_option( 'title_tag' );

		return $input;

	}

}
