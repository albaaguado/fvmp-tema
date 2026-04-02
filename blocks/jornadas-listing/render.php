<?php
/**
 * Jornadas Listing Block Render Template.
 *
 * @package DooFvmpTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section_title  = isset( $attributes['sectionTitle'] ) ? $attributes['sectionTitle'] : 'Próximas Jornadas';
$posts_per_page = isset( $attributes['postsPerPage'] ) ? intval( $attributes['postsPerPage'] ) : 6;

$jornadas = get_posts(
	array(
		'post_type'      => 'doo_jornada',
		'posts_per_page' => $posts_per_page,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	)
);
?>

<section class="doo-jornadas-listing">
	<div class="doo-jornadas-listing__container">
		<?php if ( ! empty( $section_title ) ) : ?>
			<h2 class="doo-jornadas-listing__title"><?php echo esc_html( $section_title ); ?></h2>
		<?php endif; ?>

		<?php if ( ! empty( $jornadas ) ) : ?>
			<div class="doo-jornadas-listing__grid">
				<?php foreach ( $jornadas as $jornada ) : 
					$edition     = get_post_meta( $jornada->ID, 'doo_jornada_edition', true );
					$date        = get_post_meta( $jornada->ID, 'doo_jornada_date', true );
					$description = get_post_meta( $jornada->ID, 'doo_jornada_description', true );
					$image       = get_the_post_thumbnail_url( $jornada->ID, 'medium_large' );
					$linked_page = get_post_meta( $jornada->ID, 'doo_linked_page_id', true );
					$link        = $linked_page ? get_permalink( $linked_page ) : '#';
				?>
					<article class="doo-jornada-card">
						<?php if ( $image ) : ?>
							<div class="doo-jornada-card__image">
								<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $jornada->post_title ); ?>" loading="lazy" />
							</div>
						<?php else : ?>
							<div class="doo-jornada-card__image doo-jornada-card__image--placeholder">
								<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
									<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
									<line x1="16" y1="2" x2="16" y2="6"></line>
									<line x1="8" y1="2" x2="8" y2="6"></line>
									<line x1="3" y1="10" x2="21" y2="10"></line>
								</svg>
							</div>
						<?php endif; ?>
						
						<div class="doo-jornada-card__content">
							<span class="doo-jornada-card__badge">Jornada</span>
							
							<h3 class="doo-jornada-card__title">
								<a href="<?php echo esc_url( $link ); ?>">
									<?php echo esc_html( $jornada->post_title ); ?>
								</a>
							</h3>
							
							<?php if ( $date ) : ?>
								<div class="doo-jornada-card__meta">
									<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
										<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
										<line x1="16" y1="2" x2="16" y2="6"></line>
										<line x1="8" y1="2" x2="8" y2="6"></line>
										<line x1="3" y1="10" x2="21" y2="10"></line>
									</svg>
									<span><?php echo esc_html( $date ); ?></span>
								</div>
							<?php endif; ?>
							
							<?php if ( $description ) : ?>
								<p class="doo-jornada-card__description"><?php echo esc_html( $description ); ?></p>
							<?php endif; ?>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<p class="doo-jornadas-listing__empty">No hay jornadas programadas actualmente.</p>
		<?php endif; ?>
	</div>
</section>
