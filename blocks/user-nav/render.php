<?php
/**
 * doo/user-nav — Server-side render.
 *
 * Shows REGISTRARSE + INICIAR SESIÓN when logged out.
 * Shows MIS CURSOS · ÁREA PERSONAL · SALIR when logged in.
 *
 * @package DooFvmpTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$is_logged_in = is_user_logged_in();

if ( $is_logged_in ) :
	$logout_url       = wp_logout_url( home_url( '/' ) );
	$mis_cursos_url   = home_url( '/mis-cursos/' );
	$area_personal_url = home_url( '/area-personal/' );

	// Match by slug OR by current URL path (covers custom rewrites / CPT archives).
	$current_path     = trim( parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' );
	$is_mis_cursos    = is_page( 'mis-cursos' )    || $current_path === 'mis-cursos';
	$is_area_personal = is_page( 'area-personal' ) || $current_path === 'area-personal';
	?>
	<nav class="doo-header__user-nav" aria-label="<?php esc_attr_e( 'Menú de usuario', 'dw-tema' ); ?>">
		<a
			href="<?php echo esc_url( $mis_cursos_url ); ?>"
			class="doo-header__user-link<?php echo $is_mis_cursos ? ' is-active' : ''; ?>"
		><?php esc_html_e( 'MIS CURSOS', 'dw-tema' ); ?></a>

		<a
			href="<?php echo esc_url( $area_personal_url ); ?>"
			class="doo-header__user-link<?php echo $is_area_personal ? ' is-active' : ''; ?>"
		><?php esc_html_e( 'ÁREA PERSONAL', 'dw-tema' ); ?></a>

		<a
			href="<?php echo esc_url( $logout_url ); ?>"
			class="doo-header__user-link doo-header__user-link--logout"
		><?php esc_html_e( 'SALIR', 'dw-tema' ); ?></a>
	</nav>
	<?php
else :
	?>
	<div class="doo-header__actions">

		<span class="doo-header__lang">VAL / CAS</span>

		<a class="doo-header__btn doo-header__btn--outline" href="<?php echo esc_url( home_url( '/formulario-de-registro/' ) ); ?>">REGISTRARSE</a>

		<a class="doo-header__btn doo-header__btn--solid" href="<?php echo esc_url( home_url( '/inicio-sesion/' ) ); ?>">INICIAR SESIÓN</a>

	</div>
	<?php
endif;
