<?php

class WSUWP_Extend_WP_SEO {
	/**
	 * @var WSUWP_Extend_WP_SEO
	 */
	private static $instance;

	/**
	 * Maintain and return the one instance. Initiate hooks when
	 * called the first time.
	 *
	 * @since 0.0.1
	 *
	 * @return \WSUWP_Plugin_Skeleton
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new WSUWP_Extend_WP_SEO();
			self::$instance->setup_hooks();
		}
		return self::$instance;
	}

	/**
	 * Setup hooks to include.
	 *
	 * @since 0.0.1
	 */
	public function setup_hooks() {
		add_action( 'init', array( $this, 'remove_wpseo_title_filters' ) );
		add_action( 'admin_menu', array( $this, 'remove_wpseo_titles_page' ), 999 );
		add_action( 'admin_enqueue_scripts', array( $this, 'wpseo_metabox' ) );
		add_filter( 'option_wpseo_titles', array( $this, 'wpseo_title_options' ) );
		add_filter( 'pre_update_option_wpseo_titles', array( $this, 'wpseo_title_options' ) );
		add_filter( 'wpseo_opengraph_title', array( $this, 'meta_titles_filter' ) );
		add_filter( 'wpseo_twitter_title', array( $this, 'meta_titles_filter' ) );
		add_filter( 'pre_get_document_title', array( $this, 'meta_titles_filter' ) );
		add_filter( 'spine_get_title', array( $this, 'spine_get_title_filter' ), 10, 4 );
	}

	/**
	 * Remove title filters.
	 */
	public function remove_wpseo_title_filters() {
		remove_filter( 'pre_get_document_title', array( WPSEO_Frontend::get_instance(), 'title' ), 15 );
		remove_filter( 'wp_title', array( WPSEO_Frontend::get_instance(), 'title' ), 15 );
	}

	/**
	 * Remove `Titles & Metas` from the menu.
	 */
	public function remove_wpseo_titles_page() {
		$page = remove_submenu_page( 'wpseo_dashboard', 'wpseo_titles' );
	}

	/**
	 * Enqueue script for modifying the SEO metabox for post types.
	 */
	function wpseo_metabox( $hook ) {
		if ( ! in_array( $hook, array( 'edit.php', 'post.php', 'post-new.php' ) ) ) {
			return;
		}

		wp_enqueue_script( 'spine-wpseo-mb', plugins_url( '/js/wsu-wpseo-metabox.js', __FILE__ ), array('jquery'), '0.1', true );
	}

	/**
	 * Override the options from the 'Titles & Metas' page.
	 *
	 * @param array $options Default option values.
	 *
	 * @return array Modified option values.
	 */
	public function wpseo_title_options( $options ) {
		$site_part = get_option( 'blogname' );
		$global_part = ' | Washington State University';
		$article_title = '%%title%% | ' . $site_part . $global_part;
		$taxonomies = get_taxonomies();

		$options['forcerewritetitle'] = '';
		// get_post_types() here?
		$options['title-post'] = $article_title;
		$options['title-page'] = $article_title;
		$options['title-attachment'] = $article_title;

		if ( $taxonomies ) {
			foreach ( $taxonomies as $taxonomy ) {
				$options[ 'hideeditbox-tax-' . $taxonomy ] = true;
			}
		}

		return $options;
	}

	/**
	 * Filter OG and Twitter title meta tag values and get_document_title function.
	 */
	public function meta_titles_filter() {
		return spine_get_title();
	}

	/**
	 * Filter the Spine title function.
	 *
	 * @param string $title       Potentially already rendered title.
	 * @param string $site_part   `get_option( 'blogname' )`.
	 * @param string $global_part ` | Washington State University`.
	 * @param string $view_title  `wp_title( '|', false, 'right' )`.
	 *
	 * @return string Built title.
	 */
	public function spine_get_title_filter( $title, $site_part, $global_part, $view_title ) {
		if ( is_front_page() ) {
			$title = $site_part . $global_part;
		} else {
			$title = $view_title . $site_part . $global_part;
		}
		return $title;
	}

}
