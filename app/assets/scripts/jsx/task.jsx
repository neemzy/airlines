var React = require('react'),
    reqwest = require('reqwest'),
    Numbers = require('./numbers');

module.exports = React.createClass({
    getInitialState: function() {
        return { removed: false };
    },

    erase: function() {
        this.setState({ removed: true });
    },

    remove: function() {
        if (!confirm('Are you sure ?')) {
            return;
        }

        reqwest({
            url: this.props.removeUrl,
            type: 'json',
            method: 'DELETE',

            error: function(err) {
                // TODO: error handling, if there's any need
            },

            success: function(response) {
                this.erase();
            }.bind(this)
        });
    },

    render: function() {
        var style = {},
            nameStyle = { backgroundColor: this.props.color };

        if (this.state.removed) {
            style.display = 'none';
        }

        return (
            <div className="task" style={style}>
                <div className="task__name" style={nameStyle}>{this.props.name}</div>
                <Numbers estimate={this.props.estimate}
                         consumed={this.props.consumed}
                         remaining={this.props.remaining}
                         overConsumed={this.props.overConsumed}
                         underEstimated={this.props.underEstimated}
                         overEstimated={this.props.overEstimated} />
                <a className="task__remove" onClick={this.remove}></a>
            </div>
        );
    }
});