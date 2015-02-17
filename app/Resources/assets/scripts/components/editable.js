(function () {
    'use strict';

    var React = require('react/addons'),
        throttle;

    module.exports = React.createClass({
        /**
         * Input event handler for the contentEditable element
         * Throttles AJAX saving process
         *
         * @return void
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
         * Rendering React hook
         *
         * @return void
         */
        render: function() {
            var props = JSON.parse(JSON.stringify(this.props));

            delete props.children;
            delete props.handleInput;

            props.className = props.className || '';
            props.className += ' editable';

            return (
                <div {...props} contentEditable onInput={this.handleInput}>{this.props.children}</div>
            );
        }
    });
})();
