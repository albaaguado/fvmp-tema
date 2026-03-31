<?php
/**
 * Block render: doo/impacto.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content.
 * @var WP_Block $block      Block instance.
 *
 * @package DooFvmpTheme
 */

if ( ! function_exists( 'doo_impacto_stat_defaults' ) ) {
	/**
	 * Default stat keys and values.
	 *
	 * @return array<string, string>
	 */
	function doo_impacto_stat_defaults() {
		return array(
			'stat1Num'   => '54.000+',
			'stat1Label' => 'Personas formadas',
			'stat2Num'   => '950+',
			'stat2Label' => 'Cursos desde 2016',
			'stat3Num'   => '1.680+',
			'stat3Label' => 'Ediciones realizadas',
			'stat4Num'   => '25+',
			'stat4Label' => 'Áreas formativas',
		);
	}
}

if ( ! function_exists( 'doo_impacto_stat_val' ) ) {
	/**
	 * Resolve a stat attribute with fallback.
	 *
	 * @param array  $attributes Block attributes.
	 * @param string $key        Attribute key.
	 * @param string $default    Default value.
	 * @return string
	 */
	function doo_impacto_stat_val( $attributes, $key, $default ) {
		return array_key_exists( $key, $attributes ) ? (string) $attributes[ $key ] : $default;
	}
}

if ( ! function_exists( 'doo_impacto_render_stats' ) ) {
	/**
	 * Output the four stat cards markup.
	 *
	 * @param array $attributes Block attributes.
	 * @return void
	 */
	function doo_impacto_render_stats( $attributes ) {
		$d = doo_impacto_stat_defaults();
		for ( $i = 1; $i <= 4; $i++ ) {
			$nk = 'stat' . $i . 'Num';
			$lk = 'stat' . $i . 'Label';
			$num   = esc_html( doo_impacto_stat_val( $attributes, $nk, $d[ $nk ] ) );
			$label = esc_html( doo_impacto_stat_val( $attributes, $lk, $d[ $lk ] ) );
			?>
			<div class="doo-stat-card">
				<span class="doo-stat-card__num"><?php echo $num; ?></span>
				<p class="doo-stat-card__label"><?php echo $label; ?></p>
			</div>
			<?php
		}
	}
}

if ( ! empty( $attributes['renderOnlyStats'] ) ) {
	?>
	<div class="doo-impacto__stats">
		<?php doo_impacto_render_stats( $attributes ); ?>
	</div>
	<?php
	return;
}

$eyebrow     = isset( $attributes['eyebrow'] ) ? $attributes['eyebrow'] : 'NUESTRO IMPACTO';
$title       = isset( $attributes['title'] ) ? $attributes['title'] : 'Apostando por la formación desde 1996';
$description = isset( $attributes['description'] ) ? $attributes['description'] : 'Casi tres décadas formando al personal al servicio de la administración local de la Comunitat Valenciana.';
$cta_eyebrow = isset( $attributes['ctaEyebrow'] ) ? $attributes['ctaEyebrow'] : 'EMPIEZA AHORA';
$cta_title   = isset( $attributes['ctaTitle'] ) ? $attributes['ctaTitle'] : 'Explora nuestros cursos y descubre acciones formativas para tu crecimiento profesional';
$cta_desc    = isset( $attributes['ctaDesc'] ) ? $attributes['ctaDesc'] : 'Una amplia oferta formativa orientada tanto a personal de entidades públicas locales como a cargos electos';
$cta_btn     = isset( $attributes['ctaButtonText'] ) ? $attributes['ctaButtonText'] : 'Explorar la oferta formativa';
$cta_url     = isset( $attributes['ctaButtonUrl'] ) ? $attributes['ctaButtonUrl'] : '#';
?>
<section class="doo-impacto">
	<div class="doo-impacto__container">

		<div class="doo-impacto__header">
			<p class="doo-impacto__eyebrow"><?php echo wp_kses_post( $eyebrow ); ?></p>
			<h2 class="doo-impacto__title"><?php echo wp_kses_post( $title ); ?></h2>
			<p class="doo-impacto__desc"><?php echo wp_kses_post( $description ); ?></p>
		</div>

		<div class="doo-impacto__stats">
			<?php doo_impacto_render_stats( $attributes ); ?>
		</div>

		<div class="doo-impacto__divider"></div>

	</div>

	<div class="doo-cta">
		<p class="doo-cta__eyebrow"><?php echo wp_kses_post( $cta_eyebrow ); ?></p>
		<h2 class="doo-cta__title"><?php echo wp_kses_post( $cta_title ); ?></h2>
		<p class="doo-cta__desc"><?php echo wp_kses_post( $cta_desc ); ?></p>
		<a class="doo-cta__btn" href="<?php echo esc_url( $cta_url ); ?>"><?php echo wp_kses_post( $cta_btn ); ?></a>
	</div>
</section>
