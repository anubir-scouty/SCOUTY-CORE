module.exports = function(grunt){

	grunt.initConfig({
		less: {
			development: {
				files: {
					'assets/public/css/style.css': 'assets/public/less/style.less',
					'plugins/home/assets/css/home.css': 'plugins/home/assets/less/home.less',
				}
			},
		},
		concat: {
			corecss: {
				src: ['node_modules/bootstrap/dist/css/bootstrap.min.css','assets/public/css/style.css'],
				dest: 'assets/dist/css/scouty.core.css'
			},
			corejs: {
				src: [
					'node_modules/jquery/dist/jquery.min.js',
					'node_modules/bootstrap/dist/js/bootstrap.min.js',
					'node_modules/jquery-validation/dist/jquery.validate.min.js',
					'node_modules/jquery-validation/dist/additional-methods.js',
					'assets/public/js/script.js'
				],
				dest: 'assets/dist/js/scouty.core.js'
			},
			homecss: {
				src: [
					'plugins/home/assets/css/home.css'
				],
				dest: 'plugins/home/dist/css/home.min.css'
			},
			homejs: {
				src: [
					'plugins/home/assets/js/home.js'
				],
				dest: 'plugins/home/dist/js/home.min.js'
			},
			aboutjs: {
				src: [
					'plugins/about2/assets/js/home.js'
				],
				dest: 'plugins/about2/dist/js/home.min.js'
			}
		},
		cssmin: {
		  options: {
		    shorthandCompacting: true,
		    roundingPrecision: -1
		  },
		  target: {
		    files: {
		      'assets/dist/css/scouty.core.css': ['assets/dist/css/scouty.core.css'],
		      'plugins/home/dist/css/home.min.css': ['plugins/home/dist/css/home.min.css']
		    }
		  }
		},
		uglify: {
		    corejs: {
		      files: {
		        'assets/dist/js/scouty.core.js': ['assets/dist/js/scouty.core.js'],
		        'plugins/home/dist/js/home.min.js': ['plugins/home/dist/js/home.min.js']
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

	grunt.loadNpmTasks('grunt-contrib-less');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-uglify');

	grunt.registerTask('default', ['less','concat','cssmin','uglify']);
};
