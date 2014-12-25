(function ($) {
    'use strict';

    $('html').classList.remove('no-js');

    // React bindings
    var React = require('react'),
        SampleList = React.createFactory(require('./jsx/sampleList')),
        ColorPicker = React.createFactory(require('./jsx/colorPicker')),
        contentElement = $('#content'),
        pickerElement = $('.color-picker');

    contentElement && React.render(SampleList(), contentElement);
    pickerElement && React.render(ColorPicker(), pickerElement);
})
(document.querySelector.bind(document));