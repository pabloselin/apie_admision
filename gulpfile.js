var gulp = require('gulp');
var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var concat = require('gulp-concat');
var concatCss = require('gulp-concat-css');
var cleanCss = require('gulp-clean-css');
var minify = require('gulp-minify');
var watch = require('gulp-watch');
var browserSync = require('browser-sync').create();
var reload = browserSync.reload;


gulp.task('default', function() {
  // place code for your default task here
});

gulp.task('sass', function() {
	 return gulp.src('css/main.scss')
		  		.pipe(sass().on('error', sass.logError) )
		  		.pipe(autoprefixer())
		  		.pipe(gulp.dest('css'))
		  		.pipe(browserSync.stream());
  	});

gulp.task('concatcss', function() {
	return gulp.src([
		'./lib/dynatable/jquery.dynatable.css',
		'./lib/pickadate/lib/themes/default.css',
		'./lib/pickadate/lib/themes/default.date.css',
		'./css/postulacion.css'
		])
		.pipe(concatCss('apie-admision.css')).
		pipe(gulp.dest('css/'));
});

gulp.task('cleancss', function() {
	return gulp.src('css/apie-admision.css')
		.pipe(cleanCss())
		.pipe(gulp.dest('css/'));
});

gulp.task('scripts', function() {
	return gulp.src([
		'./lib/dynatable/jquery.dynatable.js',
		'./lib/jquery-validation/dist/jquery.validate.js',
		'./lib/jquery.rut/jquery.rut.js',
		'./lib/pickadate/lib/picker.js',
		'./lib/pickadate/lib/picker.date.js',
		'./js/src/funciones-postulacion.js',
		'./js/src/functiones-frontadmin-postulacion.js'
		])
		.pipe(concat('apie-admision.js'))
		.pipe(gulp.dest('./js/'))
		.pipe(browserSync.stream());
});

gulp.task('watch', function() {
	browserSync.init({
		proxy: 'cma.dev'
	});
	gulp.watch([
		'css/src/*.scss',
		'js/src/*.js'
		], 
		[
		'sass',
		'concatcss',
		'cleancss', 
		'scripts'
		]);
});

gulp.watch(['*.php', '*/*/*.php']).on('change', reload);