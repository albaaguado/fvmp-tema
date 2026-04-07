<?php
/**
 * Block render: doo/oferta.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content.
 * @var WP_Block $block      Block instance.
 *
 * @package DooFvmpTheme
 */

if ( ! function_exists( 'doo_oferta_defaults' ) ) {
	/**
	 * Default attribute values (must match block.json defaults).
	 *
	 * @return array<string, string>
	 */
	function doo_oferta_defaults() {
		return array(
			'fcTag'        => 'FC',
			'fcName'       => 'Formación Continua',
			'fcStat1Num'   => '80',
			'fcStat1Label' => 'acciones formativas',
			'fcStat2Num'   => '164',
			'fcStat2Label' => 'cursos programados',
			'fcStat3Num'   => '7200',
			'fcStat3Label' => 'plazas disponibles',
			'fcFeat1'      => '16 sesiones formativas',
			'fcFeat2'      => '3 modalidades de impartición',
			'fcFeat3'      => 'Certificación oficial',
			'fcCtaText'    => 'Ver programa →',
			'fcCtaUrl'     => '#',
			'fapTag'       => 'FAP',
			'fapName'      => 'Formación Atención Primaria',
			'fapStat1Num'  => '45',
			'fapStat1Label' => 'acciones formativas',
			'fapStat2Num'  => '73',
			'fapStat2Label' => 'cursos programados',
			'fapStat3Num'  => '2270',
			'fapStat3Label' => 'plazas disponibles',
			'fapFeat1'     => '8 áreas formativas',
			'fapFeat2'     => '6 modalidades de impartición',
			'fapFeat3'     => 'Homologados por el IVAP',
			'fapCtaText'   => 'Ver programa →',
			'fapCtaUrl'    => '#',
			'pimTag'       => 'PIM',
			'pimName'      => 'Programa Innovación Municipal',
			'pimStat1Num'  => '22',
			'pimStat1Label' => 'acciones formativas',
			'pimStat2Num'  => '45',
			'pimStat2Label' => 'cursos programados',
			'pimStat3Num'  => '1200',
			'pimStat3Label' => 'plazas disponibles',
			'pimFeat1'     => '3 microcredenciales universitarias',
			'pimFeat2'     => '3 modalidades de impartición',
			'pimFeat3'     => 'Homologados por el IVAP',
			'pimCtaText'   => 'Ver programa →',
			'pimCtaUrl'    => '#',
		);
	}
}

if ( ! function_exists( 'doo_oferta_val' ) ) {
	/**
	 * @param array  $attributes Attributes.
	 * @param string $key        Key.
	 * @param array  $defaults   Defaults map.
	 * @return string
	 */
	function doo_oferta_val( $attributes, $key, $defaults ) {
		if ( array_key_exists( $key, $attributes ) && '' !== $attributes[ $key ] ) {
			return (string) $attributes[ $key ];
		}
		return isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
	}
}

