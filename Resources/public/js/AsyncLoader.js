/**
 * @author Sergei Lissovski <sergei.lissovski@modera.org>
 */
Ext.define('Modera.backend.backendonsteroids.AsyncLoader', {
    requires: [
        'MF.misc.SyncedCallbackExecution'
    ],

    /**
     * @param {Object} config
     */
    constructor: function (config) {
        Ext.apply(this, config);
    }
});