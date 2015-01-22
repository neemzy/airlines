(function () {
    'use strict';

    var React = require('react/addons'),
        Editable = require('./editable');

    module.exports = React.createClass({
        /**
         * Generic input handling method
         * Calls update method on parent Task
         *
         * @param string key   Key of parameter to update
         * @param string value New value
         *
         * @return void
         */
        handleInput: function(key, value) {
            var data = {};
            data[key] = value;

            this.props.handleInput(data);
        },



        /**
         * Rendering React hook
         * Sets the right classes for each value and binds edition callbacks
         *
         * @return void
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
                            <label>Estimé</label>
                            <Editable title="Estimate" handleInput={handleEstimateInput}>{estimate}</Editable>
                        </div>
                        <div className={consumedClass}>
                            <label>Consommé</label>
                            <Editable title="Consumed" handleInput={handleConsumedInput}>{consumed}</Editable>
                        </div>
                        <div className={remainingClass}>
                            <label>Kirestafèr</label>
                            <Editable title="Remaining" handleInput={handleRemainingInput}>{remaining}</Editable>
                        </div>
                    </div>
                );
            }

            return (
                <div className="numbers">
                    <div className={estimateClass} title="Estimate">
                        <label>Estimé</label>
                        <span>{estimate}</span>
                    </div>
                    <div className={consumedClass} title="Consumed">
                        <label>Consommé</label>
                        <span>{consumed}</span>
                    </div>
                    <div className={remainingClass} title="Remaining">
                        <label>Kirestafèr</label>
                        <span>{remaining}</span>
                    </div>
                </div>
            );
        }
    });
})
();
