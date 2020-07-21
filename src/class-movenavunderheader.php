<?php
/**
 * The file that changes the navigation menu's position to be under the header
 *
 * @link       https://github.com/AgriLife/af4-masternaturalist/blob/master/src/class-movenavunderheader.php
 * @since      0.1.1
 * @package    af4-masternaturalist
 * @subpackage af4-masternaturalist/src
 */

namespace MasterNaturalist;

/**
 * The core plugin class
 *
 * @since 0.1.1
 * @return void
 */
class MoveNavUnderHeader {

	/**
	 * Initialize the class
	 *
	 * @since 0.1.1
	 * @return void
	 */
	public function __construct() {

		// Move nagitation to below the header.
		add_action( 'init', array( $this, 'init_nav_changes' ), 12 );
		add_filter( 'af4_top_bar_left_attr', array( $this, 'af4_top_bar_left_attr' ) );
		add_filter( 'af4_top_bar_attr', array( $this, 'add_grid_container_class' ) );
		add_filter( 'wp_nav_menu_args', array( $this, 'nav_menu_args' ) );
		add_filter( 'af4_primary_nav_class', array( $this, 'af4_primary_nav_class' ) );
		add_filter( 'genesis_attr_nav-primary', array( $this, 'attr_nav_primary' ) );

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
		add_action( 'genesis_header', 'genesis_do_nav', 12 );

		// Remove default mobile navigation menu toggle elements.
		if ( isset( $af_required ) ) {

			remove_filter( 'af4_before_nav', array( $af_required, 'af4_nav_primary_title_bar_open' ), 9 );
			remove_filter( 'af4_before_nav', array( $af_required, 'add_menu_toggle' ), 10 );
			remove_filter( 'af4_before_nav', array( $af_required, 'add_search_toggle' ), 11 );
			remove_filter( 'af4_before_nav', array( $af_required, 'af4_nav_primary_title_bar_close' ), 12 );
			add_filter( 'genesis_markup_title-area_close', array( $this, 'mobile_nav_toggle' ), 99, 2 );

		}

		// Move search widget right header widget area attached to the AgriFlex\RequiredDOM class.
		add_filter( 'af4_primary_nav_menu', array( $this, 'add_search_to_nav' ), 9 );

	}

	/**
	 * Change attributes for top bar left
	 *
	 * @since 0.1.1
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
	 * @since 0.1.1
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
	 * @since 0.1.1
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
	 * @since 0.1.1
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
	 * @since 0.1.1
	 * @param array $attributes HTML attributes.
	 * @return array
	 */
	public function attr_nav_primary( $attributes ) {

		$attributes['class'] = 'nav-p';
		return $attributes;

	}

	/**
	 * Add AgriFlex4 menu and nav primary toggles for mobile
	 *
	 * @since 0.1.1
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
	 * Add search field and toggle to primary navigation menu
	 *
	 * @since 0.1.1
	 * @param string $output Current output for primary navigation menu.
	 * @return string
	 */
	public function add_search_to_nav( $output ) {

		global $af_required;

		$search  = '<div class="title-bars cell medium-shrink title-bar-right">';
		$search .= '<div class="title-bar title-bar-search"><button class="search-icon hide-for-small-only" type="button" data-toggle="header-search"></button><div class="title-bar-title">Search</div><div class="nav-search-widget-area hide-for-medium" data-toggler=".hide-for-medium" id="header-search" data-toggler=".hide-for-medium">';
		$search .= get_search_form( array( 'echo' => false ) );
		$search .= '</div></div></div>';

		return $output . $search;

	}

}
