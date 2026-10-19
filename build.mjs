/**
 * Build script for Color or Image Variation Swatches for WooCommerce.
 *
 * Compiles SCSS with Dart Sass (plus autoprefixer) from src/scss and
 * bundles JS with esbuild from src/js into assets/css and assets/js.
 *
 * Usage: node build.mjs [--watch]
 *
 * @version 1.2.3
 * @since   1.2.3
 */

import { build, context } from 'esbuild';
import * as sass from 'sass';
import postcss from 'postcss';
import autoprefixer from 'autoprefixer';
import { watch } from 'chokidar';
import { writeFileSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const root    = dirname( fileURLToPath( import.meta.url ) );
const isWatch = process.argv.includes( '--watch' );

const paths = {
	jsEntry:   join( root, 'src/js/frontend.js' ),
	jsOut:     join( root, 'assets/js/alg-wc-civs.js' ),
	jsOutMin:  join( root, 'assets/js/alg-wc-civs.min.js' ),
	scssEntry: join( root, 'src/scss/frontend.scss' ),
	scssDir:   join( root, 'src/scss' ),
	cssOut:    join( root, 'assets/css/alg-wc-civs.css' ),
	cssOutMin: join( root, 'assets/css/alg-wc-civs.min.css' ),
};

const jsOptions = {
	entryPoints: [ paths.jsEntry ],
	bundle: true,
	format: 'iife',
	target: [ 'es2015' ],
	logLevel: 'info',
};

async function buildJs() {
	await build( { ...jsOptions, outfile: paths.jsOut } );
	await build( { ...jsOptions, minify: true, sourcemap: true, outfile: paths.jsOutMin } );
	console.log( 'Built JS:', paths.jsOut, 'and', paths.jsOutMin );
}

async function buildCss() {
	const prefixer = postcss( [ autoprefixer( { cascade: false } ) ] );

	const expanded   = sass.compile( paths.scssEntry, { style: 'expanded', sourceMap: false } );
	const compressed = sass.compile( paths.scssEntry, { style: 'compressed', sourceMap: false } );

	writeFileSync( paths.cssOut, ( await prefixer.process( expanded.css, { from: undefined, map: false } ) ).css );
	writeFileSync( paths.cssOutMin, ( await prefixer.process( compressed.css, { from: undefined, map: false } ) ).css );

	console.log( 'Built CSS:', paths.cssOut, 'and', paths.cssOutMin );
}

if ( isWatch ) {
	context( { ...jsOptions, outfile: paths.jsOut } ).then( ( ctx ) => ctx.watch() );
	context( { ...jsOptions, minify: true, sourcemap: true, outfile: paths.jsOutMin } ).then( ( ctx ) => ctx.watch() );

	watch( join( paths.scssDir, '**', '*.scss' ), { ignoreInitial: true } ).on( 'all', () => {
		buildCss().catch( ( err ) => console.error( err.message ) );
	} );

	buildCss().catch( ( err ) => console.error( err.message ) );
	console.log( 'Watching for changes in src/js and src/scss…' );
} else {
	await buildJs();
	await buildCss();
}
