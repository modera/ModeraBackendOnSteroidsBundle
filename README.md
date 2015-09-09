# ModeraBackendOnSteroidsBundle [![Build Status](https://travis-ci.org/modera/ModeraBackendOnSteroidsBundle.svg?branch=master)](https://travis-ci.org/modera/ModeraBackendOnSteroidsBundle)

Bundle makes it easier to optimize backend's loading speed for up to 50%, it does it several ways:

 * Provides console commands which can generate shell scripts which can be used to compile all bundle's
 extjs classes together using Sencha Cmd. **NB! Generated scripts rely on Docker!**

## Installation

Add this dependency to your composer.json:

    "modera/backend-on-steroids-bundle": "dev-master"

Update your AppKernel class and add ModeraBackendOnSteroidsBundle declaration there:

    new Modera\BackendOnSteroidsBundle\ModeraBackendOnSteroidsBundle()

## Documentation

Once you have installed the bundle please use `modera:backend-on-steroids:generate-scripts` command, once
executed it will generated three shell scripts for you:

 * `steroids-setup.sh`  - This script will prepare an extjs workspace for you that will be used to compile your
 assets using Sencha Cmd
 * `steroids-compile.sh` - Once you have steroids set up and your extjs classes copied (use `modera:backend-on-steroids:copy-classes-to-workspace`
 for that) you can invoke this command and have all your installation extjs classes will be compiled together, the result, if
 no configuration changed, will copied to `web/backend-on-steroids/bundles.js` file.
 * `steroids-cleanup.sh`  - If you don't anymore need extjs-workspace then you can use this script and it will delete
 all developers files that were created to setup extjs-workspace (your compiled extjs classes won't be touched)

See `Modera\BackendOnSteroidsBundle\DependencyInjection\Configuration` for a full list of available configuration
properties.

## Licensing

This bundle is under the MIT license. See the complete license in the bundle:
Resources/meta/LICENSE

