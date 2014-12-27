var React = require('react'),
    Numbers = require('./numbers');

module.exports = React.createClass({
    render: function() {
        var style = { backgroundColor: this.props.color };

        return (
            <div className="task">
                <div className="task__name" style={style}>{this.props.name}</div>
                <Numbers estimate={this.props.estimate} consumed={this.props.consumed} remaining={this.props.remaining} />
                <a className="task__delete"></a>
            </div>
        );
    }
});