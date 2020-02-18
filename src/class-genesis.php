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
			ALSAF4_DIR_URL . 'images/logo-white.png',
			get_bloginfo( 'name' )
		);

		$title = str_replace( $inside, $new_inside, $title );

		return $title;

	}
}
