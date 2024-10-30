<?php
/**
 * The Shortcode class
 *
 * @package MNML_Footnotes
 */

namespace MauroBringolf\MNML_Footnotes;

/**
 * Provides render methods for a footnote reference and a footnote list
 */
class Shortcodes {

	/**
	 * Stores all footnotes for each post
	 *
	 * @var Array
	 */
	private $footnotes;

	/**
	 * Sets up the array storing footnotes for each posts
	 *
	 * @param Settings $settings Reference to the settings instance used.
	 */
	public function __construct( Settings $settings ) {
		$this->settings = $settings;
		$this->footnotes = array();
	}

	/**
	 * Echoes inline styles for the footnote references.
	 */
	public function styles() {
		?>
		<style>
		/*.mbch-mnml-footnote {
			position: relative;
			bottom: 0.5em;
			font-size: 0.8em;
		}*/
		</style>
		<?php
	}

	/**
	 * Returns markup for a footnote reference
	 *
	 * @param Array  $atts    Attributes passed into the shortcode.
	 * @param String $content Content passed into the shortcode.
	 */
	public function footnote_reference( $atts, $content ) {

		global $post;

		if ( $post && $post instanceof \WP_Post ) {

			$id = $post->ID;

			if ( ! in_array( $id, array_keys( $this->footnotes ), true ) ) {
				$this->footnotes[ $post->ID ] = array();
			}

			$content = trim( $content );

			$key = array_search( $content, $this->footnotes[ $id ], true );

			if ( false !== $key ) {
				$index = $key + 1;
			} else {
				array_push( $this->footnotes[ $id ], $content );
				$index = count( $this->footnotes[ $id ] );
			}

			return '<sup><a href="#mbch_mnml_footnotes-' . $id . '">' . $index . '</a></sup>';

		}

	}

	/**
	 * Returns markup for a complete footnote list
	 *
	 * @return String
	 */
	public function footnote_list() {

		global $post;

		if ( $post && $post instanceof \WP_Post ) {

			$footnotes = isset( $this->footnotes[ $post->ID ] ) ? $this->footnotes[ $post->ID ] : false;

			if ( $footnotes ) {

				$html = '<div id="mbch_mnml_footnotes-' . $post->ID . '">' . $this->create_title() . '<ol>';

				// Manual closure $this binding for PHP < 5.4.
				$that = $this;

				array_map( function( $note ) use ( &$html, $that ) {

					$is_url = filter_var( $note, FILTER_VALIDATE_URL ) !== false;

					$text = $is_url ? $that->create_footnote_anchor_tag( $note ) : $note;

					$filtered_text = apply_filters( 'mnml_footnote_text', $text );

					$html .= "<li>$filtered_text</li>";
				}, $footnotes);

				$html .= '</ol></div>';

				return $html;

			}
		}

		return '';

	}

	/**
	 * Wraps a link into an anchor tag.
	 *
	 * @param String $url The link to be wrapped.
	 * @return String
	 */
	public function create_footnote_anchor_tag( $url ) {
		return "<a href=\"$url\" target=\"_blank\">$url</a>";
	}

	/**
	 * Creates the footnotes title markup based on settings.
	 *
	 * @return String
	 */
	public function create_title() {

		$title_text = $this->settings->get_option( 'title_text' );
		$title_tag = $this->settings->get_option( 'title_tag' );

		if ( strlen( $title_text ) > 0 ) {
			return "<$title_tag>$title_text</$title_tag>";
		}

		return '';

	}

	/**
	 * Adds a list of footnotes to the post if not done manually using the shortcode.
	 *
	 * @param String $content The post content to be filtered.
	 */
	public function autolist_footnotes( $content ) {

		global $post;

		if ( $post && $post instanceof \WP_Post ) {
			if ( isset( $this->footnotes[ $post->ID ] ) && $this->footnotes[ $post->ID ] ) {
				return $content . $this->footnote_list();
			}
		}

		return $content;

	}



}
