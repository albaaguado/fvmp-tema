<?php
/**
 * One-time migration: move block content from templates to page post_content.
 *
 * After FSE templates were simplified to header + post-content + footer,
 * this function populates the actual page content with the blocks that
 * were previously embedded in the template files.
 *
 * Runs once on admin_init, then sets an option flag to skip future runs.
 *
 * @package DooFvmpTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Find a page by its assigned custom template or by its slug.
 *
 * @param string $template_slug Template slug (e.g. 'page-home').
 * @return WP_Post|null
 */
function doo_find_page_by_template_or_slug( $template_slug ) {
	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'meta_key'       => '_wp_page_template',
			'meta_value'     => $template_slug,
			'posts_per_page' => 1,
			'post_status'    => 'any',
		)
	);

	if ( ! empty( $pages ) ) {
		return $pages[0];
	}

	$slug  = preg_replace( '/^page-/', '', $template_slug );
	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'name'           => $slug,
			'posts_per_page' => 1,
			'post_status'    => 'any',
		)
	);

	if ( ! empty( $pages ) ) {
		return $pages[0];
	}

	if ( 'page-home' === $template_slug ) {
		$front_page_id = (int) get_option( 'page_on_front' );
		if ( $front_page_id ) {
			return get_post( $front_page_id );
		}
	}

	return null;
}

/**
 * Populate page content from migration HTML files.
 *
 * @param array $migrations Template slug => content file pairs.
 * @return void
 */
function doo_apply_migrations( $migrations ) {
	foreach ( $migrations as $template_slug => $content_file ) {
		$page = doo_find_page_by_template_or_slug( $template_slug );

		if ( ! $page ) {
			continue;
		}

		$content_path = DOO_THEME_DIR . '/inc/' . $content_file;

		if ( ! file_exists( $content_path ) ) {
			continue;
		}

		$content = file_get_contents( $content_path );

		if ( false === $content ) {
			continue;
		}

		wp_update_post(
			array(
				'ID'           => $page->ID,
				'post_content' => $content,
			)
		);
	}
}

/**
 * V1: Initial migration — move block content from templates to pages.
 * V2: Replace wp:html blocks with custom doo/* blocks for editor styling.
 *
 * @return void
 */
function doo_migrate_blocks_to_pages() {
	$migrations = array(
		'page-formacion-continua' => 'page-content-fc.html',
		'page-home'               => 'page-content-home.html',
	);

	if ( ! get_option( 'doo_blocks_migrated_v1' ) ) {
		doo_apply_migrations( $migrations );
		update_option( 'doo_blocks_migrated_v1', true );
	}

	if ( ! get_option( 'doo_blocks_migrated_v2' ) ) {
		doo_apply_migrations( $migrations );
		update_option( 'doo_blocks_migrated_v2', true );
	}
}
add_action( 'admin_init', 'doo_migrate_blocks_to_pages' );
