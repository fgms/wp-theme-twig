module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    watch: {
      scripts: {
        files: ['css/**/*.less'],
        tasks: ['less'],
        options: {
          spawn: false,
        },
      },
    },    
    less: {
      development: {
        options: {
            paths: ['css/mixins','css/'],
          compress: true,
          yuicompress: true,
          syncImport: true,
          strictImports: true          
        },
        files: {
          "../css/style.css": "css/style.less" // destination file and source file
        }
      }
    },
    googlefonts: {
        build: {
            options: {
                fontPath: 'assets/fonts',
                cssFile: 'src/css/fonts.less',
                httpPath: '@{assetsPath}/fonts/',
                formats: {
                    eot: true,
                    svg: true,
                    ttf: true,
                    woff: true,
                    woff2: true
                },
                fonts: [
                    {
                        family: 'Lato',
                        styles: [400,700]                 
                    },
                    {
                      family: 'Open Sans',
                      styles: [400,700]
                    }
					
                ]
            }
        }            
    }
  });


  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-google-fonts');

  // Default task(s).
  grunt.registerTask('default', ['less']);

};
