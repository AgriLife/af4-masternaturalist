# AgriFlex4 - Master Naturalist
[![Codeship Status for AgriLife/af4-masternaturalist](https://app.codeship.com/projects/7e8dd320-34b6-0138-5d84-7a442fed4f6c/status?branch=master)](https://app.codeship.com/projects/385857)

This WordPress plugin provides functionality and visual styles for Texas Master Naturalist websites on the AgriFlex4 WordPress theme created by AgriLife Communications.

## WordPress Requirements

1. Genesis theme
2. Advanced Custom Fields Pro plugin
3. PHP 7.2+

## Features

* Header Widget Area

## Development Installation

1. Copy this repo to the desired location.
2. In your terminal, navigate to the plugin location 'cd /path/to/the/plugin'.
3. Run "npm start" to configure your local copy of the repo, install dependencies, and build files for a production environment.
4. Or, run "npm start -- develop" to configure your local copy of the repo, install dependencies, and build files for a development environment.

## Development Notes

When you stage changes to this repository and initiate a commit, they must pass PHP and Sass linting tasks before they will complete the commit step. Release tasks can only be used by the repository's owners.

## Development Tasks

1. Run "grunt develop" to compile the css when developing the plugin.
2. Run "grunt watch" to automatically compile the css after saving a *.scss file.
3. Run "grunt" to compile the css when publishing the plugin.
4. Run "npm run checkwp" to check PHP files against WordPress coding standards.
5. Run "npm run fixwp" to fix simple formatting issues against WordPress coding standards.

## Development Requirements

* Node: http://nodejs.org/
* NPM: https://npmjs.org/
