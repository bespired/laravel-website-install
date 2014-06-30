/**
 *		testsite
 */

module.exports = function (grunt) {

	'use strict';

	grunt.initConfig({
		pkg: '<json:package.json>',

		/**
		 * Watch
		 */
		watch: {
			vendor_sass: {
				files: [
					'app/assets/scss/partials/*.scss',
				],
				tasks: ['sass:vendor']
			},

			app_sass: {
				files: [
					'app/assets/scss/**/*.scss',
					'app/assets/bower_components/foundation/scss/**/*.scss',
				],
				tasks: ['sass:app']
			},

			uglify: {
				files: [
					'app/assets/javascripts/*.js',
					'app/assets/javascripts/**/*.js',
				],
				tasks: ['uglify']
			},

			copy: {
				files: [
					'app/assets/js/*',
					'app/assets/stylesheets/*',
					'app/assets/fonts/*',
				],
				tasks: ['copy']
				
			}
		},
		/**
		 * SASS
		 */
		sass: {
			options: {
				style: 'compressed'
			},
			vendor: {
				files: {
					'public/stylesheets/vendor.css': 'app/assets/scss/vendor.scss'
				}
			},
			app: {
				files: {
					'public/stylesheets/application.css': 'app/assets/scss/application.scss'
				}
			}
		},

		/**
		 * Uglify
		 */
		uglify: {
			app: {
				files: {
//					'public/javascripts/vendor/jquery.cycle.all.min.js': [
//						'app/assets/javascripts/vendor/jquery.cycle.all.js'
//					],
					'public/javascripts/application.min.js': [
						'app/assets/javascripts/application.js'
					]
				}
			},

			vendor: {
				files: {
					'public/javascripts/jquery-1.11.0.min.js': 'bower_components/jquery/dist/jquery.js',
					
					'public/javascripts/vendor.min.js': 
					[
						'app/assets/javascripts/plugins/copywidthheight.js',
						'app/assets/javascripts/plugins/autoredirect.js',

						'app/assets/bower_components/foundation/js/vendor/modernizr.js',
						'app/assets/bower_components/foundation/js/foundation.js',
						'app/assets/bower_components/foundation/js/foundation/foundation.topbar.js',
						'app/assets/bower_components/foundation/js/foundation/foundation.tab.js',
						'app/assets/bower_components/foundation/js/foundation/foundation.tooltip.js',
						'app/assets/bower_components/fastclick/lib/fastclick.js',
						'app/assets/bower_components/echojs/dist/echo.min.js',

						'app/assets/javascripts/vendor/jquery.cycle.all.js'

					],
					
					'public/javascripts/polyfill.min.js': [
						'app/assets/bower_components/html5shiv/dist/html5shiv.js',
						'app/assets/bower_components/respond/dest/respond.min.js'
					]
				}
			}
		},


		copy: {
			main: {
				files: [
					{
						expand:true, 
						flatten: true, 
						src:'app/assets/js/*', 
						dest: 'public/javascripts/', 
						filter: 'isFile'
					},
					{
						expand:true, 
						flatten: true, 
						src:'app/assets/stylesheets/*', 
						dest: 'public/stylesheets/', 
						filter: 'isFile'
					},
					{
						expand:true, 
						flatten: true, 
						src:'app/assets/fonts/*', 
						dest: 'public/fonts/', 
						filter: 'isFile'
					}
				]
			},
			
		}

	});

	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-copy');

	grunt.registerTask('default', [
		'sass',
		'copy',
		'uglify'
	]);

};