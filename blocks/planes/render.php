<?php
/**
 * Block render: doo/planes.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content.
 * @var WP_Block $block      Block instance.
 *
 * @package DooFvmpTheme
 */

if ( ! function_exists( 'doo_planes_image_url' ) ) {
	/**
	 * Resolve image URL: attachment, override URL, or theme asset.
	 *
	 * @param int    $image_id Image attachment ID.
	 * @param string $image_url Manual URL.
	 * @param string $fallback Theme-relative fallback filename under assets/images/.
	 * @return string
	 */
	function doo_planes_image_url( $image_id, $image_url, $fallback ) {
		if ( $image_id ) {
			$url = wp_get_attachment_image_url( (int) $image_id, 'medium_large' );
			if ( $url ) {
				return $url;
			}
		}
		if ( $image_url ) {
			return $image_url;
		}
		return DOO_THEME_URI . '/assets/images/' . $fallback;
	}
}

if ( ! function_exists( 'doo_planes_render_cards' ) ) {
	/**
	 * @param array<int, array{img:string,alt:string,title:string,sub:string,linkText:string,linkUrl:string}> $plans Plans.
	 * @return void
	 */
	function doo_planes_render_cards( $plans ) {
		?>
		<div class="doo-planes__cards">
			<?php foreach ( $plans as $plan ) : ?>
			<div class="doo-plan-dl-card">
				<div class="doo-plan-dl-card__img">
					<img src="<?php echo esc_url( $plan['img'] ); ?>" alt="<?php echo esc_attr( $plan['alt'] ); ?>" />
				</div>
				<div class="doo-plan-dl-card__body">
					<p class="doo-plan-dl-card__title"><?php echo esc_html( $plan['title'] ); ?></p>
					<p class="doo-plan-dl-card__sub"><?php echo esc_html( $plan['sub'] ); ?></p>
					<a class="doo-plan-dl-card__link" href="<?php echo esc_url( $plan['linkUrl'] ); ?>"><?php echo esc_html( $plan['linkText'] ); ?></a>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}

$a = wp_parse_args(
	isset( $attributes ) && is_array( $attributes ) ? $attributes : array(),
	array(
		'title'       => 'Planes formativos 2026',
		'description' => 'Descarga los planes de formación del año en curso para los empleados de la administración local valenciana.',
		'pimImageId'  => 0,
		'pimImageUrl' => '',
		'pimImageAlt' => "Pla de Formació Programa d'Innovació Municipal 2026",
		'pimTitle'    => "Programa d'Innovació Municipal",
		'pimSub'      => "Dirigit a EEPP de l'administració local",
		'pimLinkText' => 'Acceder al plan →',
		'pimLinkUrl'  => '#',
		'fapImageId'  => 0,
		'fapImageUrl' => '',
		'fapImageAlt' => "Pla de Formació Atenció Primària 2026",
		'fapTitle'    => 'Formació Atenció Primària',
		'fapSub'      => "Dirigit als equips d'Atenció Primària de l'administració local",
		'fapLinkText' => 'Acceder al plan →',
		'fapLinkUrl'  => '#',
		'fcImageId'   => 0,
		'fcImageUrl'  => '',
		'fcImageAlt'  => "Pla de Formació Contínua 2026",
		'fcTitle'     => 'Formació Contínua',
		'fcSub'       => "Dirigit a EEPP de l'administració local",
		'fcLinkText'  => 'Acceder al plan →',
		'fcLinkUrl'   => '#',
	)
);

$plans = array(
	array(
		'img' => doo_planes_image_url( $a['pimImageId'], $a['pimImageUrl'], 'pim.png' ),
		'alt' => $a['pimImageAlt'],
		'title' => $a['pimTitle'],
		'sub'   => $a['pimSub'],
		'linkText' => $a['pimLinkText'],
		'linkUrl'  => $a['pimLinkUrl'],
	),
	array(
		'img' => doo_planes_image_url( $a['fapImageId'], $a['fapImageUrl'], 'fap.png' ),
		'alt' => $a['fapImageAlt'],
		'title' => $a['fapTitle'],
		'sub'   => $a['fapSub'],
		'linkText' => $a['fapLinkText'],
		'linkUrl'  => $a['fapLinkUrl'],
	),
	array(
		'img' => doo_planes_image_url( $a['fcImageId'], $a['fcImageUrl'], 'fc.png' ),
		'alt' => $a['fcImageAlt'],
		'title' => $a['fcTitle'],
		'sub'   => $a['fcSub'],
		'linkText' => $a['fcLinkText'],
		'linkUrl'  => $a['fcLinkUrl'],
	),
);

if ( ! empty( $attributes['renderOnlyCards'] ) && ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
	doo_planes_render_cards( $plans );
	return;
}
?>
<section class="doo-planes">
	<div class="doo-planes__container">

		<h2 class="doo-planes__title"><?php echo esc_html( $a['title'] ); ?></h2>
		<p class="doo-planes__desc"><?php echo wp_kses_post( $a['description'] ); ?></p>

		<?php doo_planes_render_cards( $plans ); ?>
	</div>
</section>
