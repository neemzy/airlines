(function ($) {
    'use strict';

    $('html').classList.remove('no-js');

    var Board = require('./modules/board'),
        ColorPicker = require('./modules/colorPicker'),

        board = new Board($('.board')),
        colorPicker = new ColorPicker($('.color-picker'));
})
(document.querySelector.bind(document));
