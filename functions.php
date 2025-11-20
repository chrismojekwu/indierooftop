<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_VERSION', '3.4.4' );
define( 'EHP_THEME_SLUG', 'hello-elementor' );

define( 'HELLO_THEME_PATH', get_template_directory() );
define( 'HELLO_THEME_URL', get_template_directory_uri() );
define( 'HELLO_THEME_ASSETS_PATH', HELLO_THEME_PATH . '/assets/' );
define( 'HELLO_THEME_ASSETS_URL', HELLO_THEME_URL . '/assets/' );
define( 'HELLO_THEME_SCRIPTS_PATH', HELLO_THEME_ASSETS_PATH . 'js/' );
define( 'HELLO_THEME_SCRIPTS_URL', HELLO_THEME_ASSETS_URL . 'js/' );
define( 'HELLO_THEME_STYLE_PATH', HELLO_THEME_ASSETS_PATH . 'css/' );
define( 'HELLO_THEME_STYLE_URL', HELLO_THEME_ASSETS_URL . 'css/' );
define( 'HELLO_THEME_IMAGES_PATH', HELLO_THEME_ASSETS_PATH . 'images/' );
define( 'HELLO_THEME_IMAGES_URL', HELLO_THEME_ASSETS_URL . 'images/' );

if ( ! isset( $content_width ) ) {
	$content_width = 800; // Pixels.
}

if ( ! function_exists( 'hello_elementor_setup' ) ) {
	/**
	 * Set up theme support.
	 *
	 * @return void
	 */
	function hello_elementor_setup() {
		if ( is_admin() ) {
			hello_maybe_update_theme_version_in_db();
		}

		if ( apply_filters( 'hello_elementor_register_menus', true ) ) {
			register_nav_menus( [ 'menu-1' => esc_html__( 'Header', 'hello-elementor' ) ] );
			register_nav_menus( [ 'menu-2' => esc_html__( 'Footer', 'hello-elementor' ) ] );
		}

		if ( apply_filters( 'hello_elementor_post_type_support', true ) ) {
			add_post_type_support( 'page', 'excerpt' );
		}

		if ( apply_filters( 'hello_elementor_add_theme_support', true ) ) {
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support(
				'html5',
				[
					'search-form',
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
					'script',
					'style',
					'navigation-widgets',
				]
			);
			add_theme_support(
				'custom-logo',
				[
					'height'      => 100,
					'width'       => 350,
					'flex-height' => true,
					'flex-width'  => true,
				]
			);
			add_theme_support( 'align-wide' );
			add_theme_support( 'responsive-embeds' );

			/*
			 * Editor Styles
			 */
			add_theme_support( 'editor-styles' );
			add_editor_style( 'editor-styles.css' );

			/*
			 * WooCommerce.
			 */
			if ( apply_filters( 'hello_elementor_add_woocommerce_support', true ) ) {
				// WooCommerce in general.
				add_theme_support( 'woocommerce' );
				// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0).
				// zoom.
				add_theme_support( 'wc-product-gallery-zoom' );
				// lightbox.
				add_theme_support( 'wc-product-gallery-lightbox' );
				// swipe.
				add_theme_support( 'wc-product-gallery-slider' );
			}
		}
	}
}
add_action( 'after_setup_theme', 'hello_elementor_setup' );

function hello_maybe_update_theme_version_in_db() {
	$theme_version_option_name = 'hello_theme_version';
	// The theme version saved in the database.
	$hello_theme_db_version = get_option( $theme_version_option_name );

	// If the 'hello_theme_version' option does not exist in the DB, or the version needs to be updated, do the update.
	if ( ! $hello_theme_db_version || version_compare( $hello_theme_db_version, HELLO_ELEMENTOR_VERSION, '<' ) ) {
		update_option( $theme_version_option_name, HELLO_ELEMENTOR_VERSION );
	}
}

if ( ! function_exists( 'hello_elementor_display_header_footer' ) ) {
	/**
	 * Check whether to display header footer.
	 *
	 * @return bool
	 */
	function hello_elementor_display_header_footer() {
		$hello_elementor_header_footer = true;

		return apply_filters( 'hello_elementor_header_footer', $hello_elementor_header_footer );
	}
}

