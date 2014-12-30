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

                handleEstimateInput = function(value) {
                    this.handleInput('estimate', value);
                }.bind(this),

                handleConsumedInput = function(value) {
                    this.handleInput('consumed', value);
                }.bind(this),

                handleRemainingInput = function(value) {
                    this.handleInput('remaining', value);
                }.bind(this),

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
                    'numbers__remaining--over': this.props.underEstimated && 0 < this.props.remaining,
                    'numbers__remaining--under': this.props.overEstimated
                });

            return (
                <div className="numbers">
                    <Editable className={estimateClass} title="Estimate" handleInput={handleEstimateInput}>{estimate}</Editable>
                    <Editable className={consumedClass} title="Consumed" handleInput={handleConsumedInput}>{consumed}</Editable>
                    <Editable className={remainingClass} title="Remaining" handleInput={handleRemainingInput}>{remaining}</Editable>
                </div>
            );
        }
    });
})
();
