# ModeraBackendOnSteroidsBundle [![Build Status](https://travis-ci.org/modera/ModeraBackendOnSteroidsBundle.svg?branch=1.0)](https://travis-ci.org/modera/ModeraBackendOnSteroidsBundle)

Bundle makes it easier to optimize backend's loading speed for up to 50%, it does it several ways:

 * Provides console commands which can generate shell scripts which can be used to compile all bundle's
 extjs classes together using Sencha Cmd. **NB! Generated scripts rely on Docker!**
 * Ships a special resources-loader which makes it possible to automatically detect and include
 MJR.js and a javascript file which is compiled from bundles' javascript extjs-classes.
 * Using a semantic configuration makes it possible to mark certain bundles (using regex-like syntax) and their
 javascript files as non-blocking resources. This is especially useful when there're bundles whose assets
 are not designated as non-blocking but in fact they are and you want to override this behaviour, make them load
 asynchronously.

## Installation

Add this dependency to your composer.json:

    "modera/backend-on-steroids-bundle": "~1.0"

Update your AppKernel class and add ModeraBackendOnSteroidsBundle declaration there:

    new Modera\BackendOnSteroidsBundle\ModeraBackendOnSteroidsBundle()

## Documentation

Once you have installed the bundle please use `modera:backend-on-steroids:generate-scripts` command, once
executed it will generated four shell scripts for you:

 * `steroids-setup.sh`  - This script will prepare an extjs workspace for you that will be used to compile your
 assets using Sencha Cmd
 * `steroids-compile-bundles.sh` - Once you have steroids set up and your extjs classes copied (use `modera:backend-on-steroids:copy-classes-to-workspace`
 for that) you can invoke this command and have all your installation extjs classes will be compiled together, the result, if
 no configuration changed, will copied to `web/backend-on-steroids/bundles.js` file.
 * `steroids-compile-mjr.sh` - compiles MJR for you and places it, if no semantic configuration is modified, to
 `web/backend-on-steroids/MJR.js`
 * `steroids-cleanup.sh`  - If you don't anymore need extjs-workspace then you can use this script and it will delete
 all developers files that were created to setup extjs-workspace (your compiled extjs classes won't be touched)

See `Modera\BackendOnSteroidsBundle\DependencyInjection\Configuration` for a full list of available configuration
properties.

In order to make your extjs classes visible to `modera:backend-on-steroids:copy-classes-to-workspace`, you need to contribute
to `modera_backend_on_steroids.extjs_classes_paths` extension-point *or* if you are not going to distribute your bundles
you can use `modera_backend_on_steroids/compiler/path_patterns` configuration property.

### Typical workflow

1. $ `modera:backend-on-steroids:generate-scripts`
2. Make generated scripts executable (when `modera:backend-on-steroids:generate-scripts` is executed required shell command is printed that you can use)
3. $ `./steroids-compile-bundles.sh`
4. $ `./steroids-compile-mjr.sh`

Once this steps are completed, given that you haven't changed this bundle's semantic configuration
`compiler/path_patterns` property), when you refresh backend you should see that MJR.js and bundles.js are
automatically included using "script" tags.

## Licensing

This bundle is under the MIT license. See the complete license in the bundle:
Resources/meta/LICENSE

