(function () {
    'use strict';

    var React = require('react'),
        ReactColorPicker = require('react-color-picker');

    module.exports = React.createClass({
        /**
         * @return {Object}
         */
        getInitialState: function() {
            return {
                color: '#FFFFFF',
                attrs: {}
            };
        },

        /**
         * @param {String} color Hexadecimal notation
         */
        updateColor: function(color) {
            this.setState({ color: color.toUpperCase() });
        },

        /**
         * Post-mount React hook
         * Attaches data-* HTML attributes to the inner input
         */
        componentDidMount: function() {
            var parentNode = this.getDOMNode().parentNode,
                attrs = this.state.attrs;

            [].slice.call(parentNode.attributes).forEach(function (attr) {
                if (attr.name.match(/^data-/)) {
                    var realName = attr.name.substr(5);

                    if ('value' == realName) {
                        this.updateColor(attr.value);
                    } else {
                        if ('class' == realName) {
                            realName = 'className';
                        }

                        attrs[realName] = attr.value;
                    }

                    parentNode.removeAttribute(attr.name);
                }
            }, this);

            this.setState({ attrs: attrs });
        },

        /**
         * @return {Object}
         */
        render: function() {
            var style = { backgroundColor: this.state.color };

            return (
                <div>
                    <input readOnly value={this.state.color} {...this.state.attrs} onChange={this.updateColor} style={style} />
                    <ReactColorPicker value={this.state.color} onDrag={this.updateColor} />
                </div>
            );
        }
    });
})();
