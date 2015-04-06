(function () {
    'use strict';

    var React = require('react'),
        classNames = require('classnames'),
        Editable = require('./editable');

    module.exports = React.createClass({
        /**
         * @return {Object}
         */
        getDefaultProps: function() {
            return {
                estimate: 0,
                consumed: 0,
                remaining: 0
            };
        },

        /**
         * Generic input handler
         * Calls update method on parent Task
         *
         * @param {String} key
         * @param {String} value
         */
        handleInput: function(key, value) {
            var data = {};
            data[key] = value;

            this.props.handleInput(data);
        },

        /**
         * @return {Object}
         */
        render: function() {
            var estimate = this.props.estimate.toFixed(3),
                consumed = this.props.consumed.toFixed(3),
                remaining = this.props.remaining.toFixed(3),
                estimateNode = (<span className="numbers__value">{estimate}</span>),
                consumedNode = (<span className="numbers__value">{consumed}</span>),
                remainingNode = (<span className="numbers__value">{remaining}</span>),

                estimateClass = classNames({
                    'numbers__estimate': true
                }),

                consumedClass = classNames({
                    'numbers__consumed': true,
                    'is-over': parseFloat(consumed) > parseFloat(estimate)
                }),

                remainingClass = classNames({
                    'numbers__remaining': true,
                    'is-over': (parseFloat(consumed) + parseFloat(remaining) > parseFloat(estimate)) && (0 < this.props.remaining),
                    'is-under': parseFloat(consumed) + parseFloat(remaining) < parseFloat(estimate)
                });

            // Editable values ?
            if (undefined !== this.props.handleInput) {
                var handleEstimateInput = function(value) {
                        this.handleInput('estimate', value);
                    }.bind(this),

                    handleConsumedInput = function(value) {
                        this.handleInput('consumed', value);
                    }.bind(this),

                    handleRemainingInput = function(value) {
                        this.handleInput('remaining', value);
                    }.bind(this);

                estimateNode = (<Editable className="numbers__value" handleInput={handleEstimateInput}>{estimate}</Editable>);
                consumedNode = (<Editable className="numbers__value" handleInput={handleConsumedInput}>{consumed}</Editable>);
                remainingNode = (<Editable className="numbers__value" handleInput={handleRemainingInput}>{remaining}</Editable>);
            }

            return (
                <div className="numbers">
                    <div className={estimateClass} title="Estimate">
                        <span className="numbers__label">Estimate</span>
                        {estimateNode}
                    </div>
                    <div className={consumedClass} title="Consumed">
                        <span className="numbers__label">Consumed</span>
                        {consumedNode}
                    </div>
                    <div className={remainingClass} title="Remaining">
                        <span className="numbers__label">Remaining</span>
                        {remainingNode}
                    </div>
                </div>
            );
        }
    });
})();
