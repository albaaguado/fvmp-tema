<?php
/**
 * Jornada Detail Block Render Template.
 *
 * Displays full details of a single Jornada.
 *
 * @package DooFvmpTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get linked CPT ID from the current page
$page_id        = get_the_ID();
$linked_cpt_id  = get_post_meta( $page_id, 'doo_linked_cpt_id', true );

if ( ! $linked_cpt_id ) {
	echo '<p>No se encontró la jornada vinculada.</p>';
	return;
}

$jornada = get_post( $linked_cpt_id );

if ( ! $jornada || 'doo_jornada' !== $jornada->post_type ) {
	echo '<p>La jornada no existe o ha sido eliminada.</p>';
	return;
}

$title       = $jornada->post_title;
$edition     = get_post_meta( $linked_cpt_id, 'doo_jornada_edition', true );
$date        = get_post_meta( $linked_cpt_id, 'doo_jornada_date', true );
$description = get_post_meta( $linked_cpt_id, 'doo_jornada_description', true );
$image       = get_the_post_thumbnail_url( $linked_cpt_id, 'large' );

// Colors for Jornadas section (teal theme)
$accent_color = '#009e96';
$bg_light     = '#f5f7fa';
$text_navy    = '#152057';
?>

<article class="doo-jornada-detail">
	<!-- Breadcrumb -->
	<nav class="doo-jornada-detail__breadcrumb" aria-label="Breadcrumb">
		<div class="doo-jornada-detail__breadcrumb-container">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Inicio</a>
			<span class="doo-jornada-detail__breadcrumb-sep">›</span>
			<a href="<?php echo esc_url( home_url( '/jornadas/' ) ); ?>">Jornadas</a>
			<span class="doo-jornada-detail__breadcrumb-sep">›</span>
			<span class="doo-jornada-detail__breadcrumb-current"><?php echo esc_html( $title ); ?></span>
		</div>
	</nav>

	<!-- Hero Section -->
	<header class="doo-jornada-detail__hero" style="background: <?php echo esc_attr( $bg_light ); ?>;">
		<div class="doo-jornada-detail__hero-container">
			<div class="doo-jornada-detail__hero-content">
				<?php if ( $edition ) : ?>
					<span class="doo-jornada-detail__edition" style="color: <?php echo esc_attr( $accent_color ); ?>;">
						<?php echo esc_html( $edition ); ?> Jornada
					</span>
				<?php endif; ?>
				
				<h1 class="doo-jornada-detail__title" style="color: <?php echo esc_attr( $text_navy ); ?>;">
					<?php echo esc_html( $title ); ?>
				</h1>
				
				<?php if ( $date ) : ?>
					<div class="doo-jornada-detail__meta">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="<?php echo esc_attr( $accent_color ); ?>" stroke-width="2">
							<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
							<line x1="16" y1="2" x2="16" y2="6"></line>
							<line x1="8" y1="2" x2="8" y2="6"></line>
							<line x1="3" y1="10" x2="21" y2="10"></line>
						</svg>
						<span><?php echo esc_html( $date ); ?></span>
					</div>
				<?php endif; ?>
				
				<?php if ( $description ) : ?>
					<p class="doo-jornada-detail__description">
						<?php echo esc_html( $description ); ?>
					</p>
				<?php endif; ?>
			</div>
			
			<?php if ( $image ) : ?>
				<div class="doo-jornada-detail__hero-image">
					<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $title ); ?>" />
				</div>
			<?php endif; ?>
		</div>
	</header>

	<!-- Content placeholder for future expansion -->
	<section class="doo-jornada-detail__content">
		<div class="doo-jornada-detail__content-container">
			<p class="doo-jornada-detail__placeholder">
				Más información sobre esta jornada estará disponible próximamente.
			</p>
		</div>
	</section>
</article>
