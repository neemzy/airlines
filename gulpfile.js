var gulp = require('gulp'),
    tasks = require('gulp-load-plugins')(),
    rimraf = require('rimraf'),
    fs = require('fs'),
    open = './open.json',
    src = 'app/Resources/assets/',
    dist = 'web/';

gulp.task(
    'clean',
    function (callback) {
        rimraf.sync(dist + 'app.css');
        rimraf.sync(dist + 'app.js');
        rimraf.sync(dist + 'fonts/');
        callback();
    }
);

gulp.task(
    'stylesheets',
    function () {
        gulp.src(src + 'stylesheets/app.less')
            .pipe(tasks.plumber())
            .pipe(tasks.less())
            .pipe(tasks.autoprefixer())
            .pipe(tasks.if(tasks.util.env.dist, tasks.csso()))
            .pipe(gulp.dest(dist))
            .pipe(tasks.livereload());
    }
);

gulp.task(
    'fonts',
    function () {
        gulp.src('node_modules/bootstrap/dist/fonts/*')
            .pipe(gulp.dest(dist + 'fonts/'));
    }
);

gulp.task(
    'scripts',
    function () {
        // Define environment for React according to build mode
        tasks.env({ vars: { NODE_ENV: tasks.util.env.dist ? 'production' : 'development' } });

        gulp.src(src + 'scripts/app.js', { read: false })
            .pipe(tasks.plumber())
            .pipe(tasks.jshint())
            .pipe(tasks.jshint.reporter('default'))
            .pipe(tasks.browserify({
                debug: tasks.util.env.dist,
                insertGlobals: false,
                transform: ['reactify'],
                extensions: ['.jsx']
            }))
            .pipe(tasks.if(tasks.util.env.dist, tasks.uglify()))
            .pipe(gulp.dest(dist))
            .pipe(tasks.livereload());
    }
);

gulp.task(
    'workflow',
    function () {
        if (tasks.util.env.dist) {
            return;
        }

        fs.stat(
            open,
            function (err) {
                var cb = function() {
                    gulp.src('gulpfile.js')
                        .pipe(tasks.open('', require(open)));
                };

                if (null === err) {
                    cb();
                } else {
                    console.log('gulp-open configuration file not found, creating it with default values...');
                    fs.writeFile(open, '{"url": "http://localhost:8000/app_dev.php"}', cb);
                }
            }
        );

        tasks.livereload.listen(
            35729,
            function (err) {
                gulp.watch(src + 'stylesheets/**/*.less', ['stylesheets']);
                gulp.watch(src + 'scripts/**/*', ['scripts']);

                gulp.watch(
                    ['app/Resources/views/**/*.twig', 'src/Airlines/AppBundle/Resources/views/**/*.twig'],
                    function () {
                        gulp.src('').pipe(tasks.livereload());
                    }
                );
            }
        );
    }
);

gulp.task('default', ['clean', 'stylesheets', 'fonts', 'scripts', 'workflow']);
