<?php
/**
 * Block render: doo/modalidades.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content.
 * @var WP_Block $block      Block instance.
 *
 * @package DooFvmpTheme
 */

if ( ! function_exists( 'doo_modalidades_render_grid' ) ) {
	/**
	 * @param array<int, array{svg:string,name:string,pct:string,desc:string}> $cards Cards.
	 * @return void
	 */
	function doo_modalidades_render_grid( $cards ) {
		?>
		<div class="doo-modalidades__grid">
			<?php foreach ( $cards as $card ) :
				$pct = (int) preg_replace( '/[^0-9]/', '', (string) $card['pct'] );
				if ( $pct > 100 ) {
					$pct = 100;
				}
				if ( $pct < 0 ) {
					$pct = 0;
				}
				?>
			<div class="doo-modal-card">
				<div class="doo-modal-card__header">
					<div class="doo-modal-card__icon" aria-hidden="true">
						<?php echo $card['svg']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- inline SVG ?>
					</div>
					<span class="doo-modal-card__name"><?php echo esc_html( $card['name'] ); ?></span>
					<span class="doo-modal-card__pct"><?php echo esc_html( (string) $pct ); ?>%</span>
				</div>
				<div class="doo-modal-card__bar"><div class="doo-modal-card__fill" style="width:<?php echo esc_attr( $pct ); ?>%"></div></div>
				<p class="doo-modal-card__desc"><?php echo esc_html( $card['desc'] ); ?></p>
			</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}

$a = wp_parse_args(
	isset( $attributes ) && is_array( $attributes ) ? $attributes : array(),
	array(
		'eyebrow'         => 'MODALIDADES',
		'title'           => 'Diferentes modalidades para la oferta formativa',
		'presencialName'  => 'Presencial',
		'presencialPct'   => '28',
		'presencialDesc'  => 'Formación en aula con interacción directa entre docentes y alumnado.',
		'onlineName'      => 'Online',
		'onlinePct'       => '36',
		'onlineDesc'      => 'Aprendizaje flexible a tu ritmo desde cualquier lugar y dispositivo.',
		'streamingName'   => 'Streaming',
		'streamingPct'    => '17',
		'streamingDesc'   => 'Acceso en tiempo real a la formación desde cualquier ubicación.',
		'mixName'         => 'Mix',
		'mixPct'          => '19',
		'mixDesc'         => 'Combina lo mejor de las 3 modalidades en un mismo curso.',
	)
);

$cards = array(
	array(
		'svg' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
		'name' => $a['presencialName'],
		'pct'  => $a['presencialPct'],
		'desc' => $a['presencialDesc'],
	),
	array(
		'svg' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 16V7a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v9m14 0H4m16 0 1.28 2.55a1 1 0 0 1-.9 1.45H3.62a1 1 0 0 1-.9-1.45L4 16"/></svg>',
		'name' => $a['onlineName'],
		'pct'  => $a['onlinePct'],
		'desc' => $a['onlineDesc'],
	),
	array(
		'svg' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>',
		'name' => $a['streamingName'],
		'pct'  => $a['streamingPct'],
		'desc' => $a['streamingDesc'],
	),
	array(
		'svg' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="9" r="7"/><circle cx="15" cy="15" r="7"/></svg>',
		'name' => $a['mixName'],
		'pct'  => $a['mixPct'],
		'desc' => $a['mixDesc'],
	),
);

// Fragmento solo en la petición REST del editor (evita duplicar títulos + no guardar layout roto en el front).
if ( ! empty( $attributes['renderOnlyGrid'] ) && ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
	doo_modalidades_render_grid( $cards );
	return;
}
?>
<section class="doo-modalidades">
	<div class="doo-modalidades__container">

		<p class="doo-modalidades__eyebrow"><?php echo esc_html( $a['eyebrow'] ); ?></p>
		<h2 class="doo-modalidades__title"><?php echo esc_html( $a['title'] ); ?></h2>

		<?php doo_modalidades_render_grid( $cards ); ?>
	</div>
</section>
