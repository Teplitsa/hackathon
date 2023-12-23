var path = require('path');
var name = path.basename(__dirname);

var basePaths = { // Paths for source and bundled parts of app
		src: 'src/', dest: 'assets/', npm: 'node_modules/'
	},
	gulp = require( 'gulp' ), // Require plugins
	es = require( 'event-stream' ),
	zip = require('gulp-zip');
	gutil = require( 'gulp-util' ),
	prettier = require('gulp-prettier'),
	bourbon = require( 'node-bourbon' ),
	path = require( 'relative-path' ),
	runSequence = require( 'run-sequence' ),
	lec = require( 'gulp-line-ending-corrector' ),
	plugins = require( 'gulp-load-plugins' )({ // Plugins - load gulp-* plugins without direct calls
		pattern: [ 'gulp-*', 'gulp.*' ], replaceString: /\bgulp[\-.]/
	}),
	concat = require('gulp-concat'),
	jsImport = require('gulp-js-import'),
	// Env - call gulp --prod to go into production mode
	sassStyle = 'expanded', // SASS syntax
	sourceMap = false, // Wheter to build source maps
	isProduction = false, // Mode flag
	changeEvent = function( evt ) { // Log
		gutil.log( 'File', gutil.colors.cyan( evt.path.replace( new RegExp( '/.*(?=/' + basePaths.src + ')/' ), '' ) ), 'was', gutil.colors.magenta( evt.type ) );
	};

if ( true === gutil.env.prod ) {
	isProduction = true;
	sassStyle = 'compressed';
	sourceMap = false;
}

const run     = require('gulp-run');

var jsImport = require('gulp-js-import');

//js
gulp.task( 'js', function() {
	var vendorFiles = [
		//basePaths.npm + 'imagesloaded/imagesloaded.pkgd.js'
	], appFiles = [ basePaths.src + 'js/*' ]; //our own JS files

	return gulp.src( vendorFiles.concat( appFiles ) ) //join them
		.pipe( plugins.filter( '*.js' ) )//select only .js ones
		.pipe( plugins.concat( 'scripts.js' ) )//combine them into bundle.js
		.pipe(jsImport({
			hideConsole: true,
			importStack: false
		}))
		.pipe( plugins.size() ) //print size for log
		.on( 'error', console.log ) //log
		.pipe( gulp.dest( basePaths.dest + 'js' ) ); //write results into file
});

// CSS
gulp.task( 'css', function() {

	// Paths for mdl and bourbon
	var paths = require( 'node-bourbon' ).includePaths;
	paths.push( basePaths.npm + 'modularscale-sass/stylesheets' );
	var vendorFiles = gulp.src('.', {allowEmpty: true}),//gulp.src( [] ), // Components
		appFiles = gulp.src( basePaths.src + 'sass/styles.scss' ) // Main file with @import-s
			.pipe( plugins.sass( {
				outputStyle: sassStyle, // SASS syntax
				indentType: 'tab',
				indentWidth: 1,
				includePaths: paths // Add bourbon
			} ).on( 'error', plugins.sass.logError ) ) // SASS own error log
			.pipe( plugins.autoprefixer( { // Aautoprefixer
				browsers: [ 'last 4 versions' ], cascade: false
			} ) )
			.on( 'error', console.log ); // Log

	return es.concat( appFiles, vendorFiles ) // Combine vendor CSS files and our files after-SASS
		.pipe( plugins.concat( 'style.css' ) ) // Combine into file
		.pipe( isProduction ? plugins.cssmin() : gutil.noop() ) // Minification on production
		.pipe( plugins.size() ) // Display size
		.pipe( gulp.dest( basePaths.dest + 'css' ) ) // Write file
		.on( 'error', console.log ); // Log
});

// Editor CSS
gulp.task( 'css-editor', function() {

	// Paths for mdl and bourbon
	var paths = require( 'node-bourbon' ).includePaths;
	paths.push( basePaths.npm + 'modularscale-sass/stylesheets' );
	var vendorFiles = gulp.src('.', {allowEmpty: true}),
		appFiles = gulp.src( basePaths.src + 'sass/style-editor.scss' )
			.pipe( plugins.sass( {
				outputStyle: sassStyle, // SASS syntax
				indentType: 'tab',
				indentWidth: 1,
				includePaths: paths // Add bourbon
			} ).on( 'error', plugins.sass.logError ) ) // SASS own error log
			.pipe( plugins.autoprefixer( { // Aautoprefixer
				browsers: [ 'last 4 versions' ], cascade: false
			} ) )
			.on( 'error', console.log );

	return es.concat( appFiles, vendorFiles ) // Combine vendor CSS files and our files after-SASS
		.pipe( plugins.concat( 'style-editor.css' ) ) // Combine into file
		.pipe( isProduction ? plugins.cssmin() : gutil.noop() ) // Minification on production
		.pipe( plugins.size() ) // Display size
		.pipe( gulp.dest( basePaths.dest + 'css' ) ) // Write file
		.on( 'error', console.log ); // Log
});

