module.exports = function(grunt){

	grunt.initConfig({
		concat: {
			corecss: {
				src: ['node_modules/bootstrap/dist/css/bootstrap.min.css'],
				dest: 'assets/dist/css/scouty.core.css'
			},
			corejs: {
				src: ['node_modules/bootstrap/dist/js/bootstrap.min.js'],
				dest: 'assets/dist/js/scouty.core.js'
			},/*
			eventcss: {
				src: ['plugins/events/assets/css/**.css'],
				dest: 'plugins/events/dist/css/events.min.css'
			},
			homejs: {
				src: ['plugins/home/assets/js/**.js'],
				dest: 'plugins/home/dist/js/home.min.js'
			},
			nominationjs: {
				src: ['assets/public/js/malsup.min.js','assets/public/js/validate.min.js','plugins/about/assets/js/nomination-form.js'],
				dest: 'plugins/about/dist/js/nomination.min.js'
			},
			shaktigallery: {
				src: ['assets/public/js/isotope.min.js','plugins/about/assets/js/gallery.js'],
				dest: 'plugins/about/dist/js/gallery.min.js'
			},
			corejs: {
				src: [
					'assets/public/js/jquery-1.11.2.min.js',
					'assets/public/js/bootstrap.js',
					'assets/public/js/jquery.waypoints.min.js',
					'assets/public/js/modernizr.js',
					'assets/public/js/rubick_pres.js'
				],
				dest: 'assets/dist/js/core.min.js'
			},*/
		},
		cssmin: {
		  options: {
		    shorthandCompacting: true,
		    roundingPrecision: -1
		  },
		  target: {
		    files: {
		      'assets/dist/css/core.min.css': ['assets/dist/css/core.css']
		    }
		  }
		},
		uglify: {
		    corejs: {
		      files: {
		        'assets/dist/js/core.min.js': ['assets/dist/js/core.min.js']
		      }
		    },
		    nominationjs: {
		      files: {
		        'plugins/about/dist/js/nomination.min.js': ['plugins/about/dist/js/nomination.min.js']
		      }
		    },
		    shaktigallery: {
		      files: {
		        'plugins/about/dist/js/gallery.min.js': ['plugins/about/dist/js/gallery.min.js']
		      }
		    }
		},
		watch: {
		  styles: {
		    files: ['plugins/**/assets/css/*.css','plugins/**/assets/js/*.js','assets/public/css/*.css','assets/public/js/*.js'], // which files to watch
		    tasks: ['concat','homecss'],
		    options: {
		      nospawn: true
		    }
		  }
		}
	});

	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-uglify');

	grunt.registerTask('default', ['concat']);
	grunt.registerTask('corecss', ['concat:corecss','cssmin']);
	grunt.registerTask('concat-corejs', ['concat:corejs','cssmin']);
	grunt.registerTask('uglify-corejs', ['uglify:corejs']);
	grunt.registerTask('homecss', ['concat:homecss','cssmin']);
	grunt.registerTask('eventcss', ['concat:eventcss','cssmin']);
	grunt.registerTask('nominationjs', ['concat:nominationjs','uglify:nominationjs']);
	grunt.registerTask('shaktigallery', ['concat:shaktigallery','uglify:shaktigallery']);
};
