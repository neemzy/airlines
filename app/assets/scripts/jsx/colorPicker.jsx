var React = require('react'),
    ReactColorPicker = require('react-color-picker');

module.exports = React.createClass({
    getInitialState: function() {
        return { color: '#FFFFFF' };
    },

    updateColor: function(color) {
        this.setState({ color: color });
    },

    render: function() {
        return (
            <div>
                <ReactColorPicker value={this.state.color} onDrag={this.updateColor} />
                <input type="text" value={this.state.color} onChange={this.updateColor} />
            </div>
        );
    }
});