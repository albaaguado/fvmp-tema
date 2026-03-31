<?php
/**
 * Block render: doo/testimonios.
 *
 * Encabezado desde atributos del bloque; tarjetas desde el CPT doo_testimonio.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content.
 * @var WP_Block $block      Block instance.
 *
 * @package DooFvmpTheme
 */

$eyebrow = isset( $attributes['eyebrow'] ) ? $attributes['eyebrow'] : 'TESTIMONIOS';
$title   = isset( $attributes['title'] ) ? $attributes['title'] : 'Lo que opina el alumnado';

$query = new WP_Query(
	array(
		'post_type'      => 'doo_testimonio',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'post_status'    => 'publish',
	)
);

if ( ! $query->have_posts() ) {
	return;
}

$color_map = array(
	'teal'   => '#00a9a5',
	'orange' => '#f5a623',
	'navy'   => '#1a2b4a',
);

$allowed_colors = function_exists( 'doo_testimonio_allowed_colors' ) ? doo_testimonio_allowed_colors() : array( 'teal', 'orange', 'navy' );
$color_meta_key = function_exists( 'doo_testimonio_color_meta_key' ) ? doo_testimonio_color_meta_key() : 'doo_testimonio_color';

$testimonials = array();
while ( $query->have_posts() ) {
	$query->the_post();
	$c = get_post_meta( get_the_ID(), $color_meta_key, true );
	if ( ! in_array( $c, $allowed_colors, true ) ) {
		$c = 'teal';
	}
	$raw  = get_post_field( 'post_content', get_the_ID() );
	$text = wp_strip_all_tags( $raw );
	if ( '' === $text ) {
		continue;
	}
	$testimonials[] = array(
		'color' => $c,
		'text'  => $text,
	);
}
wp_reset_postdata();

if ( empty( $testimonials ) ) {
	return;
}

$total_pages = (int) ceil( count( $testimonials ) / 3 );
if ( $total_pages < 1 ) {
	$total_pages = 1;
}
?>
<section class="doo-testimonios">
	<div class="doo-testimonios__container">

		<p class="doo-testimonios__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
		<h2 class="doo-testimonios__title"><?php echo esc_html( $title ); ?></h2>

		<div class="doo-testimonios__slider">

			<button class="doo-testimonios__nav doo-testimonios__nav--prev" aria-label="<?php esc_attr_e( 'Anterior testimonio', 'dw-tema' ); ?>" disabled>
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"/></svg>
			</button>

			<div class="doo-testimonios__overflow">
				<div class="doo-testimonios__track">
					<?php foreach ( $testimonials as $t ) :
						$c     = $t['color'];
						$hex   = isset( $color_map[ $c ] ) ? $color_map[ $c ] : $color_map['teal'];
						$mod   = 'teal' !== $c ? ' doo-testi-card__stripe--' . $c : '';
						$iwrap = 'teal' !== $c ? ' doo-testi-card__icon-wrap--' . $c : '';
						?>
					<div class="doo-testi-card">
						<div class="doo-testi-card__stripe<?php echo esc_attr( $mod ); ?>"></div>
						<div class="doo-testi-card__body">
							<div class="doo-testi-card__icon-wrap<?php echo esc_attr( $iwrap ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" style="color:<?php echo esc_attr( $hex ); ?>"><path d="M9.983 3v7.391c0 5.704-3.731 9.57-8.983 10.609l-.995-2.151c2.432-.917 3.995-3.293 3.995-5.849h-4v-10h9.983zm14.017 0v7.391c0 5.704-3.748 9.571-9 10.609l-.996-2.151c2.433-.917 3.996-3.293 3.996-5.849h-3.983v-10h9.983z"/></svg>
							</div>
							<p class="doo-testi-card__text">"<?php echo esc_html( $t['text'] ); ?>"</p>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>

			<button class="doo-testimonios__nav doo-testimonios__nav--next" aria-label="<?php esc_attr_e( 'Siguiente testimonio', 'dw-tema' ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
			</button>

		</div>

		<div class="doo-testimonios__dots" role="tablist" aria-label="Testimonios">
			<?php for ( $p = 1; $p <= $total_pages; $p++ ) :
				$inactive = 1 !== $p ? ' doo-testimonios__dot--inactive' : '';
				?>
			<button class="doo-testimonios__dot<?php echo esc_attr( $inactive ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Página %d', 'dw-tema' ), $p ) ); ?>"></button>
			<?php endfor; ?>
		</div>

	</div>
</section>
