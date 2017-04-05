// Include project requirements.
var gulp = require('gulp'),
    uglify = require('gulp-uglify'),
    sass = require('gulp-sass'),
    livereload = require('gulp-livereload'),
    watch = require('gulp-watch'),
    concat = require('gulp-concat'),
    autoprefixer = require('gulp-autoprefixer'),
    sourcemaps = require('gulp-sourcemaps'),
    rename = require("gulp-rename");

// Sets assets folders.
var dirs = {
	frontend: {
		src: {
			js: '../assets/src/frontend/js',
			sass: '../assets/src/frontend/scss'
		},
		dist: {
			js: '../assets/dist/frontend/js',
			css: '../assets/dist/frontend/css',
			vendor: '../assets/dist/frontend/vendor'
		}
	}
};

gulp.task('js-frontend-custom', function () {
    return gulp.src([dirs.frontend.src.js + '/*.js'])
        .pipe(concat('alg-wc-civs.js'))
        .pipe(sourcemaps.write('../maps'))
        .pipe(gulp.dest(dirs.frontend.dist.js))
        .pipe(concat('alg-wc-civs.min.js'))
        .pipe(uglify({
            preserveComments:'license'
        }).on('error', function(e){
            console.log(e.message); return this.end();
        }))
        .pipe(sourcemaps.write('../maps'))
        .pipe(gulp.dest(dirs.frontend.dist.js))
        .pipe(livereload());
});

gulp.task('sass-frontend', function () {
    gulp.src(dirs.frontend.src.sass + '/*.scss')
        .pipe(sass({
            outputStyle: 'compressed'
        }))
        .on('error', sass.logError)
        .pipe(rename("alg-wc-civs.min.css"))
        .pipe(autoprefixer({
            browsers: ['last 3 versions'],
            cascade: false
        }))
        .pipe(gulp.dest(dirs.frontend.dist.css));

    return gulp.src(dirs.frontend.src.sass + '/*.scss')
        .pipe(sourcemaps.init())
        .pipe(sass({
            outputStyle: 'expanded'
        }))
        .on('error', sass.logError)
        .pipe(rename("alg-wc-civs.css"))
        .pipe(autoprefixer({
            browsers: ['last 3 versions'],
            cascade: false
        }))
        .pipe(gulp.dest(dirs.frontend.dist.css))
        .pipe(livereload())
        .pipe(sourcemaps.write('../maps'))
        .pipe(gulp.dest(dirs.frontend.dist.css));
});

gulp.task('watch', ['sass-frontend', 'js-frontend-custom'], function () {
    livereload.listen();
    watch(dirs.frontend.src.js + '/*.js', function () {
        gulp.start('js-frontend-custom');
    });
    watch(dirs.frontend.src.sass + '/**/*.scss', function () {
        gulp.start('sass-frontend');
    });
});

gulp.task('default', function () {
    gulp.start(['sass-frontend', 'js-frontend-custom']);
});