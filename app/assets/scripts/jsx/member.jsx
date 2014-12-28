var React = require('react'),
    Promise = require('promise'),
    reqwest = require('reqwest'),
    Task = require('./task'),
    Numbers = require('./numbers');

module.exports = React.createClass({
    getInitialState: function() {
        return {
            estimate: 0,
            consumed: 0,
            remaining: 0,
            tasks: []
        };
    },

    loadDates: function() {
        return new Promise(
            function (resolve, reject) {
                reqwest({
                    url: this.props.weekUrl,
                    type: 'json',
                    method: 'GET',

                    error: function(err) {
                        reject(err);
                    }.bind(this),

                    success: function(dates) {
                        resolve(dates);
                    }.bind(this)
                });
            }.bind(this)
        );
    },

    loadTasks: function() {
        return new Promise(
            function (resolve, reject) {
                reqwest({
                    url: this.props.taskUrl,
                    type: 'json',
                    method: 'GET',

                    error: function(err) {
                        reject(err);
                    }.bind(this),

                    success: function(tasks) {
                        resolve(tasks);
                    }.bind(this)
                });
            }.bind(this)
        );
    },

    componentWillMount: function() {
        this.loadDates()
            .then(
                function (dates) {
                    this.loadTasks(dates)
                        .then(
                            function (tasks) {
                                var days = [],
                                    estimate = 0,
                                    consumed = 0,
                                    remaining = 0,
                                    alreadyProcessedNumbers = false;

                                dates.forEach(
                                    function (date) {
                                        var tasksForDay = [];

                                        tasks.forEach(
                                            function (task) {
                                                // FIXME: need better timezone handling
                                                if (task.date.split('T').shift() == date) {
                                                    tasksForDay.push(task);
                                                }

                                                if (!alreadyProcessedNumbers) {
                                                    estimate += task.estimate;
                                                    consumed += task.consumed;
                                                    remaining += task.remaining;
                                                }
                                            }
                                        );

                                        days.push(tasksForDay);
                                        alreadyProcessedNumbers = true;
                                    }
                                );

                                this.setState({ tasks: days, estimate: estimate, consumed: consumed, remaining: remaining });
                            }.bind(this)
                        );
                }.bind(this)
            );
    },

    render: function() {
        var avatarStyle = { backgroundImage: 'url(\'' + this.props.avatar + '\')' },
            colorStyle = { backgroundColor: this.props.color },
            days = [],
            keyCounter = 0;

        this.state.tasks.forEach(
            function (day) {
                var tasks = [],
                    key = this.props.id + '-' + (++keyCounter);

                day.forEach(
                    function (task) {
                        tasks.push(
                            <Task key={task.id}
                                  id={task.id}
                                  name={task.name}
                                  date={task.date}
                                  color={this.props.color}
                                  estimate={task.estimate}
                                  consumed={task.consumed}
                                  remaining={task.remaining}
                                  overConsumed={task.overConsumed}
                                  underEstimated={task.underEstimated}
                                  overEstimated={task.overEstimated}
                                  removeUrl={task.removeUrl} />
                        );
                    },
                    this
                );

                days.push(
                    <div key={key} className="member__day">{tasks}</div>
                );
            },
            this
        );

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
                    <div className="numbers">
                        <div className="numbers__estimate" title="Estimate">{this.state.estimate}</div>
                        <div className="numbers__consumed" title="Consumed">{this.state.consumed}</div>
                        <div className="numbers__remaining" title="Remaining">{this.state.remaining}</div>
                    </div>
                </div>
                {days}
            </div>
        );
    }
});