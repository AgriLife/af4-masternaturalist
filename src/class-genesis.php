<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/AgriLife/af4-masternaturalist/blob/master/src/class-genesis.php
 * @since      0.1.0
 * @package    af4-masternaturalist
 * @subpackage af4-masternaturalist/src
 */

namespace MasterNaturalist;

/**
 * The core plugin class
 *
 * @since 0.1.0
 * @return void
 */
class Genesis {

	/**
	 * Initialize the class
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function __construct() {

		// Replace site title with logo.
		add_filter( 'genesis_seo_title', array( $this, 'add_logo' ), 10, 3 );

		add_action( 'init', array( $this, 'init' ) );

		// Modify header.
		add_filter( 'widget_display_callback', array( $this, 'change_header_icon_size' ), 11, 3 );
		add_filter( 'genesis_attr_title-area', array( $this, 'class_cell_title_area' ), 11 );
		add_filter( 'af4_header_right_attr', array( $this, 'af4_header_right_attr' ) );
		add_filter( 'af4_header_right_widget_area_atts', array( $this, 'af4_header_right_logos' ) );

		// Modify footer.
		add_filter( 'genesis_structural_wrap-footer', array( $this, 'class_footer_wrap' ), 12 );
		add_action( 'genesis_footer', array( $this, 'genesis_footer_widget_area' ), 7 );
		add_action( 'genesis_footer', array( $this, 'add_copyright' ), 9 );

	}

	/**
	 * Add logos to right side of header.
	 *
	 * @since 0.1.1
	 * @param array $attr An array of af4-header-right widget area attributes.
	 * @return array
	 */
	public function af4_header_right_logos( $attr ){

		$extension = sprintf(
			'<a href="%s" class="logo ext"><img src="%s%s"></a>',
			'https://agrilifeextension.tamu.edu/',
			MNAF4_DIR_URL,
			'images/extension-logo-white.png'
		);

		$tpw = sprintf(
			'<a href="%s" class="logo tpw"><img src="%s%s"></a>',
			'https://tpwd.texas.gov/',
			MNAF4_DIR_URL,
			'images/tpw-logo-white.png'
		);

		$attr['before'] .= $extension . $tpw;

		return $attr;

	}

	/**
	 * Filters the settings for a particular widget instance.
	 *
	 * @since 0.1.1
	 * @param array     $instance The current widget instance's settings.
	 * @param WP_Widget $obj      The current widget instance.
	 * @param array     $args     An array of default widget arguments.
	 * @return array
	 */
	public function change_header_icon_size( $instance, $obj, $args ) {

		if ( 'af4-header-right' === $args['id'] ) {
			$instance['icon_size'] = 26;
		}

		return $instance;

	}

	/**
	 * Initialize the various classes
	 *
	 * @since 0.1.1
	 * @return void
	 */
	public function init() {

		global $af_required;

		// Custom footer.
		remove_action( 'genesis_footer', array( $af_required, 'render_tamus_logo' ), 10 );

	}

	/**
	 * Change header title area cell class names
	 *
	 * @since 0.1.1
	 * @param array $attributes HTML attributes.
	 * @return array
	 */
	public function class_cell_title_area( $attributes ) {
		$attributes['class'] = str_replace( 'medium-shrink', 'medium-auto', $attributes['class'] );
		return $attributes;
	}

	/**
	 * Change attributes for header right widget area
	 *
	 * @since 0.1.1
	 * @param array $attributes HTML attributes.
	 * @return array
	 */
	public function af4_header_right_attr( $attributes ) {
		$attributes['class'] = str_replace( 'medium-3', 'medium-shrink', $attributes['class'] );
		return $attributes;
	}

	/**
	 * Initialize the class
	 *
	 * @since 0.1.0
	 * @param string $title Genesis SEO title html.
	 * @param string $inside The inner HTML of the title.
	 * @param string $wrap The tag name of the seo title wrap element.
	 * @return string
	 */
	public function add_logo( $title, $inside, $wrap ) {

		$new_inside = sprintf(
			'<div class="logo"><a href="%s" title="%s"><img src="%s" alt="%s"></a></div>',
			trailingslashit( home_url() ),
			get_bloginfo( 'name' ),
			MNAF4_DIR_URL . 'images/logo-white.png',
			get_bloginfo( 'name' )
		);

		$title = str_replace( $inside, $new_inside, $title );

		return $title;

	}

	/**
	 * Change footer wrap class names
	 *
	 * @since 0.1.1
	 * @param string $output The wrap HTML.
	 * @return string
	 */
	public function class_footer_wrap( $output ) {

		$output = preg_replace( '/\s?grid-container\s?/', ' ', $output );
		$output = preg_replace( '/\s?grid-x\s?/', ' ', $output );
		$output = preg_replace( '/\s?grid-padding-x\s?/', ' ', $output );
		$output = preg_replace( '/class=" /', 'class="', $output );

		return $output;
	}

	/**
	 * Add footer widget areas
	 *
	 * @since 0.1.1
	 * @return void
	 */
	public function genesis_footer_widget_area() {

		echo '<div class="footer-info grid-container"><div class="grid-x grid-padding-x">';

		genesis_widget_area(
			'footer-right',
			array(
				'before' => '',
				'after'  => '',
			)
		);

		echo '<div class="cell medium-order-1 medium-6 small-12"><div class="grid-x">';

		genesis_widget_area(
			'footer-left',
			array(
				'before' => '',
				'after'  => '',
			)
		);

		echo '</div></div></div></div>';

	}

	/**
	 * Add copyright notice
	 *
	 * @since 0.1.1
	 * @return void
	 */
	public function add_copyright() {

		echo wp_kses_post( '<p class="center">&copy; ' . date( 'Y' ) . ' Texas A&amp;M University. All rights reserved.</p>' );

	}
}