if ( ! function_exists( 'hello_elementor_scripts_styles' ) ) {
	/**
	 * Theme Scripts & Styles.
	 *
	 * @return void
	 */
	function hello_elementor_scripts_styles() {
		if ( apply_filters( 'hello_elementor_enqueue_style', true ) ) {
			wp_enqueue_style(
				'hello-elementor',
				HELLO_THEME_STYLE_URL . 'reset.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}

		if ( apply_filters( 'hello_elementor_enqueue_theme_style', true ) ) {
			wp_enqueue_style(
				'hello-elementor-theme-style',
				HELLO_THEME_STYLE_URL . 'theme.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}

		if ( hello_elementor_display_header_footer() ) {
			wp_enqueue_style(
				'hello-elementor-header-footer',
				HELLO_THEME_STYLE_URL . 'header-footer.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_scripts_styles' );

if ( ! function_exists( 'hello_elementor_register_elementor_locations' ) ) {
	/**
	 * Register Elementor Locations.
	 *
	 * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
	 *
	 * @return void
	 */
	function hello_elementor_register_elementor_locations( $elementor_theme_manager ) {
		if ( apply_filters( 'hello_elementor_register_elementor_locations', true ) ) {
			$elementor_theme_manager->register_all_core_location();
		}
	}
}
add_action( 'elementor/theme/register_locations', 'hello_elementor_register_elementor_locations' );

if ( ! function_exists( 'hello_elementor_content_width' ) ) {
	/**
	 * Set default content width.
	 *
	 * @return void
	 */
	function hello_elementor_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'hello_elementor_content_width', 800 );
	}
}
add_action( 'after_setup_theme', 'hello_elementor_content_width', 0 );

if ( ! function_exists( 'hello_elementor_add_description_meta_tag' ) ) {
	/**
	 * Add description meta tag with excerpt text.
	 *
	 * @return void
	 */
	function hello_elementor_add_description_meta_tag() {
		if ( ! apply_filters( 'hello_elementor_description_meta_tag', true ) ) {
			return;
		}

		if ( ! is_singular() ) {
			return;
		}

		$post = get_queried_object();
		if ( empty( $post->post_excerpt ) ) {
			return;
		}

		echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $post->post_excerpt ) ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'hello_elementor_add_description_meta_tag' );

// Settings page
require get_template_directory() . '/includes/settings-functions.php';

// Header & footer styling option, inside Elementor
require get_template_directory() . '/includes/elementor-functions.php';

if ( ! function_exists( 'hello_elementor_customizer' ) ) {
	// Customizer controls
	function hello_elementor_customizer() {
		if ( ! is_customize_preview() ) {
			return;
		}

		if ( ! hello_elementor_display_header_footer() ) {
			return;
		}

		require get_template_directory() . '/includes/customizer-functions.php';
	}
}
add_action( 'init', 'hello_elementor_customizer' );

if ( ! function_exists( 'hello_elementor_check_hide_title' ) ) {
	/**
	 * Check whether to display the page title.
	 *
	 * @param bool $val default value.
	 *
	 * @return bool
	 */
	function hello_elementor_check_hide_title( $val ) {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$current_doc = Elementor\Plugin::instance()->documents->get( get_the_ID() );
			if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
				$val = false;
			}
		}
		return $val;
	}
}
add_filter( 'hello_elementor_page_title', 'hello_elementor_check_hide_title' );

/**
 * BC:
 * In v2.7.0 the theme removed the `hello_elementor_body_open()` from `header.php` replacing it with `wp_body_open()`.
 * The following code prevents fatal errors in child themes that still use this function.
 */
if ( ! function_exists( 'hello_elementor_body_open' ) ) {
	function hello_elementor_body_open() {
		wp_body_open();
	}
}

/**
 * Custom post types
 */
function create_event_post_type() {
	register_post_type('ir_event_post',
		array(
			'public' => true,
        	'labels' => array(
            	'name' => esc_html__( 'IR Events', 'textdomain' ),
            	'all_items' => esc_html__( 'All IR Events', 'textdomain' ),
            	'singular_name' => esc_html__( 'IR Event', 'textdomain' )
        	),
			'has_archive' => true,
            'rewrite' => array('slug' => 'shows'),
            'supports' => array('title', 'editor', 'thumbnail', "custom-fields")
		)
	);
}
add_action( 'init', 'create_event_post_type' );

function create_release_post_type() {
	register_post_type('ir_release_post',
		array(
			'public' => true,
        	'labels' => array(
            	'name' => esc_html__( 'IR Releases', 'textdomain' ),
            	'all_items' => esc_html__( 'All IR Releases', 'textdomain' ),
            	'singular_name' => esc_html__( 'IR Release', 'textdomain' )
        	),
			'has_archive' => true,
            'rewrite' => array('slug' => 'releases'),
            'supports' => array('title', 'editor', 'thumbnail', "custom-fields")
		)
	);
}
add_action( 'init', 'create_release_post_type' );

/**
 * Custom Post Meta Boxes
 */
function ir_event_date_box() {
    add_meta_box(
        'ir_event_date', 
        'Event Date',
        'ir_event_date_callback',
        'ir_event_post',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'ir_event_date_box' );

function ir_event_city_box() {
    add_meta_box(
        'ir_event_city', 
        'Event City',
        'ir_event_city_callback',
        'ir_event_post',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'ir_event_city_box' );

function ir_event_venue_box() {
    add_meta_box(
        'ir_event_venue', 
        'Event Venue',
        'ir_event_venue_callback',
        'ir_event_post',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'ir_event_venue_box' );

function ir_event_ticket_box() {
    add_meta_box(
        'ir_event_ticket', 
        'Event Ticket',
        'ir_event_ticket_callback',
        'ir_event_post',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'ir_event_ticket_box' );

function ir_release_artist_box() {
    add_meta_box(
        'ir_release_artist', 
        'Release Artist',
        'ir_release_artist_callback',
        'ir_release_post',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'ir_release_artist_box' );

function ir_release_genre_box() {
    add_meta_box(
        'ir_release_genre', 
        'Release Genre',
        'ir_release_genre_callback',
        'ir_release_post',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'ir_release_genre_box' );

function ir_release_presslink_box() {
    add_meta_box(
        'ir_release_presslink', 
        'Release Press',
        'ir_release_presslink_callback',
        'ir_release_post',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'ir_release_presslink_box' );

function ir_release_show_box() {
    add_meta_box(
        'ir_release_show', 
        'Release Show',
        'ir_release_show_callback',
        'ir_release_post',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'ir_release_show_box' );

function ir_release_streaming_box() {
    add_meta_box(
        'ir_release_streaming', 
        'Release Streaming',
        'ir_release_streaming_callback',
        'ir_release_post',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'ir_release_streaming_box' );

/**
 * Custom Post Callbacks
 */
function ir_event_date_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'ir_event_date_nonce' );
    $my_meta_value = get_post_meta( $post->ID, '_ir_event_date_field', true );
    echo '<label for="ir_event_date_field">Event Date: </label>';
    echo '<input type="date" id="ir_event_date_field" name="ir_event_date_field" value="' . esc_attr( $my_meta_value ) . '" size="25" />';
}

function ir_event_city_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'ir_event_city_nonce' );
    $my_meta_value = get_post_meta( $post->ID, '_ir_event_city_field', true );
    echo '<label for="ir_event_city_field">Event City: </label>';
    echo '<input type="text" id="ir_event_city_field" name="ir_event_city_field" value="' . esc_attr( $my_meta_value ) . '" size="25" />';
}

function ir_event_venue_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'ir_event_venue_nonce' );
    $my_meta_value = get_post_meta( $post->ID, '_ir_event_venue_field', true );
    echo '<label for="ir_event_venue_field">Event Venue: </label>';
    echo '<input type="text" id="ir_event_venue_field" name="ir_event_venue_field" value="' . esc_attr( $my_meta_value ) . '" size="25" />';
}

function ir_event_ticket_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'ir_event_ticket_nonce' );
    $my_meta_value = get_post_meta( $post->ID, '_ir_event_ticket_field', true );
    echo '<label for="ir_event_ticket_field">Event Ticket: </label>';
    echo '<input type="url" id="ir_event_ticket_field" name="ir_event_ticket_field" value="' . esc_attr( $my_meta_value ) . '" size="25" />';
}

function ir_release_artist_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'ir_release_artist_nonce' );
    $my_meta_value = get_post_meta( $post->ID, '_ir_release_artist_field', true );
    echo '<label for="ir_release_artist_field">Artist: </label>';
    echo '<input type="text" id="ir_release_artist_field" name="ir_release_artist_field" value="' . esc_attr( $my_meta_value ) . '" size="25" />';
}

function ir_release_genre_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'ir_release_genre_nonce' );
    $my_meta_value = get_post_meta( $post->ID, '_ir_release_genre_field', true );
    echo '<label for="ir_release_genre_field">Genre: </label>';
    echo '<input type="text" id="ir_release_genre_field" name="ir_release_genre_field" value="' . esc_attr( $my_meta_value ) . '" size="25" />';
}

