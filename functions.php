<?php
/**
 * FVMP Theme — Functions and definitions.
 *
 * @package DooFvmpTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define theme constants.
 */
define( 'DOO_THEME_VERSION', wp_get_theme()->get( 'Version' ) );
define( 'DOO_THEME_DIR', get_template_directory() );
define( 'DOO_THEME_URI', get_template_directory_uri() );

require_once DOO_THEME_DIR . '/inc/block-defaults.php';
require_once DOO_THEME_DIR . '/inc/doo-docente-cpt.php';
require_once DOO_THEME_DIR . '/inc/doo-testimonio-cpt.php';
require_once DOO_THEME_DIR . '/inc/fc-post-type.php';
require_once DOO_THEME_DIR . '/inc/fap-post-type.php';
require_once DOO_THEME_DIR . '/inc/pim-post-type.php';
require_once DOO_THEME_DIR . '/inc/jornadas-post-type.php';
require_once DOO_THEME_DIR . '/inc/doo-migrate-blocks.php';

/**
 * Theme setup.
 */
function doo_setup() {
	add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'doo_setup' );

// ==========================================================================
// Vite — Theme Assets
// ==========================================================================

/**
 * Set DOO_VITE_DEV to true in wp-config.php to use Vite dev server:
 * define( 'DOO_VITE_DEV', true );
 */

/**
 * Check if Vite dev server is running.
 *
 * @return bool True if dev server is active.
 */
function doo_is_vite_dev() {
	return defined( 'DOO_VITE_DEV' ) && DOO_VITE_DEV;
}

/**
 * Get Vite manifest for production asset paths.
 *
 * @return array Parsed manifest data.
 */
function doo_get_vite_manifest() {
	$manifest_path = DOO_THEME_DIR . '/dist/.vite/manifest.json';

	if ( ! file_exists( $manifest_path ) ) {
		return array();
	}

	$content = file_get_contents( $manifest_path );

	if ( false === $content ) {
		return array();
	}

	return json_decode( $content, true );
}

/**
 * Enqueue theme assets via Vite.
 *
 * In development: loads from Vite dev server (HMR).
 * In production: loads compiled files from /dist/ using manifest.
 *
 * @return void
 */