if ( ! function_exists( 'doo_oferta_render_plan_cards' ) ) {
	/**
	 * @param array $attributes Block attributes.
	 * @return void
	 */
	function doo_oferta_render_plan_cards( $attributes ) {
		$d = doo_oferta_defaults();
		$cards = array(
			array( 'prefix' => 'fc', 'variant' => '' ),
			array( 'prefix' => 'fap', 'variant' => 'orange' ),
			array( 'prefix' => 'pim', 'variant' => 'navy' ),
		);
		foreach ( $cards as $card ) {
			$p = $card['prefix'];
			$v = $card['variant'];
			$hmod = $v ? ' doo-plan-card__header--' . $v : '';
			$tmod = $v ? ' doo-plan-card__tag--' . $v : '';
			$nmod = $v ? ' doo-plan-card__stat-num--' . $v : '';
			$dmod = $v ? ' doo-plan-card__dot--' . $v : '';
			$cmod = $v ? ' doo-plan-card__cta--' . $v : '';

			$tag   = esc_html( doo_oferta_val( $attributes, $p . 'Tag', $d ) );
			$name  = esc_html( doo_oferta_val( $attributes, $p . 'Name', $d ) );
			$s1n   = esc_html( doo_oferta_val( $attributes, $p . 'Stat1Num', $d ) );
			$s1l   = esc_html( doo_oferta_val( $attributes, $p . 'Stat1Label', $d ) );
			$s2n   = esc_html( doo_oferta_val( $attributes, $p . 'Stat2Num', $d ) );
			$s2l   = esc_html( doo_oferta_val( $attributes, $p . 'Stat2Label', $d ) );
			$s3n   = esc_html( doo_oferta_val( $attributes, $p . 'Stat3Num', $d ) );
			$s3l   = esc_html( doo_oferta_val( $attributes, $p . 'Stat3Label', $d ) );
			$f1    = esc_html( doo_oferta_val( $attributes, $p . 'Feat1', $d ) );
			$f2    = esc_html( doo_oferta_val( $attributes, $p . 'Feat2', $d ) );
			$f3    = esc_html( doo_oferta_val( $attributes, $p . 'Feat3', $d ) );
			$ct    = esc_html( doo_oferta_val( $attributes, $p . 'CtaText', $d ) );
			$curl  = esc_url( doo_oferta_val( $attributes, $p . 'CtaUrl', $d ) );
			?>
			<div class="doo-plan-card">
				<div class="doo-plan-card__header<?php echo esc_attr( $hmod ); ?>">
					<p class="doo-plan-card__tag<?php echo esc_attr( $tmod ); ?>"><?php echo $tag; ?></p>
					<p class="doo-plan-card__name"><?php echo $name; ?></p>
				</div>
				<div class="doo-plan-card__stats">
					<div class="doo-plan-card__stat"><span class="doo-plan-card__stat-num<?php echo esc_attr( $nmod ); ?>"><?php echo $s1n; ?></span><span class="doo-plan-card__stat-label"><?php echo $s1l; ?></span></div>
					<div class="doo-plan-card__stat"><span class="doo-plan-card__stat-num<?php echo esc_attr( $nmod ); ?>"><?php echo $s2n; ?></span><span class="doo-plan-card__stat-label"><?php echo $s2l; ?></span></div>
					<div class="doo-plan-card__stat"><span class="doo-plan-card__stat-num<?php echo esc_attr( $nmod ); ?>"><?php echo $s3n; ?></span><span class="doo-plan-card__stat-label"><?php echo $s3l; ?></span></div>
				</div>
				<div class="doo-plan-card__features">
					<div class="doo-plan-card__feature"><span class="doo-plan-card__dot<?php echo esc_attr( $dmod ); ?>"></span><?php echo $f1; ?></div>
					<div class="doo-plan-card__feature"><span class="doo-plan-card__dot<?php echo esc_attr( $dmod ); ?>"></span><?php echo $f2; ?></div>
					<div class="doo-plan-card__feature"><span class="doo-plan-card__dot<?php echo esc_attr( $dmod ); ?>"></span><?php echo $f3; ?></div>
				</div>
				<a class="doo-plan-card__cta<?php echo esc_attr( $cmod ); ?>" href="<?php echo $curl; ?>"><?php echo $ct; ?></a>
			</div>
			<?php
		}
	}
}

if ( ! empty( $attributes['renderOnlyCards'] ) ) {
	?>
	<div class="doo-oferta__cards">
		<?php doo_oferta_render_plan_cards( $attributes ); ?>
	</div>
	<?php
	return;
}

$eyebrow     = isset( $attributes['eyebrow'] ) ? $attributes['eyebrow'] : 'OFERTA FORMATIVA';
$title       = isset( $attributes['title'] ) ? $attributes['title'] : 'Invierte en tu propia formación';
$description = isset( $attributes['description'] ) ? $attributes['description'] : 'Descubre toda la oferta formativa que ofrece la <strong>Federación Valenciana de Municipios y Provincias</strong> a todos los empleados públicos de la administración local de la Comunitat Valenciana.';
?>
<section class="doo-oferta">
	<div class="doo-oferta__container">

		<p class="doo-oferta__eyebrow"><?php echo wp_kses_post( $eyebrow ); ?></p>
		<h2 class="doo-oferta__title"><?php echo wp_kses_post( $title ); ?></h2>
		<p class="doo-oferta__desc"><?php echo wp_kses_post( $description ); ?></p>

		<div class="doo-oferta__cards">
			<?php doo_oferta_render_plan_cards( $attributes ); ?>
		</div>
	</div>
</section>
