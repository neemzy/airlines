var gulp = require('gulp'),
    tasks = require('gulp-load-plugins')(),
    rimraf = require('rimraf'),
    server = require('tiny-lr')(),
    src = 'app/assets/',
    dist = 'web/';



gulp.task('clean', function (callback) {
    rimraf.sync(dist + 'app.css');
    rimraf.sync(dist + 'app.js');
    callback();
});



gulp.task('stylesheets', function () {
    gulp.src([src + 'stylesheets/app.less'])
        .pipe(tasks.plumber())
        .pipe(tasks.less())
        .pipe(tasks.autoprefixer())
        .pipe(tasks.if(tasks.util.env.dist, tasks.csso()))
        .pipe(gulp.dest(dist))
        .pipe(tasks.livereload(server));
});



gulp.task('scripts', function () {
    gulp.src(src + 'scripts/app.js', { read: false })
        .pipe(tasks.plumber())
        .pipe(tasks.jshint())
        .pipe(tasks.jshint.reporter('default'))
        .pipe(tasks.browserify({
            insertGlobals : false,
            transform: ['reactify'],
            extensions: ['.jsx']
        }))
        .pipe(tasks.if(tasks.util.env.dist, tasks.uglify()))
        .pipe(gulp.dest(dist))
        .pipe(tasks.livereload(server));
});



gulp.task('workflow', function () {
    if (! tasks.util.env.dist) {
        server.listen(35729, function (err) {
            gulp.watch(src + 'stylesheets/**/*.less', ['stylesheets']);
            gulp.watch(src + 'scripts/**/*', ['scripts']);
        });
    }
});



gulp.task('default', [/*'clean', */'stylesheets', 'scripts', 'workflow']);