function doo_enqueue_assets() {

	// --- Development mode: Vite dev server ---
	if ( doo_is_vite_dev() ) {

		// Vite client for HMR — must load before the entry point.
		wp_enqueue_script(
			'doo-vite-client',
			'http://localhost:5173/@vite/client',
			array(),
			null,
			false
		);

		// Main entry point. Vite injects CSS via JS (HMR).
		// Do NOT enqueue main.scss separately — WP adds ?ver= which breaks the Vite module.
		wp_enqueue_script(
			'doo-main-script',
			'http://localhost:5173/src/js/main.js',
			array(),
			null,
			true
		);

		return;
	}

	// --- Production mode: compiled assets ---
	$manifest = doo_get_vite_manifest();

	if ( empty( $manifest ) ) {
		return;
	}

	// Enqueue compiled CSS.
	if ( isset( $manifest['src/js/main.js']['css'] ) ) {
		foreach ( $manifest['src/js/main.js']['css'] as $index => $css_file ) {
			wp_enqueue_style(
				'doo-main-style-' . $index,
				DOO_THEME_URI . '/dist/' . $css_file,
				array(),
				null
			);
		}
	}

	// Enqueue compiled JS.
	if ( isset( $manifest['src/js/main.js']['file'] ) ) {
		wp_enqueue_script(
			'doo-main-script',
			DOO_THEME_URI . '/dist/' . $manifest['src/js/main.js']['file'],
			array(),
			null,
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'doo_enqueue_assets' );

/**
 * Enqueue custom styles on wp-login.php (login, register, lost password).
 *
 * @return void
 */
function doo_enqueue_login_styles() {

	// --- Development mode: Vite dev server ---
	if ( doo_is_vite_dev() ) {
		wp_enqueue_style(
			'doo-login-style',
			'http://localhost:5173/src/scss/login.scss',
			array(),
			null
		);
		return;
	}

	// --- Production mode: compiled asset ---
	$manifest = doo_get_vite_manifest();

	if ( isset( $manifest['src/scss/login.scss']['file'] ) ) {
		wp_enqueue_style(
			'doo-login-style',
			DOO_THEME_URI . '/dist/' . $manifest['src/scss/login.scss']['file'],
			array(),
			null
		);
	}
}
add_action( 'login_enqueue_scripts', 'doo_enqueue_login_styles' );

/**
 * Add type="module" on Vite scripts.
 *
 * @param string $tag    Script HTML tag.
 * @param string $handle Script handle.
 * @return string Modified script tag.
 */
function doo_vite_script_type_module( $tag, $handle ) {
	if ( str_starts_with( $handle, 'doo-' ) && 'doo-blocks-editor' !== $handle ) {
		$tag = str_replace( '<script ', '<script type="module" ', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'doo_vite_script_type_module', 10, 2 );

/**
 * Enqueue base theme stylesheet for WP identification.
 *
 * @return void
 */
function doo_enqueue_base_style() {
	wp_enqueue_style(
		'doo-base-style',
		get_stylesheet_uri(),
		array(),
		DOO_THEME_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'doo_enqueue_base_style' );

// ==========================================================================
// Gutenberg Blocks — Registration
// ==========================================================================

/**
 * Register the compiled block editor script (built by Vite).
 *
 * @return void
 */
function doo_register_block_editor_assets() {
	$script_path = DOO_THEME_DIR . '/build/doo-blocks.js';

	if ( ! file_exists( $script_path ) ) {
		return;
	}

	wp_register_script(
		'doo-blocks-editor',
		DOO_THEME_URI . '/build/doo-blocks.js',
		array( 'wp-blocks', 'wp-block-editor', 'wp-element', 'wp-components', 'wp-server-side-render', 'wp-i18n', 'wp-api-fetch' ),
		filemtime( $script_path ),
		true
	);
}
add_action( 'init', 'doo_register_block_editor_assets' );

/**
 * Datos auxiliares para el editor de bloques (mensaje largo por defecto, URI del tema).
 *
 * @return void
 */
function doo_localize_block_editor_helpers() {
	if ( ! wp_script_is( 'doo-blocks-editor', 'registered' ) ) {
		return;
	}

	wp_localize_script(
		'doo-blocks-editor',
		'dooThemeEditor',
		array(
			'themeUri'               => DOO_THEME_URI,
			'presentacionFullDefault' => doo_theme_presentacion_full_default(),
		)
	);
}
add_action( 'enqueue_block_editor_assets', 'doo_localize_block_editor_helpers' );

/**
 * Enqueue theme CSS inside the block editor iframe so blocks
 * render with the same styles as the frontend.
 *
 * Uses `enqueue_block_assets` instead of `enqueue_block_editor_assets`
 * because WordPress 6.x renders blocks in an iframe — the latter hook
 * only loads styles in the parent admin frame, not inside the iframe.
 *
 * @return void
 */
function doo_enqueue_editor_styles() {
	if ( ! is_admin() ) {
		return;
	}

	// In dev mode, CSS is injected by main.js via HMR — nothing extra needed here.
	if ( doo_is_vite_dev() ) {
		return;
	}

	$manifest = doo_get_vite_manifest();

	if ( isset( $manifest['src/js/main.js']['css'] ) ) {
		foreach ( $manifest['src/js/main.js']['css'] as $index => $css_file ) {
			wp_enqueue_style(
				'doo-editor-style-' . $index,
				DOO_THEME_URI . '/dist/' . $css_file,
				array(),
				null
			);
		}
	}
}
add_action( 'enqueue_block_assets', 'doo_enqueue_editor_styles' );

/**
 * Register all custom blocks found in the blocks/ directory.
 *
 * @return void
 */
function doo_register_custom_blocks() {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	$blocks_dir  = DOO_THEME_DIR . '/blocks';
	$block_jsons = glob( $blocks_dir . '/*/block.json' );

	if ( empty( $block_jsons ) ) {
		return;
	}

	foreach ( $block_jsons as $block_json ) {
		register_block_type( dirname( $block_json ) );
	}
}
add_action( 'init', 'doo_register_custom_blocks' );

/**
 * Add a custom block category for FVMP blocks.
 *
 * @param array                   $categories Existing categories.
 * @param WP_Block_Editor_Context $context    Editor context.
 * @return array Modified categories.
 */
function doo_register_block_category( $categories, $context ) {
	return array_merge(
		array(
			array(
				'slug'  => 'doo-blocks',
				'title' => __( 'FVMP Blocks', 'dw-tema' ),
			),
		),
		$categories
	);
}
add_filter( 'block_categories_all', 'doo_register_block_category', 10, 2 );

// ==========================================================================
// Login Form — Label customisation
// ==========================================================================

/**
 * Translate wp_login_form() labels to match the FVMP design.
 *
 * @param array $defaults Default login form arguments.
 * @return array Modified defaults.
 */
function doo_login_form_labels( $defaults ) {
	$defaults['label_username'] = __( 'Correo electrónico', 'dw-tema' );
	$defaults['label_password'] = __( 'Contraseña', 'dw-tema' );
	$defaults['label_log_in']   = __( 'Iniciar sesión', 'dw-tema' );
	$defaults['label_remember'] = __( 'Recuérdame', 'dw-tema' );
	return $defaults;
}
add_filter( 'login_form_defaults', 'doo_login_form_labels' );

// ==========================================================================
// Front Page — Force the page-home template page as the static front page
// ==========================================================================

/**
 * Set the front page to the page using the page-home template if not already
 * configured in Settings > Reading.
 */
function doo_set_front_page() {
	if ( 'page' === get_option( 'show_on_front' ) && (int) get_option( 'page_on_front' ) > 0 ) {
		return;
	}

	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'meta_key'       => '_wp_page_template',
			'meta_value'     => 'page-home',
			'posts_per_page' => 1,
			'post_status'    => 'publish',
		)
	);

	if ( empty( $pages ) ) {
		$pages = get_posts(
			array(
				'post_type'      => 'page',
				'name'           => 'home',
				'posts_per_page' => 1,
				'post_status'    => 'publish',
			)
		);
	}

	if ( empty( $pages ) ) {
		return;
	}

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $pages[0]->ID );
}
add_action( 'init', 'doo_set_front_page' );

// ==========================================================================
// Logout — Redirect to current page unless it requires login
// ==========================================================================

/**
 * Slugs of pages that require an active session.
 * Logging out from these redirects to home instead.
 */
function doo_protected_slugs() {
	return array( 'mis-cursos', 'area-personal' );
}

/**
 * After logout, stay on the current page unless it is protected.
 *
 * @param string  $redirect_to           Default redirect URL.
 * @param string  $requested_redirect_to The URL passed to wp_logout_url().
 * @param WP_User $user                  The user that just logged out.
 * @return string Redirect URL.
 */
function doo_logout_redirect( $redirect_to, $requested_redirect_to, $user ) {
	if ( ! $requested_redirect_to ) {
		return home_url( '/' );
	}

	$path = trim( parse_url( $requested_redirect_to, PHP_URL_PATH ), '/' );

	if ( in_array( $path, doo_protected_slugs(), true ) ) {
		return home_url( '/' );
	}

	return $requested_redirect_to;
}
add_filter( 'logout_redirect', 'doo_logout_redirect', 10, 3 );
