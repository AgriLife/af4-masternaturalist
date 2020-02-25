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

		// Modify header.
		add_filter( 'widget_display_callback', array( $this, 'change_header_icon_size' ), 11, 3 );
		add_filter( 'genesis_attr_title-area', array( $this, 'class_cell_title_area' ), 11 );
		add_filter( 'af4_header_right_attr', array( $this, 'af4_header_right_attr' ) );
		$active_widgets = wp_get_sidebars_widgets();
		if ( empty( $active_widgets['af4-header-right'] ) ) {
			add_action( 'genesis_header', array( $this, 'header_right_logos' ), 11 );
		} else {
			add_filter( 'af4_header_right_widget_area_atts', array( $this, 'af4_header_right_logos' ) );
		}

		// Move nagitation to below the header.
		add_action( 'init', array( $this, 'init_nav_changes' ), 12 );
		add_filter( 'af4_top_bar_left_attr', array( $this, 'af4_top_bar_left_attr' ) );
		add_filter( 'af4_top_bar_attr', array( $this, 'add_grid_container_class' ) );
		add_filter( 'wp_nav_menu_args', array( $this, 'nav_menu_args' ) );
		add_filter( 'af4_primary_nav_class', array( $this, 'af4_primary_nav_class' ) );
		add_filter( 'genesis_attr_nav-primary', array( $this, 'attr_nav_primary' ) );

		// Modify footer.
		add_filter( 'agrilife_footer_1_logo', '__return_empty_string' );

	}

	/**
	 * Init
	 *
	 * @since 0.1.1
	 * @return void
	 */
	public function init_nav_changes() {

		global $af_required;

		// Move navigation menu to after the header structural wrap but within the sticky container.
		remove_action( 'genesis_header', 'genesis_do_nav', 10 );
		add_action( 'genesis_structural_wrap-header', array( $this, 'genesis_do_nav' ), 16 );

		// Remove default mobile navigation menu toggle elements.
		remove_filter( 'af4_before_nav', array( $af_required, 'af4_nav_primary_title_bar_open' ), 9 );
		remove_filter( 'af4_before_nav', array( $af_required, 'add_menu_toggle' ), 10 );
		remove_filter( 'af4_before_nav', array( $af_required, 'add_search_toggle' ), 11 );
		remove_filter( 'af4_before_nav', array( $af_required, 'af4_nav_primary_title_bar_close' ), 12 );
		add_filter( 'genesis_markup_title-area_close', array( $this, 'mobile_nav_toggle' ), 99, 2 );

		// Move search widget right header widget area attached to the AgriFlex\RequiredDOM class.
		add_filter(
			'af4_primary_nav_menu',
			function( $output ) {

				global $af_required;

				$search  = '<div class="title-bars cell medium-shrink title-bar-right">';
				$search .= '<div class="title-bar title-bar-search"><button class="search-icon" type="button" data-toggle="header-search"></button><div class="title-bar-title">Search</div><div class="nav-search-widget-area hide-for-medium" data-toggler=".hide-for-medium" id="header-search" data-toggler=".hide-for-medium">';
				$search .= get_search_form( array( 'echo' => false ) );
				$search .= '</div></div></div>';

				return $output . $search;

			},
			9
		);

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
	 * Add AgriFlex4 menu and nav primary toggles for mobile
	 *
	 * @since 0.1.0
	 * @param string $output Current output for Genesis title area open element.
	 * @param array  $args Arguments for Genesis title area open element.
	 * @return string
	 */
	public function mobile_nav_toggle( $output, $args ) {

		global $af_required;

		$open_m  = str_replace( 'small-6', 'shrink', $af_required->af4_nav_primary_title_bar_open() );
		$open_m  = str_replace( 'title-bar-right', 'title-bar-left', $open_m );
		$menu_m  = $af_required->add_menu_toggle();
		$menu_m  = str_replace( '<div class="title-bar-title" data-toggle="nav-menu-primary">Menu</div>', '', $menu_m );
		$close_m = $af_required->af4_nav_primary_title_bar_close();
		$m       = $open_m . $menu_m . $close_m;

		if ( ! empty( $args['close'] ) ) {

			$output .= $m;

		}

		return $output;

	}

	/**
	 * Add nav menu in grid container
	 *
	 * @since 0.1.0
	 * @param string $output Output for the site header wrap.
	 * @return string
	 */
	public function genesis_do_nav( $output ) {

		ob_start();
		genesis_do_nav();
		$nav = ob_get_contents();
		ob_end_clean();

		$output = preg_replace( '/<\/div><\/div><\/div>$/', '</div>' . $nav . '</div></div>', $output );

		return $output;

	}

	/**
	 * Change attributes for top bar left
	 *
	 * @since 0.1.0
	 * @param array $attributes HTML attributes.
	 * @return array
	 */
	public function af4_top_bar_left_attr( $attributes ) {
		$attributes['class'] .= ' grid-x grid-padding-x';
		return $attributes;
	}

	/**
	 * Add grid container class to element.
	 *
	 * @since 0.1.0
	 * @param array $attributes Element html attributes.
	 * @return array
	 */
	public function add_grid_container_class( $attributes ) {

		$attributes['class'] .= ' grid-container';
		return $attributes;

	}

	/**
	 * Change class for primary nav menu
	 *
	 * @since 0.1.0
	 * @param array $args Arguments for menu.
	 * @return array
	 */
	public function nav_menu_args( $args ) {

		if ( 'primary' === $args['theme_location'] ) {

			$args['menu_class'] .= ' cell medium-auto';

		}

		return $args;

	}

	/**
	 * Replace Foundation class in primary nav menu
	 *
	 * @since 0.1.0
	 * @param array $class Array of classes for AgriFlex4 primary nav menu.
	 * @return array
	 */
	public function af4_primary_nav_class( $class ) {

		$key1 = array_search( 'medium-auto', $class, true );
		$key2 = array_search( 'small-12', $class, true );
		$key3 = array_search( 'cell', $class, true );

		unset( $class[ $key1 ] );
		unset( $class[ $key2 ] );
		unset( $class[ $key3 ] );

		return $class;

	}

	/**
	 * Add header nav primary cell class names
	 *
	 * @since 0.1.0
	 * @param array $attributes HTML attributes.
	 * @return array
	 */
	public function attr_nav_primary( $attributes ) {

		$attributes['class'] = 'nav-p';
		return $attributes;

	}

}
