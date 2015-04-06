(function () {
    'use strict';

    var React = require('react'),
        throttle;

    module.exports = React.createClass({
        /**
         * Input event handler for the contentEditable element
         * Throttles AJAX saving process
         */
        handleInput: function() {
            window.clearTimeout(throttle);

            throttle = window.setTimeout(
                function () {
                    this.props.handleInput(this.getDOMNode().innerHTML);
                }.bind(this),
                1000
            );
        },

        /**
         * @return {Object}
         */
        render: function() {
            var props = JSON.parse(JSON.stringify(this.props));

            delete props.children;
            delete props.handleInput;

            props.className = props.className || '';
            props.className += ' editable';

            return (
                <span {...props} contentEditable onKeyUp={this.handleInput}>{this.props.children}</span>
            );
        }
    });
})();