function ir_release_presslink_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'ir_release_presslink_nonce' );
    $my_meta_value = get_post_meta( $post->ID, '_ir_release_presslink_field', true );
    echo '<label for="ir_release_presslink_field">Press Link: </label>';
    echo '<input type="url" id="ir_release_presslink_field" name="ir_release_presslink_field" value="' . esc_attr( $my_meta_value ) . '" size="25" />';
}

function ir_release_show_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'ir_release_show_nonce' );
    $my_meta_value = get_post_meta( $post->ID, '_ir_release_show_field', true );
    echo '<label for="ir_release_show_field">Show Link: </label>';
    echo '<input type="url" id="ir_release_show_field" name="ir_release_show_field" value="' . esc_attr( $my_meta_value ) . '" size="25" />';
}

function ir_release_streaming_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'ir_release_streaming_nonce' );
    $my_meta_value = get_post_meta( $post->ID, '_ir_release_streaming_field', true );
    echo '<label for="ir_release_streaming_field">Streaming Link: </label>';
    echo '<input type="url" id="ir_release_streaming_field" name="ir_release_streaming_field" value="' . esc_attr( $my_meta_value ) . '" size="25" />';
}

/**
 * Custom Post Save Actions
 */
function save_ir_event_date_field_data( $post_id ) {
    if ( ! isset( $_POST['ir_event_date_nonce'] ) || ! wp_verify_nonce( $_POST['ir_event_date_nonce'], basename( __FILE__ ) ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( wp_is_post_revision( $post_id ) ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    if ( isset( $_POST['ir_event_date_field'] ) ) {
        update_post_meta( $post_id, '_ir_event_date_field', sanitize_text_field( $_POST['ir_event_date_field'] ) );
    } else {
        delete_post_meta( $post_id, '_ir_event_date_field' );
    }
}
add_action( 'save_post', 'save_ir_event_date_field_data' );

function save_ir_event_city_field_data( $post_id ) {
    if ( ! isset( $_POST['ir_event_city_nonce'] ) || ! wp_verify_nonce( $_POST['ir_event_city_nonce'], basename( __FILE__ ) ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( wp_is_post_revision( $post_id ) ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    if ( isset( $_POST['ir_event_city_field'] ) ) {
        update_post_meta( $post_id, '_ir_event_city_field', sanitize_text_field( $_POST['ir_event_city_field'] ) );
    } else {
        delete_post_meta( $post_id, '_ir_event_city_field' );
    }
}
add_action( 'save_post', 'save_ir_event_city_field_data' );

function save_ir_event_venue_field_data( $post_id ) {
    if ( ! isset( $_POST['ir_event_venue_nonce'] ) || ! wp_verify_nonce( $_POST['ir_event_venue_nonce'], basename( __FILE__ ) ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( wp_is_post_revision( $post_id ) ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    if ( isset( $_POST['ir_event_venue_field'] ) ) {
        update_post_meta( $post_id, '_ir_event_venue_field', sanitize_text_field( $_POST['ir_event_venue_field'] ) );
    } else {
        delete_post_meta( $post_id, '_ir_event_venue_field' );
    }
}
add_action( 'save_post', 'save_ir_event_venue_field_data' );

function save_ir_event_ticket_field_data( $post_id ) {
    if ( ! isset( $_POST['ir_event_ticket_nonce'] ) || ! wp_verify_nonce( $_POST['ir_event_ticket_nonce'], basename( __FILE__ ) ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( wp_is_post_revision( $post_id ) ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    if ( isset( $_POST['ir_event_ticket_field'] ) ) {
        update_post_meta( $post_id, '_ir_event_ticket_field', sanitize_text_field( $_POST['ir_event_ticket_field'] ) );
    } else {
        delete_post_meta( $post_id, '_ir_event_ticket_field' );
    }
}
add_action( 'save_post', 'save_ir_event_ticket_field_data' );

function save_ir_release_artist_field_data( $post_id ) {
    if ( ! isset( $_POST['ir_release_artist_nonce'] ) || ! wp_verify_nonce( $_POST['ir_release_artist_nonce'], basename( __FILE__ ) ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( wp_is_post_revision( $post_id ) ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    if ( isset( $_POST['ir_release_artist_field'] ) ) {
        update_post_meta( $post_id, '_ir_release_artist_field', sanitize_text_field( $_POST['ir_release_artist_field'] ) );
    } else {
        delete_post_meta( $post_id, '_ir_release_artist_field' );
    }
}
add_action( 'save_post', 'save_ir_release_artist_field_data' );

function save_ir_release_genre_field_data( $post_id ) {
    if ( ! isset( $_POST['ir_release_genre_nonce'] ) || ! wp_verify_nonce( $_POST['ir_release_genre_nonce'], basename( __FILE__ ) ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( wp_is_post_revision( $post_id ) ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    if ( isset( $_POST['ir_release_genre_field'] ) ) {
        update_post_meta( $post_id, '_ir_release_genre_field', sanitize_text_field( $_POST['ir_release_genre_field'] ) );
    } else {
        delete_post_meta( $post_id, '_ir_release_genre_field' );
    }
}
add_action( 'save_post', 'save_ir_release_genre_field_data' );

function save_ir_release_presslink_field_data( $post_id ) {
    if ( ! isset( $_POST['ir_release_presslink_nonce'] ) || ! wp_verify_nonce( $_POST['ir_release_presslink_nonce'], basename( __FILE__ ) ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( wp_is_post_revision( $post_id ) ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    if ( isset( $_POST['ir_release_presslink_field'] ) ) {
        update_post_meta( $post_id, '_ir_release_presslink_field', sanitize_text_field( $_POST['ir_release_presslink_field'] ) );
    } else {
        delete_post_meta( $post_id, '_ir_release_presslink_field' );
    }
}
add_action( 'save_post', 'save_ir_release_presslink_field_data' );

function save_ir_release_show_field_data( $post_id ) {
    if ( ! isset( $_POST['ir_release_show_nonce'] ) || ! wp_verify_nonce( $_POST['ir_release_show_nonce'], basename( __FILE__ ) ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( wp_is_post_revision( $post_id ) ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    if ( isset( $_POST['ir_release_show_field'] ) ) {
        update_post_meta( $post_id, '_ir_release_show_field', sanitize_text_field( $_POST['ir_release_show_field'] ) );
    } else {
        delete_post_meta( $post_id, '_ir_release_show_field' );
    }
}
add_action( 'save_post', 'save_ir_release_show_field_data' );

function save_ir_release_streaming_field_data( $post_id ) {
    if ( ! isset( $_POST['ir_release_streaming_nonce'] ) || ! wp_verify_nonce( $_POST['ir_release_streaming_nonce'], basename( __FILE__ ) ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( wp_is_post_revision( $post_id ) ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    if ( isset( $_POST['ir_release_streaming_field'] ) ) {
        update_post_meta( $post_id, '_ir_release_streaming_field', sanitize_text_field( $_POST['ir_release_streaming_field'] ) );
    } else {
        delete_post_meta( $post_id, '_ir_release_streaming_field' );
    }
}
add_action( 'save_post', 'save_ir_release_streaming_field_data' );


function register_music_id_meta_field() {
    register_meta( 'ir_release_post', 'external_id', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'auth_callback' => function() {
            return current_user_can( 'edit_posts' );
        }
    ) );
}
add_action( 'rest_api_init', 'register_music_id_meta_field' );

/**
 * Release API Calls & Cron setup
 */

function set_featured_image_from_url($post_id, $image_url, $image_description = 'Featured Image') {
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');
	
    $image_id = media_sideload_image($image_url, $post_id, $image_description, 'id');

    if (!is_wp_error($image_id)) {
        set_post_thumbnail($post_id, $image_id);
    } else {
        error_log('Failed to sideload image: ' . $image_id->get_error_message());
    }
}

function authenticate_and_get_shopify_search() {
	$response = wp_remote_post( 'https://accounts.spotify.com/api/token', array(
		'method'      => 'POST',
    	'headers'     => array(
        	'Content-Type' => 'application/x-www-form-urlencoded',
    	),
    	'body'        => array(
        	'grant_type'    => 'client_credentials',
        	'client_id'     => '013b2c010a6d40d1b3973903e7343291',
        	'client_secret' => '48c8e5c828c14e8793e060655c2cb798',
        	'redirect_uri'  => 'https://indierooftop.com/',
    	),
    	'timeout'     => 30, 
    	'sslverify'   => true,
	) );
	
	if ( is_wp_error( $response ) ) {
    	$error_message = $response->get_error_message();
    	error_log( "Token request failed: $error_message" );
	} else {
    	$body = wp_remote_retrieve_body( $response );
    	$data = json_decode( $body );

    	if ( isset( $data->access_token ) ) {
        	$access_token = $data->access_token;
			$music_queries = ['https://api.spotify.com/v1/search?q=independent%20year:2025%20genre:r%26b&type=track', 'https://api.spotify.com/v1/search?q=independent%20year:2025%20genre:dance&type=track', 'https://api.spotify.com/v1/search?q=independent%20year:2025%20genre:hiphop&type=track'];
        	// Use the access token
        	// 
        	// 
        	$synced_ids = array();
			
        	foreach ($music_queries as $query) {
				$music_response = wp_remote_get($query,  array(
					'method'      => 'GET',
    				'headers'     => array(
        				'Authorization' => 'Bearer ' . $access_token,
    				)
				) );
				
				$music_genre = "";
				
				if ($query == $music_queries[0]) {
					$music_genre = "R&B";
				} elseif ($query == $music_queries[1]) {
					$music_genre = "Dance";
				} elseif ($query == $music_queries[2]) {
					$music_genre = "Hip Hop";
				}
				
				if ( is_wp_error( $music_response ) ) {
        			return 'Error: ' . $music_response->get_error_message();
    			}

    			$body = wp_remote_retrieve_body( $music_response );
    			$data = json_decode( $body );
				
				error_log( "Next Link: " . $data->tracks->next  );
				
				if ( isset( $data->tracks ) && isset( $data->tracks->items ) ) {
        			$music_items = $data->tracks->items;
					foreach ( $music_items as $music_item ) {
						
						$existing_post = get_posts( array(
            				'post_type'  => 'ir_release_post',
            				'meta_key'   => 'external_id',
            				'meta_value' => $music_item->album->id, 
            				'fields'     => 'ids',
        				) );
						
						error_log( "check for existing post: " . $existing_post[0] );
						error_log( "check image url: " . $music_item->album->images[0]->url );
						error_log( "check api id: " . $post_id = $music_item->album->id );
						
						$post_id = $music_item->album->id;
						
        				if ( ! empty( $existing_post ) ) {
            				$post_id = $existing_post[0]; 
							wp_update_post( array(
                				'ID'           => $post_id,
                				'post_title'   => sanitize_text_field( $music_item->album->name ), // Map API 'name' to post title
            					'post_content' => wp_kses_post( $music_item->album->name . " " . $music_item->album->artists[0]->name  ), // Map API 'description' to post content
            					'post_status'  => 'publish',
            					'post_type'    => 'ir_release_post',
            				) );
							$synced_ids[] = $music_item->album->id;
        				} else {
							$post_args = array(
            					'post_title'   => sanitize_text_field( $music_item->album->name ), // Map API 'name' to post title
            					'post_content' => wp_kses_post( $music_item->album->name . " " . $music_item->album->artists[0]->name  ), // Map API 'description' to post content
            					'post_status'  => 'publish',
            					'post_type'    => 'ir_release_post',
        					);
							$post_id = wp_insert_post( $post_args );
							if ( ! is_wp_error( $post_id ) ) {
                				update_post_meta( $post_id, 'external_id', $music_item->album->id ); // Store the API ID
								$synced_ids[] = $music_item->album->id;
            				}
						}
						
						
						
						/*
						if ( $post_id ) {
            				$post_args['ID'] = $post_id;
            				wp_update_post( $post_args );
        				} else {
            				$post_id = wp_insert_post( $post_args );
        				}*/
						
						# ORIGINAL WORKING INSERT POST
						//$post_id = wp_insert_post( $post_args );
						
						if ( $post_id && ! is_wp_error( $post_id ) ) {
            				// Update custom fields
            				update_post_meta( $post_id, 'external_id', $music_item->album->id );
            				update_post_meta( $post_id, '_ir_release_artist_field', $music_item->album->artists[0]->name ); // Map other API fields to custom fields
            				update_post_meta( $post_id, '_ir_release_genre_field', $music_genre );
							update_post_meta( $post_id, '_ir_release_streaming_field', $music_item->album->external_urls->spotify );
							set_featured_image_from_url($post_id, $music_item->album->images[0]->url);
        				}
					}
    			}
			}
			
			$all_synced_posts = get_posts( array(
        		'post_type'  => 'ir_release_post',
        		'meta_key'   => 'external_id',
        		'posts_per_page' => -1,
        		'fields' => 'ids',
    		) );
			
			foreach ( $all_synced_posts as $post_id ) {
        		$api_id = get_post_meta( $post_id, 'external_id', true );
        		if ( ! in_array( $api_id, $synced_ids ) ) {
            		wp_delete_post( $post_id, true ); // Delete the post permanently
        		}
    		}
			
			
        	error_log( "Access Token: " . $access_token );
			$api_url = 'https://api.spotify.com/v1/search';
			
    	} else {
        	error_log( "Token response did not contain an access token: " . $body );
    	}
	}
}
add_action( 'admin_init', 'authenticate_and_get_shopify_search' );

function release_cron_schedule( $schedules ) {
	$schedules['every_twelve_hours'] = array(
    	'interval' => 43200, 
        'display'  => __( 'Every Twelve Hours' )
    );
    return $schedules;
}
//add_filter( 'cron_schedules', 'release_cron_schedule' );

if ( ! wp_next_scheduled( 'my_custom_event_hook' ) ) {
	wp_schedule_event( time(), 'every_twelve_hours', 'release_event_hook' );
}

//add_action( 'release_event_hook', 'authenticate_and_get_shopify_search' );

function move_release_posts_to_trash() {
    $post_type = 'ir_release_post'; // Replace with your custom post type slug
    $args = array(
        'post_type'      => $post_type,
        'posts_per_page' => -1, // Retrieve all posts
        'fields'         => 'ids', // Retrieve only post IDs
        'post_status'    => 'publish', // Or 'any' to include drafts, etc.
    );
    $posts = get_posts($args);

    if ($posts) {
        foreach ($posts as $post_id) {
            wp_trash_post($post_id); // Move to trash
        }
        //echo "All posts of '$post_type' moved to trash.";
    } else {
       // echo "No posts found for '$post_type'.";
    }
}

//add_action('init', 'move_release_posts_to_trash');

require HELLO_THEME_PATH . '/theme.php';

HelloTheme\Theme::instance();
