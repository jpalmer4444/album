var gulp = require('gulp');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');

var vendorFiles = [
        'js/datatables.cdn.js',
		'js/bootstrap.min.js',
		'js/bootstrap-datepicker.min.js',
		'js/bootstrap-select.min.js',
		'js/bootstrap-notify.min.js',
        'js/clean-blog.js',
        'js/jquery.validate.min.js',
        'js/additional-methods.min.js'
	];

gulp.task('vendor', function(){
	return gulp.src(vendorFiles)
		.pipe(concat('vendor.js'))
		.pipe(uglify())
		.pipe(gulp.dest('js/dist/'));
});

gulp.task('watch-vendor', function(){
	gulp.watch(vendorFiles, ['vendor'])
});

gulp.task('default', ['vendor']);
