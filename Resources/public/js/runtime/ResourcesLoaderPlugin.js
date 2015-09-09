/**
 * @author Sergei Lissovski <sergei.lissovski@modera.org>
 */
Ext.define('Modera.backend.backendonsteroids.runtime.ResourcesLoaderPlugin', {
    extend: 'MF.runtime.extensibility.AbstractPlugin',

    // override
    constructor: function(config) {
        this.callParent(arguments);
        this.config = config;
    },

    // override
    getId: function() {
        return 'backend_on_steroids_resources_loader_plugin';
    },

    // override
    bootstrap: function(cb) {
        cb();
    },

    // private
    loadScripts: function(urls, fn) {
        var me = this;
        var url = urls.shift();
        Ext.Loader.loadScript({
            url: url,
            onLoad: function() {
                if (urls.length > 0) {
                    me.loadScripts(urls, fn);
                } else {
                    fn();
                }
            },
            onError: function() {
                console.error('Url "' + url + '" not loaded!');

                var re = /ext-lang-(\D{2})(_\D{2})\.js/i
                if (url.match(re)) {
                    var tryUrl = url.replace(re, 'ext-lang-$1.js');
                    urls.unshift(tryUrl);
                    console.info('Try to load "' + tryUrl + '"');
                    me.loadScripts(urls, fn);
                } else {
                    fn();
                }
            }
        });
    }
});