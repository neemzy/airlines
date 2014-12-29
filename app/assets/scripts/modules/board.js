(function () {
    'use strict';

    var React = require('react'),
        boardComponent = React.createFactory(require('../components/board'));

    /**
     * Constructor
     * Grabs parameters from the targeted DOM node and renders the React component
     *
     * @return void
     */
    var Board = module.exports = function(boardElement) {
        if (null == boardElement) {
            return;
        }

        var params = {
                week: null,
                weekUrl: null,
                memberUrl: null
            };

        for (var key in params) {
            params[key] = boardElement.getAttribute('data-' + key);
        }

        React.render(boardComponent(params), boardElement);
    };
})
();
