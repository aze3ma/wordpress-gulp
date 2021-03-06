var gulp = require('gulp');
var gulpIf = require('gulp-if');
var uglify = require('gulp-uglify');
var minifyCss = require('gulp-cssnano');
var del = require('del');
var cache = require('gulp-cache');
var imagemin = require('gulp-imagemin');
var bump = require('gulp-bump');
var touch = require('gulp-touch');
var concat = require('gulp-concat');
var git = require('gulp-git');
var githubReleaser = require('conventional-github-releaser');
var fs = require('fs');
var postcss = require('gulp-postcss');
var atImport = require('postcss-import');
var customProperties = require('postcss-custom-properties');
var customMedia = require('postcss-custom-media');
var shortSize = require('postcss-short-size');
var simpleVars = require('postcss-simple-vars');
var nested = require('postcss-nested');
var colorFunction = require('postcss-color-function');
var calc = require('postcss-calc');
var autoprefixer = require('autoprefixer');

var paths = {
  dist: 'dist',
  styles: {
    src: 'src/assets/styles/*.css',
    dest: 'src'
  },
  scripts: {
    src: 'src/assets/scripts/**/*.js',
    dest: 'src'
  },
  images: {
    src: 'src/assets/images/**/*',
    dest: 'src/assets/images'
  }
};

function clean() {
  return del([ paths.dist ]);
}

function getPackageJsonVersion() {
  // Parse the JSON file instead of using require because require
  // caches multiple calls so the version number won't be updated
  return JSON.parse(fs.readFileSync('./package.json', 'utf8')).version;
}

function styles() {
  return gulp.src(paths.styles.src)
    .pipe(postcss([
      atImport(),
      nested(),
      simpleVars(),
      customProperties(),
      customMedia(),
      shortSize(),
      colorFunction(),
      calc(),
      autoprefixer()
    ]))
    .pipe(gulp.dest(paths.styles.dest));
}

function scripts() {
  return gulp.src(paths.scripts.src)
    .pipe(concat('script.js'))
    .pipe(gulp.dest(paths.scripts.dest));
}

function images() {
  return gulp.src(paths.images.src)
    .pipe(cache(imagemin({
      progressive: true,
      interlaced: true,
      svgoPlugins: [{ cleanupIDs: false }]
    })))
    .pipe(gulp.dest(paths.images.dest));
}

function copy() {
  return gulp.src([
    'src/**/*',
    '!src/assets/scripts',
    '!src/assets/scripts/**',
    '!src/assets/styles',
    '!src/assets/styles/**'
  ], {
    dot: true
  })
  .pipe(gulpIf('*.js', uglify()))
  .pipe(gulpIf('*.css', minifyCss()))
  .pipe(gulp.dest(paths.dist));
}

function watch() {
  gulp.watch('src/assets/styles/**/*.css', styles);
  gulp.watch(paths.scripts.src, scripts);
  gulp.watch(paths.images.src, images);
  gulp.watch('src/**/*.php', copy);
}

function bumpVersion() {
  return gulp.src([
    'package.json',
    'src/assets/styles/style.css',
    'src/functions.php',
    'src/readme.txt',
  ], { base: './' })
  .pipe(bump())
  .pipe(gulp.dest('./'))
  // Touch the files to ensure changes are
  // picked up by Git: https://git.io/vMNKZ
  .pipe(touch());
}

function commitChanges() {
  var version = getPackageJsonVersion();
  return gulp.src('.')
    .pipe(git.add())
    .pipe(git.commit(version));
}

function createNewTag(cb) {
  var version = getPackageJsonVersion();
  return git.tag(version, 'Created Tag for version: ' + version, function (error) {
    if (error) {
      return cb(error);
    }
  });
}

function pushChanges(cb) {
  return git.push('origin', 'master', cb);
}

function createRelease(done) {
  githubReleaser({
    type: 'oauth',
    token: process.env.CONVENTIONAL_GITHUB_RELEASER_TOKEN
  }, done);
}

exports.clean = clean;
exports.styles = styles;
exports.scripts = scripts;
exports.images = images;

var build = gulp.series(clean, gulp.parallel(styles, scripts, images), copy);

gulp.task('watch', gulp.series(build, watch));
gulp.task('release', gulp.series(bumpVersion, build, commitChanges, createNewTag, pushChanges, createRelease));
gulp.task('build', build);
gulp.task('default', build);
