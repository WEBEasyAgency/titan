import gulp from 'gulp';
import imagemin from 'gulp-imagemin';
import concat from 'gulp-concat';
import sync from 'browser-sync';
import gulpSass from 'gulp-sass';
import nodeSass from "node-sass";
const sass = gulpSass(nodeSass);
import cleancss from 'gulp-clean-css';
const browserSync = sync.create();
import autoprefixer from 'gulp-autoprefixer';
import terser from 'gulp-terser';
import newer from 'gulp-newer';
import fileinclude from 'gulp-file-include';
import {deleteAsync} from 'del';

function browsersync(){
	browserSync.init({
		server: {baseDir: 'app/'},
		notify: false,
		online: true
	});
}

function libscss(){
	return gulp.src([
		'app/scss/reset.css',
		'app/scss/jquery.fancybox.min.css',
		'node_modules/swiper/swiper-bundle.min.css',
	])
	.pipe(concat('libs.min.css'))
	.pipe(cleancss({level: {1: {specialComments: 0}}}))
	.pipe(gulp.dest('app/css'));
}

function libsjs(){
	return gulp.src([
		'node_modules/jquery/dist/jquery.min.js',
		'node_modules/swiper/swiper-bundle.js',
		'app/js/jquery.inputmask.min.js',
		'app/js/jquery.fancybox.min.js'
	])
	.pipe(concat('libs.min.js'))
	.pipe(terser())
	.pipe(gulp.dest('app/js/'))
}

function scripts(){
	return gulp.src([
		'app/js/app.js'
	])
	.pipe(concat('app.min.js'))
	.pipe(terser())
	.pipe(gulp.dest('app/js/'))
	.pipe(browserSync.stream())
}

function styles() {
	return gulp.src([
		'app/scss/blocks.scss',
		'app/scss/main.scss'
	])
	.pipe(sass())
	.pipe(autoprefixer({overrideBrowserlist: ['last 10 versions'], grid: true}))
	.pipe(cleancss({level: {1: {specialComments: 0}}}))
	.pipe(concat('app.min.css'))
	.pipe(gulp.dest('app/css/'))
	.pipe(browserSync.stream())
}

function images() {
	return gulp.src('app/img/src/**/*', {
		encoding: false
	})
	.pipe(newer('app/img/dest/'))
	.pipe(imagemin())
	.pipe(gulp.dest('app/img/dest/'))
}

function cleanimg(){
	return deleteAsync('app/img/dest/**/*', {force:true});
}

function cleandist(){
	return deleteAsync('dist/**/*', {force:true});
}

function includeHTML() {
	return gulp.src('app/pages/*.html') // Source HTML files to process
		.pipe(fileinclude({
			prefix: '@@',
			basepath: '@file'
		}))
		.pipe(gulp.dest('app/')); 
}

function buildcopy() {
	return gulp.src([
		'app/css/libs.min.css',
		'app/css/app.min.css',
		'app/js/libs.min.js',
		'app/js/app.min.js',
		'app/img/dest/**',
		'app/fonts/**',
		'app/**/*.html',
	], {base: 'app', encoding: false})
	.pipe(gulp.dest('dist'));
}

function startwatch(){
	gulp.watch(['app/**/scss/**/*'], styles);
	gulp.watch(['app/**/*.js', '!app/**/*.min.js'], scripts);
	gulp.watch('app/pages/*.html').on('change', includeHTML);
	gulp.watch('app/html/*.html').on('change', includeHTML);
	gulp.watch('app/**/*.html').on('change', browserSync.reload);
	gulp.watch('app/images/src/**/*', images);
}

export const browsersyncRun = browsersync
export const scriptsRun = scripts
export const stylesRun = styles
export const imagesRun = images
export const cleanimgRun = cleanimg
export const libscssRun = libscss
export const libsjsRun = libsjs
export const includeHTMLRun = includeHTML
export const build = gulp.series(cleandist, styles, scripts, images, buildcopy)

export default gulp.parallel(libscss, styles, libsjs, scripts, images, includeHTML, browsersync, startwatch)