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

require_once DOO_THEME_DIR . '/inc/home-options.php';
require_once DOO_THEME_DIR . '/inc/home-shortcodes.php';
require_once DOO_THEME_DIR . '/inc/fc-post-type.php';
require_once DOO_THEME_DIR . '/inc/fc-shortcodes.php';

/**
 * Theme setup.
 */
function doo_setup() {
	add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'doo_setup' );

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

		// Vite client for HMR.
		wp_enqueue_script(
			'doo-vite-client',
			'http://localhost:5173/@vite/client',
			array(),
			null,
			false
		);

		// Main entry point (Vite serves SCSS through JS).
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
 * Add type="module" to Vite scripts.
 *
 * @param string $tag    Script HTML tag.
 * @param string $handle Script handle.
 * @return string Modified script tag.
 */
function doo_vite_script_type_module( $tag, $handle ) {
	if ( str_starts_with( $handle, 'doo-' ) ) {
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
// Docente — Custom Post Type
// ==========================================================================

/**
 * Register the Docente CPT.
 *
 * @return void
 */
function doo_register_docente_cpt() {
	register_post_type(
		'doo_docente',
		array(
			'labels'       => array(
				'name'          => __( 'Docentes', 'dw-tema' ),
				'singular_name' => __( 'Docente', 'dw-tema' ),
				'add_new_item'  => __( 'Añadir docente', 'dw-tema' ),
				'edit_item'     => __( 'Editar docente', 'dw-tema' ),
				'all_items'     => __( 'Todos los docentes', 'dw-tema' ),
				'search_items'  => __( 'Buscar docentes', 'dw-tema' ),
			),
			'public'        => false,
			'show_ui'       => true,
			'show_in_menu'  => true,
			'menu_icon'     => 'dashicons-groups',
			'menu_position' => 20,
			'supports'      => array( 'title', 'thumbnail', 'page-attributes' ),
			'has_archive'   => false,
		)
	);
}
add_action( 'init', 'doo_register_docente_cpt' );

/**
 * Register the meta box for Docente fields.
 *
 * @return void
 */
function doo_docente_add_meta_box() {
	add_meta_box(
		'doo_docente_fields',
		__( 'Datos del docente', 'dw-tema' ),
		'doo_docente_meta_box_html',
		'doo_docente',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'doo_docente_add_meta_box' );

/**
 * Render the Docente meta box fields.
 *
 * @param WP_Post $post Current post object.
 * @return void
 */
function doo_docente_meta_box_html( $post ) {
	wp_nonce_field( 'doo_docente_save', 'doo_docente_nonce' );

	$fields = array(
		'doo_degree'    => 'Titulación',
		'doo_role'      => 'Cargo',
		'doo_role2'     => 'Cargo 2 (opcional)',
		'doo_org'       => 'Organización',
		'doo_linkedin'  => 'URL LinkedIn',
		'doo_twitter'   => 'URL Twitter',
		'doo_instagram' => 'URL Instagram',
		'doo_facebook'  => 'URL Facebook',
	);

	echo '<table class="form-table"><tbody>';
	foreach ( $fields as $key => $label ) {
		$value = get_post_meta( $post->ID, $key, true );
		echo '<tr>';
		echo '<th><label for="' . esc_attr( $key ) . '">' . esc_html( $label ) . '</label></th>';
		echo '<td><input type="text" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" class="regular-text" /></td>';
		echo '</tr>';
	}
	echo '</tbody></table>';
}

/**
 * Save Docente meta fields on post save.
 *
 * @param int $post_id Post ID.
 * @return void
 */
function doo_docente_save_meta( $post_id ) {
	if ( ! isset( $_POST['doo_docente_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['doo_docente_nonce'] ) ), 'doo_docente_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$fields = array( 'doo_degree', 'doo_role', 'doo_role2', 'doo_org', 'doo_linkedin', 'doo_twitter', 'doo_instagram', 'doo_facebook' );

	foreach ( $fields as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}
}
add_action( 'save_post_doo_docente', 'doo_docente_save_meta' );

/**
 * Shortcode [doo_profesorado] — renders the teacher grid dynamically.
 *
 * @return string HTML output.
 */
function doo_profesorado_shortcode() {
	$query = new WP_Query(
		array(
			'post_type'      => 'doo_docente',
			'posts_per_page' => -1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'post_status'    => 'publish',
		)
	);

	if ( ! $query->have_posts() ) {
		return '';
	}

	$linkedin_svg  = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-label="LinkedIn"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect width="4" height="12" x="2" y="9"/><circle cx="4" cy="4" r="2"/></svg>';
	$twitter_svg   = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-label="Twitter"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.4 5.6 3.9 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>';
	$instagram_svg = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-label="Instagram"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>';
	$facebook_svg  = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-label="Facebook"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>';

	ob_start();
	?>
	<section class="doo-profesorado">
		<div class="doo-profesorado__container">

			<p class="doo-profesorado__eyebrow">ALGUNOS DE NUESTROS DOCENTES</p>
			<h2 class="doo-profesorado__title">Experto Profesorado</h2>
			<p class="doo-profesorado__desc">Desde la <strong>Federación Valenciana de Municipios y Provincias</strong> apostamos por el talento y la experiencia de nuestros profesores y ponentes para ofrecerte la mejor enseñanza.</p>

			<div class="doo-profesorado__grid">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
			$degree    = get_post_meta( get_the_ID(), 'doo_degree', true );
			$role      = get_post_meta( get_the_ID(), 'doo_role', true );
			$role2     = get_post_meta( get_the_ID(), 'doo_role2', true );
			$org       = get_post_meta( get_the_ID(), 'doo_org', true );
			$linkedin  = get_post_meta( get_the_ID(), 'doo_linkedin', true );
			$twitter   = get_post_meta( get_the_ID(), 'doo_twitter', true );
			$instagram = get_post_meta( get_the_ID(), 'doo_instagram', true );
			$facebook  = get_post_meta( get_the_ID(), 'doo_facebook', true );
				?>
				<div class="doo-teacher-card">
					<div class="doo-teacher-card__photo-wrap">
						<div class="doo-teacher-card__photo">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'medium', array( 'alt' => esc_attr( get_the_title() ) ) ); ?>
							<?php endif; ?>
						</div>
					</div>
					<div class="doo-teacher-card__body">
						<p class="doo-teacher-card__name"><?php the_title(); ?></p>
						<?php if ( $degree ) : ?>
							<p class="doo-teacher-card__degree"><?php echo esc_html( $degree ); ?></p>
						<?php endif; ?>
					<?php if ( $role ) : ?>
						<p class="doo-teacher-card__role"><?php echo esc_html( $role ); ?></p>
					<?php endif; ?>
					<?php if ( $role2 ) : ?>
						<p class="doo-teacher-card__role"><?php echo esc_html( $role2 ); ?></p>
					<?php endif; ?>
						<?php if ( $org ) : ?>
							<p class="doo-teacher-card__org"><?php echo esc_html( $org ); ?></p>
						<?php endif; ?>
					<?php if ( $linkedin || $twitter || $instagram || $facebook ) : ?>
						<div class="doo-teacher-card__social">
							<?php if ( $linkedin ) : ?>
								<a href="<?php echo esc_url( $linkedin ); ?>" target="_blank" rel="noopener noreferrer">
									<?php echo $linkedin_svg; // phpcs:ignore WordPress.Security.EscapeOutput ?>
								</a>
							<?php endif; ?>
							<?php if ( $twitter ) : ?>
								<a href="<?php echo esc_url( $twitter ); ?>" target="_blank" rel="noopener noreferrer">
									<?php echo $twitter_svg; // phpcs:ignore WordPress.Security.EscapeOutput ?>
								</a>
							<?php endif; ?>
							<?php if ( $instagram ) : ?>
								<a href="<?php echo esc_url( $instagram ); ?>" target="_blank" rel="noopener noreferrer">
									<?php echo $instagram_svg; // phpcs:ignore WordPress.Security.EscapeOutput ?>
								</a>
							<?php endif; ?>
							<?php if ( $facebook ) : ?>
								<a href="<?php echo esc_url( $facebook ); ?>" target="_blank" rel="noopener noreferrer">
									<?php echo $facebook_svg; // phpcs:ignore WordPress.Security.EscapeOutput ?>
								</a>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					</div>
				</div>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
			</div>

			<div class="doo-profesorado__more">
				<a href="#">Ver todo el profesorado →</a>
			</div>

		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'doo_profesorado', 'doo_profesorado_shortcode' );

