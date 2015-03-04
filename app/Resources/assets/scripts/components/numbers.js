(function () {
    'use strict';

    var React = require('react/addons'),
        Editable = require('./editable');

    module.exports = React.createClass({
        /**
         * Initial props React hook
         *
         * @return object
         */
        getDefaultProps: function() {
            return {
                estimate: 0,
                consumed: 0,
                remaining: 0
            };
        },

        /**
         * Generic input handling method
         * Calls update method on parent Task
         *
         * @param string key   Key of parameter to update
         * @param string value New value
         */
        handleInput: function(key, value) {
            var data = {};
            data[key] = value;

            this.props.handleInput(data);
        },



        /**
         * Rendering React hook
         * Sets the right classes for each value and binds edition callbacks
         */
        render: function() {
            var estimate = this.props.estimate.toFixed(3),
                consumed = this.props.consumed.toFixed(3),
                remaining = this.props.remaining.toFixed(3),
                isEditable = undefined !== this.props.handleInput,
                isOverConsumed = parseFloat(consumed) > parseFloat(estimate),
                wasUnderEstimated = parseFloat(consumed) + parseFloat(remaining) > parseFloat(estimate),
                wasOverEstimated = parseFloat(consumed) + parseFloat(remaining) < parseFloat(estimate),
                handleEstimateInput,
                handleConsumedInput,
                handleRemainingInput,
                content,

                estimateClass = React.addons.classSet({
                    'numbers__estimate': true
                }),

                consumedClass = React.addons.classSet({
                    'numbers__consumed': true,
                    'is-over': isOverConsumed
                }),

                remainingClass = React.addons.classSet({
                    'numbers__remaining': true,
                    'is-over': wasUnderEstimated && 0 < this.props.remaining,
                    'is-under': wasOverEstimated
                });

            if (isEditable) {
                handleEstimateInput = function(value) {
                    this.handleInput('estimate', value);
                }.bind(this);

                handleConsumedInput = function(value) {
                    this.handleInput('consumed', value);
                }.bind(this);

                handleRemainingInput = function(value) {
                    this.handleInput('remaining', value);
                }.bind(this);

                return (
                    <div className="numbers">
                        <div className={estimateClass}>
                            <span className="numbers__label">Estimate</span>
                            <Editable title="Estimate" className="numbers__value" handleInput={handleEstimateInput}>{estimate}</Editable>
                        </div>
                        <div className={consumedClass}>
                            <span className="numbers__label">Consumed</span>
                            <Editable title="Consumed" className="numbers__value" handleInput={handleConsumedInput}>{consumed}</Editable>
                        </div>
                        <div className={remainingClass}>
                            <span className="numbers__label">Remaining</span>
                            <Editable title="Remaining" className="numbers__value" handleInput={handleRemainingInput}>{remaining}</Editable>
                        </div>
                    </div>
                );
            }

            return (
                <div className="numbers">
                    <div className={estimateClass} title="Estimate">
                        <span className="numbers__label">Estimate</span>
                        <span className="numbers__value">{estimate}</span>
                    </div>
                    <div className={consumedClass} title="Consumed">
                        <span className="numbers__label">Consumed</span>
                        <span className="numbers__value">{consumed}</span>
                    </div>
                    <div className={remainingClass} title="Remaining">
                        <span className="numbers__label">Remaining</span>
                        <span className="numbers__value">{remaining}</span>
                    </div>
                </div>
            );
        }
    });
})();
