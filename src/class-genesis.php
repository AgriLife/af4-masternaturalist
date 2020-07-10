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

		global $af_genesis;

		remove_filter( 'genesis_seo_title', array( $af_genesis, 'add_logo' ), 10 );

		// Replace site title with logo.
		add_filter( 'genesis_seo_title', array( $this, 'add_logo' ), 10, 3 );

		// Modify header.
		add_filter( 'widget_display_callback', array( $this, 'change_header_icon_size' ), 11, 3 );
		add_filter( 'genesis_attr_title-area', array( $this, 'class_cell_title_area' ), 11 );
		add_filter( 'af4_nav_search_attr', array( $this, 'af4_nav_search_attr' ) );
		$active_widgets = wp_get_sidebars_widgets();
		if ( empty( $active_widgets['af4-header-right'] ) ) {
			add_action( 'genesis_header', array( $this, 'header_right_logos' ), 11 );
		} else {
			add_filter( 'af4_nav_search_widget_area_atts', array( $this, 'af4_header_right_logos' ) );
		}

		// Modify footer.
		add_filter( 'agrilife_footer_1_logo', '__return_empty_string' );

	}

	/**
	 * Return association logos.
	 *
	 * @since 0.1.2
	 * @return string
	 */
	private function get_header_assoc_logos() {

		$extension = sprintf(
			'<a href="%s" class="association-logo logo ext"><img src="%s%s"></a>',
			'https://agrilifeextension.tamu.edu/',
			MNAF4_DIR_URL,
			'images/extension-logo-white.png'
		);

		$tpw = sprintf(
			'<a href="%s" class="association-logo logo tpw"><img src="%s%s"></a>',
			'https://tpwd.texas.gov/',
			MNAF4_DIR_URL,
			'images/tpw-logo-white.png'
		);

		return $extension . $tpw;

	}

	/**
	 * Add logos to right side of header.
	 *
	 * @since 0.1.2
	 * @return void
	 */
	public function header_right_logos() {

		$output = sprintf(
			'<div class="cell hide-for-small-only medium-shrink">%s</div>',
			$this->get_header_assoc_logos()
		);

		echo wp_kses_post( $output );

	}

	/**
	 * Add logos to right side of header.
	 *
	 * @since 0.1.1
	 * @param array $attr An array of af4-header-right widget area attributes.
	 * @return array
	 */
	public function af4_header_right_logos( $attr ) {

		$attr['before'] .= $this->get_header_assoc_logos();
		$attr['before']  = str_replace( 'small-12', 'hide-for-small-only small-12', $attr['before'] );
		$attr['before']  = str_replace( ' id="header-search"', '', $attr['before'] );

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
	public function af4_nav_search_attr( $attributes ) {
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
			'<div class="logo"><a href="%s" title="%s"><img src="%s" alt="%s"><span class="h2 site-title-text">%s</span></a></div>',
			trailingslashit( home_url() ),
			get_bloginfo( 'name' ),
			MNAF4_DIR_URL . 'images/logo-white.png',
			get_bloginfo( 'name' ),
			preg_replace( '/<\/?a[^>]*>/', '', $inside )
		);

		$title = str_replace( $inside, $new_inside, $title );

		return $title;

	}

}
