(function () {
    'use strict';

    var React = require('react/addons');

    module.exports = React.createClass({
        /**
         * Rendering React hook
         * Sets the right classes for each value
         *
         * @return void
         */
        render: function() {
            var estimate = this.props.estimate.toFixed(3),
                consumed = this.props.consumed.toFixed(3),
                remaining = this.props.remaining.toFixed(3),
                cx = React.addons.classSet,

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
                    <div className={estimateClass} title="Estimate">{estimate}</div>
                    <div className={consumedClass} title="Consumed">{consumed}</div>
                    <div className={remainingClass} title="Remaining">{remaining}</div>
                </div>
            );
        }
    });
})
();