// Admin CSS
gulp.task( 'css-admin', function() {

	// Paths for mdl and bourbon
	var paths = require( 'node-bourbon' ).includePaths;
	paths.push( basePaths.npm + 'modularscale-sass/stylesheets' );
	var vendorFiles = gulp.src('.', {allowEmpty: true}),//gulp.src( [] ), // Components
		appFiles = gulp.src( basePaths.src + 'sass/admin.scss' ) // Main file with @import-s
			.pipe( plugins.sass( {
				outputStyle: sassStyle, // SASS syntax
				indentType: 'tab',
				indentWidth: 1,
				includePaths: paths // Add bourbon
			} ).on( 'error', plugins.sass.logError ) ) // SASS own error log
			.pipe( plugins.autoprefixer( { // Aautoprefixer
				browsers: [ 'last 4 versions' ], cascade: false
			} ) )
			.on( 'error', console.log ); // Log

	return es.concat( appFiles, vendorFiles ) // Combine vendor CSS files and our files after-SASS
		.pipe( plugins.concat( 'admin.css' ) ) // Combine into file
		.pipe( isProduction ? plugins.cssmin() : gutil.noop() ) // Minification on production
		.pipe( plugins.size() ) // Display size
		.pipe( gulp.dest( basePaths.dest + 'css' ) ) // Write file
		.on( 'error', console.log ); // Log
});

// Svg - combine and clear svg assets
gulp.task( 'svg', function() {

	var icons = gulp.src( [ basePaths.src + 'svg/icon-*.svg' ] ).pipe( plugins.svgmin( {
			plugins: [
				{
					removeTitle: true,
					removeDesc: { removeAny: true },
					removeEditorsNSData: true,
					removeComments: true
				}
			]
		} ) ) // Minification
		.pipe( plugins.cheerio( {
			run: function( $ ) { //remove fill from icons
				$( '[fill]' ).removeAttr( 'fill' );
			}, parserOptions: { xmlMode: true }
		} ) );

	// Combine for inline usage
	return es.concat( icons ).pipe( plugins.svgstore( { inlineSvg: true } ) )
		.pipe( plugins.cheerio({
			run: ($) => {
				$('svg').addClass('hms-icons-file');
			},
		}))

		.pipe( plugins.concat( 'icons.svg' ) )//className: '.icon.icon--%s:hover',
		.pipe( gulp.dest( basePaths.dest + 'svg' ) );
});

// Builds
gulp.task( 'full-build',
	gulp.series( 'css', 'css-editor', 'css-admin', 'js', 'svg' )
);

// Watchers
gulp.task( 'watch', () => {
	gulp.watch(
		[ basePaths.src + 'js/*.js', basePaths.src + 'js/**/*.js' ],
		gulp.series( [ 'js' ] )
	);
	gulp.watch(
		[ basePaths.src + 'sass/*.scss', basePaths.src + 'sass/**/*.scss' ],
		gulp.series([ 'css', 'css-editor', 'css-admin' ] )
	);
});

// check encoding + line-endings
gulp.task('lec', function() {
    gulp.src(['./**/*', '!node_modules/**', '!vendor/**'])
        .pipe(lec({verbose: true, eolc: 'LF', encoding: 'utf8'}))
        .pipe(gulp.dest('./'));
});

// Default
gulp.task( 'default', gulp.series( 'full-build', 'watch' ) );

// Archive
gulp.task('zip', function(){

	const distFiles = [
		'**',
		'!src/**',
		'!node_modules/**',
		'!vendor/**',
		'!TEMP/**',
		'!.gitignore',
		'!gulpfile.js',
		'!LICENSE.txt',
		'!backlog.txt',
		'!README.md',
		'!CHANGELOG.md',
		'!package.json',
		'!package-lock.json',
		'!composer.json',
		'!composer.lock',
		'!phpcs.xml',
		'!**.zip'
	];

	return gulp.src( distFiles, { base: '../' } )
		.pipe( zip( name + '.zip' ) )
		.pipe( gulp.dest( './' ) )
});
