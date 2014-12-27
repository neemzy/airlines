var React = require('react');

module.exports = React.createClass({
    render: function() {
        return (
            <div className="numbers">
                <div className="numbers__estimate" title="Estimate">{this.props.estimate}</div>
                <div className="numbers__consumed" title="Consumed">{this.props.consumed}</div>
                <div className="numbers__remaining" title="Remaining">{this.props.remaining}</div>
            </div>
        );
    }
});