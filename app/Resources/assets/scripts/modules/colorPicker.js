(function () {
    'use strict';

    var React = require('react'),
        colorPickerComponent = React.createFactory(require('../components/colorPicker'));

    /**
     * Constructor
     * Renders the React component
     */
    var ColorPicker = module.exports = function(colorPickerElement) {
        if (null == colorPickerElement) {
            return;
        }

        React.render(colorPickerComponent(), colorPickerElement)
    };
})();
