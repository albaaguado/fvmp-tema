import { defineConfig } from 'vite';
import { resolve } from 'path';

/**
 * Vite config for Gutenberg block editor scripts.
 *
 * Compiles src/blocks/index.jsx → build/doo-blocks.js (IIFE).
 * WordPress packages are externalised — they are provided as
 * window globals by the block editor at runtime.
 */

const wpExternals = {
	'@wordpress/blocks': 'wp.blocks',
	'@wordpress/block-editor': 'wp.blockEditor',
	'@wordpress/element': 'wp.element',
	'@wordpress/components': 'wp.components',
	'@wordpress/server-side-render': 'wp.serverSideRender',
	'@wordpress/i18n': 'wp.i18n',
	'@wordpress/api-fetch': 'wp.apiFetch',
	react: 'React',
	'react-dom': 'ReactDOM',
};

export default defineConfig( {
	esbuild: {
		jsx: 'transform',
		jsxFactory: 'createElement',
		jsxFragment: 'Fragment',
		jsxInject: "import { createElement, Fragment } from '@wordpress/element'",
	},
	build: {
		outDir: 'build',
		emptyOutDir: true,
		lib: {
			entry: resolve( __dirname, 'src/blocks/index.jsx' ),
			name: 'DooBlocks',
			fileName: () => 'doo-blocks.js',
			formats: [ 'iife' ],
		},
		rollupOptions: {
			external: Object.keys( wpExternals ),
			output: {
				globals: wpExternals,
			},
		},
	},
} );
