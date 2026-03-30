import { defineConfig } from 'vite';
import { resolve } from 'path';

export default defineConfig( {
	base: './',
	build: {
		outDir: 'dist',
		emptyDirOnBuild: true,
		manifest: true,
		rollupOptions: {
			input: {
				main: resolve( __dirname, 'src/js/main.js' ),
			},
		},
	},
	css: {
		preprocessorOptions: {
			scss: {
				api: 'modern-compiler',
			},
		},
	},
	server: {
		port: 5173,
		strictPort: true,
		cors: true,
		origin: 'http://localhost:5173',
	},
} );