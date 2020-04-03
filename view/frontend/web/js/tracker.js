define([
    'jquery'
], function ($) {
    'use strict';

    $.widget('mage.tracker', {
        options: {},

        _create: function () {
            this.bindEvents();
        },

        bindEvents: function()
        {
        }
    });

    return $.mage.tracker;
});
