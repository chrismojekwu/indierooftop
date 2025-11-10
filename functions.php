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
 * Custom post type for events
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

/**
 * Event Date Meta Box
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

function ir_event_date_callback( $post ) {
    // Add a nonce field for security
    wp_nonce_field( basename( __FILE__ ), 'ir_event_date_nonce' );

    // Retrieve existing meta value (if any)
    $my_meta_value = get_post_meta( $post->ID, '_ir_event_date_field', true );

    // Output the field
    echo '<label for="ir_event_date_field">Event Date: </label>';
    echo '<input type="date" id="ir_event_date_field" name="ir_event_date_field" value="' . esc_attr( $my_meta_value ) . '" size="25" />';
}

function save_ir_event_date_field_data( $post_id ) {
    // Verify nonce for security
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

/**
 * Event City Meta Box
 */
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

function ir_event_city_callback( $post ) {
    // Add a nonce field for security
    wp_nonce_field( basename( __FILE__ ), 'ir_event_city_nonce' );

    // Retrieve existing meta value (if any)
    $my_meta_value = get_post_meta( $post->ID, '_ir_event_city_field', true );

    // Output the field
    echo '<label for="ir_event_city_field">Event City: </label>';
    echo '<input type="text" id="ir_event_city_field" name="ir_event_city_field" value="' . esc_attr( $my_meta_value ) . '" size="25" />';
}

function save_ir_event_city_field_data( $post_id ) {
    // Verify nonce for security
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

/**
 * Event Venue Meta Box
 */
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

function ir_event_venue_callback( $post ) {
    // Add a nonce field for security
    wp_nonce_field( basename( __FILE__ ), 'ir_event_venue_nonce' );

    // Retrieve existing meta value (if any)
    $my_meta_value = get_post_meta( $post->ID, '_ir_event_venue_field', true );

    // Output the field
    echo '<label for="ir_event_venue_field">Event Venue: </label>';
    echo '<input type="text" id="ir_event_venue_field" name="ir_event_venue_field" value="' . esc_attr( $my_meta_value ) . '" size="25" />';
}

function save_ir_event_venue_field_data( $post_id ) {
    // Verify nonce for security
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


/**
 * Event Ticket Link Meta Box
 */
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

function ir_event_ticket_callback( $post ) {
    // Add a nonce field for security
    wp_nonce_field( basename( __FILE__ ), 'ir_event_ticket_nonce' );

    // Retrieve existing meta value (if any)
    $my_meta_value = get_post_meta( $post->ID, '_ir_event_ticket_field', true );

    // Output the field
    echo '<label for="ir_event_ticket_field">Event Ticket: </label>';
    echo '<input type="url" id="ir_event_ticket_field" name="ir_event_ticket_field" value="' . esc_attr( $my_meta_value ) . '" size="25" />';
}

function save_ir_event_ticket_field_data( $post_id ) {
    // Verify nonce for security
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

/**
 * Parsing WPGetAPI for various endpoints
 */

/**
 * Ticketmaster Events
 */
function ticketmaster_parser() {
    $api_data = wpgetapi_endpoint( 'ticketmaster', 'ticketmaster_events' );
	$parsed_data = json_decode($api_data)->_embedded->events;
	
	$output = '<div>';
	
	if (isset($parsed_data) && is_array($parsed_data)) {
		foreach ($parsed_data as $item) {
        	$output .= '<p>'. $item->name . '<div>' . '<img src="' . $item->images[3]->url . '">' . '</div>' . '</p>';
    	}
	} else {
		$output .= '<p> There was an error retrieving events.</p>';
	}
	
	$output .= '</div>';

   return $output;
}
add_shortcode( 'ticketmaster_events_formatted', 'ticketmaster_parser' );

require HELLO_THEME_PATH . '/theme.php';

HelloTheme\Theme::instance();
