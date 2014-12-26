var React = require('react'),
    ReactColorPicker = require('react-color-picker');

module.exports = React.createClass({
    getInitialState: function() {
        return {
            color: '#FFFFFF',
            attrs: {}
        };
    },

    updateColor: function(color) {
        this.setState({ color: color.toUpperCase() });
    },

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

    render: function() {
        return (
            <div>
                <ReactColorPicker value={this.state.color} onDrag={this.updateColor} />
                <input value={this.state.color} {...this.state.attrs} onChange={this.updateColor} />
            </div>
        );
    }
});