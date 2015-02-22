(function () {
    'use strict';

    var React = require('react'),
        Promise = require('promise'),
        reqwest = require('reqwest'),
        DateHelper = require('../imports/dateHelper'),
        Day = require('./day'),
        Task = require('./task'),
        Numbers = require('./numbers');

    module.exports = React.createClass({
        /**
         * Initial state React hook
         *
         * @return object
         */
        getInitialState: function() {
            return {
                estimate: {},
                consumed: {},
                remaining: {},
                days: []
            };
        },



        /**
         * Updates this Member's Numbers from its Days
         *
         * @param string date    Date of the Day to update the numbers for, used as key
         * @param object numbers Estimate, consumed and remaining values
         *
         * @return void
         */
        updateNumbers: function(date, numbers) {
            var estimate = this.state.estimate,
                consumed = this.state.consumed,
                remaining = this.state.remaining;

            estimate[date] = numbers.estimate;
            consumed[date] = numbers.consumed;
            remaining[date] = numbers.remaining;

            this.setState({ estimate: estimate, consumed: consumed, remaining: remaining });
        },



        /**
         * Rendering React hook
         * Builds the whole Member's week row
         *
         * @return void
         */
        render: function() {
            var dateHelper = new DateHelper(),
                avatarStyle = { backgroundImage: 'url(\'/' + this.props.avatar + '\')' },
                colorStyle = { backgroundColor: this.props.color },
                key = 0,
                days = [],
                estimate = 0,
                consumed = 0,
                remaining = 0;

            this.props.dates.forEach(
                function (date) {
                    key++;

                    var convertedDate = dateHelper.convert(date),
                        taskUrl = this.props.taskUrl + convertedDate;

                    days.push(
                        <Day key={key} date={convertedDate} member={this.props.id} taskUrl={taskUrl} updateNumbers={this.updateNumbers} color={this.props.color} />
                    );
                },
                this
            );

            for (var i in this.state.estimate) {
                estimate += this.state.estimate[i];
            }

            for (var i in this.state.consumed) {
                consumed += this.state.consumed[i];
            }

            for (var i in this.state.remaining) {
                remaining += this.state.remaining[i];
            }

            return (
                <div className="member">
                    <div className="member__info">
                        <div className="member__identity">
                            <div className="avatar avatar--medium" style={avatarStyle}></div>
                            <div className="member__name">
                                <span className="member__color" style={colorStyle}></span>
                                {this.props.name}
                            </div>
                        </div>
                        <Numbers estimate={estimate} consumed={consumed} remaining={remaining} />
                    </div>
                    {days}
                </div>
            );
        }
    });
})();
