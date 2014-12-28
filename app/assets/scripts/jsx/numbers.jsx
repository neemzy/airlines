var React = require('react/addons');

module.exports = React.createClass({
    render: function() {
        var cx = React.addons.classSet,

            estimateClass = cx({
                'numbers__estimate': true
            }),

            consumedClass = cx({
                'numbers__consumed': true,
                'numbers__consumed--over': this.props.overConsumed
            }),

            remainingClass = cx({
                'numbers__remaining': true,
                'numbers__remaining--under': this.props.underEstimated,
                'numbers__remaining--over': this.props.overEstimated
            });

        return (
            <div className="numbers">
                <div className={estimateClass} title="Estimate">{this.props.estimate}</div>
                <div className={consumedClass} title="Consumed">{this.props.consumed}</div>
                <div className={remainingClass} title="Remaining">{this.props.remaining}</div>
            </div>
        );
    }
});