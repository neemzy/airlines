(function ($) {
    'use strict';

    $('html').classList.remove('no-js');

    var React = require('react'),
        Board = React.createFactory(require('./modules/board')),
        ColorPicker = React.createFactory(require('./modules/colorPicker')),
        boardElement = $('.board'),
        pickerElement = $('.color-picker');

    // Board rendering
    if (boardElement) {
        var params = {
                week: null,
                weekUrl: null,
                memberUrl: null
            };

        for (var key in params) {
            params[key] = boardElement.getAttribute('data-' + key);
        }

        React.render(Board(params), boardElement);
    }

    // Colorpicker rendering
    pickerElement && React.render(ColorPicker(), pickerElement);
})
(document.querySelector.bind(document));