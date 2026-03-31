<?php
/**
 * Block render: doo/profesorado.
 *
 * Section headers come from block attributes (editable in the editor).
 * Teacher grid is dynamic from the doo_docente CPT.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content.
 * @var WP_Block $block      Block instance.
 *
 * @package DooFvmpTheme
 */

$eyebrow     = $attributes['eyebrow'];
$title       = $attributes['title'];
$description = $attributes['description'];

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
	return;
}

$linkedin_svg  = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-label="LinkedIn"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect width="4" height="12" x="2" y="9"/><circle cx="4" cy="4" r="2"/></svg>';
$twitter_svg   = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-label="Twitter"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.4 5.6 3.9 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>';
$instagram_svg = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-label="Instagram"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>';
$facebook_svg  = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-label="Facebook"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>';
?>
<section class="doo-profesorado">
	<div class="doo-profesorado__container">

		<p class="doo-profesorado__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
		<h2 class="doo-profesorado__title"><?php echo esc_html( $title ); ?></h2>
		<p class="doo-profesorado__desc"><?php echo wp_kses_post( $description ); ?></p>

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
