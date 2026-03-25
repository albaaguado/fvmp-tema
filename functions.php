<?php
/**
 * DW-Tema - Functions
 *
 * @package DW-Tema
 */     

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Seguridad: evita acceso directo
}

/**
 * Encolar estilos del tema
 */
function dw_tema_enqueue_styles() {
    wp_enqueue_style(
        'dw-tema-style',
        get_stylesheet_uri(),
        array(),
        wp_get_theme()->get( 'Version' )
    );
}
add_action( 'wp_enqueue_scripts', 'dw_tema_enqueue_styles' );