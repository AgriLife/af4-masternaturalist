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
    merge_media:
      pkg:
        options:
          compress: true
        files:
          'css/style.css': 'css/style.css'
      dev:
        options:
          compress: false
        files:
          'css/style.css': 'css/style.css'
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
  @loadNpmTasks 'grunt-merge-media'

  @registerTask 'default', ['sasslint', 'sass:pkg', 'merge_media:pkg', 'postcss:pkg']
  @registerTask 'develop', ['sasslint', 'sass:dev', 'merge_media:dev']

  @event.on 'watch', (action, filepath) =>
    @log.writeln('#{filepath} has #{action}')
