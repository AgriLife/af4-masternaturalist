module.exports = (grunt) ->
  sass = require 'node-sass'
  @initConfig
    pkg: @file.readJSON('package.json')
    watch:
      files: [
        'css/src/**/*.scss'
      ]
      tasks: ['develop']
    postcss:
      pkg:
        options:
          processors: [
            require('autoprefixer')()
            require('cssnano')()
          ]
          failOnError: true
        files:
          'css/style.css': 'css/style.css'
      dev:
        options:
          map: true
          processors: [
            require('autoprefixer')()
          ]
          failOnError: true
        files:
          'css/style.css': 'css/style.css'
    cmq:
      your_target:
        files:
          'css': ['css/*.css']
    sass:
      pkg:
        options:
          implementation: sass
          noSourceMap: true
          outputStyle: 'compressed'
          precision: 2
          includePaths: ['node_modules/foundation-sites/scss']
        files:
          'css/style.css': 'css/src/style.scss'
      dev:
        options:
          implementation: sass
          sourceMap: true
          outputStyle: 'nested'
          precision: 2
          includePaths: ['node_modules/foundation-sites/scss']
        files:
          'css/style.css': 'css/src/style.scss'
    sasslint:
      options:
        configFile: '.sass-lint.yml'
      target: ['css/**/*.s+(a|c)ss']

  @loadNpmTasks 'grunt-contrib-watch'
  @loadNpmTasks 'grunt-sass-lint'
  @loadNpmTasks 'grunt-sass'
  @loadNpmTasks 'grunt-postcss'
  @loadNpmTasks 'grunt-combine-media-queries'

  @registerTask 'default', ['sasslint', 'sass:pkg', 'cmq', 'postcss:pkg']
  @registerTask 'develop', ['sasslint', 'sass:dev', 'postcss:dev']

  @event.on 'watch', (action, filepath) =>
    @log.writeln('#{filepath} has #{action}')
