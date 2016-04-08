module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    uglify: {
      options: {
        banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> \n* <%= pkg.homepage %> \n* <%= pkg.author %> \n*/\n'
      },
      build: {
        src: ['assets/js/<%= pkg.name %>.js'],
        dest: 'assets/js/<%= pkg.name %>.min.js'
      },
    },
    jsbeautifier : {
      src: 'assets/js/<%= pkg.name %>.js',      
      options: {     
        js: {
          braceStyle: "collapse",
          breakChainedMethods: false,
          e4x: false,
          evalCode: false,
          indentChar: " ",
          indentLevel: 0,
          indentSize: 2,
          indentWithTabs: false,
          jslintHappy: false,
          keepArrayIndentation: false,
          keepFunctionIndentation: false,
          maxPreserveNewlines: 2,
          preserveNewlines: true,
          spaceBeforeConditional: true,
          spaceInParen: false,
          unescapeStrings: false,
          wrapLineLength: 0,
          endWithNewline: true
        }
    }

    },
    jshint : {
      all: ['src/js/**/*']
    },
    prettify : {
      
    },
    watch: {
      scripts: {
        files: ['src/js/**/*.js','src/css/**/*.less'],
        tasks: ['jshint','concat','uglify', 'jsbeautifier','less'],
        options: {
          spawn: false,
        },
      },
    },
    concat: {
        options: {
          separator: ';',
        },
        dist: {
          src: ['src/js/plugins.js', 'src/js/theme.js'],
          dest: 'assets/js/<%= pkg.name %>.js',
        },
      },    
    less: {
      development: {
        options: {
            paths: ['src/css/mixins','src/css/']  ,
          compress: false,
          yuicompress: false,
          syncImport: true,
          strictImports: true
          
        },
        files: {
          "assets/css/style.css": "src/css/style.less" // destination file and source file
        }
      }
    },
    googlefonts: {
        build: {
            options: {
                fontPath: 'assets/fonts',
                cssFile: 'src/css/fonts.less',
                httpPath: '/wp-content/themes/wp-theme-fg/assets/fonts/',
                formats: {
                    eot: true,
                    svg: true,
                    ttf: true,
                    woff: true,
                    woff2: true
                },
                fonts: [
                    {
                        family: 'Roboto',
                        styles: [300,400,500,700]                 
                    },
                    {
                        family: 'Titillium Web',
                        styles: [300,400,600]
                    }
                ]
            }
        }            
    }
  });

  // Load the plugin that provides the "uglify" task.
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks("grunt-jsbeautifier");
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-google-fonts');

  // Default task(s).
  grunt.registerTask('default', ['jshint','uglify','jsbeautifier']);

};



              