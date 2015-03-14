(function () {
    'use strict';

    var React = require('react'),
        colorPickerComponent = React.createFactory(require('../components/colorPicker'));

    var ColorPicker = module.exports = function(colorPickerElement) {
        if (null == colorPickerElement) {
            return;
        }

        React.render(colorPickerComponent(), colorPickerElement)
    };
})();
