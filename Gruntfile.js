module.exports = function(grunt){

	grunt.initConfig({
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
				],
				dest: 'assets/dist/js/scouty.core.js'
			},
		},
		cssmin: {
		  options: {
		    shorthandCompacting: true,
		    roundingPrecision: -1
		  },
		  target: {
		    files: {
		      'assets/dist/css/scouty.core.css': ['assets/dist/css/scouty.core.css']
		    }
		  }
		},
		uglify: {
		    corejs: {
		      files: {
		        'assets/dist/js/scouty.core.js': ['assets/dist/js/scouty.core.js']
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

	grunt.registerTask('default', ['concat','cssmin','uglify']);
};
