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
                    url: this.props.taskUrl + this.props.week,
                    type: 'json',
                    method: 'GET',

                    error: function(err) {
                        reject(err);
                    },

                    success: function(tasks) {
                        resolve(tasks);
                    }
                });
            }.bind(this)
        );
    },

    loadTasksAtDate: function(date) {
        return new Promise(
            function (resolve, reject) {
                reqwest({
                    url: this.props.taskUrl + date.toISOString().split('T').shift(), // FIXME: need better timezone handling
                    type: 'json',
                    method: 'GET',

                    error: function(err) {
                        reject(err);
                    },

                    success: function(tasks) {
                        resolve(tasks);
                    }
                });
            }.bind(this)
        );
    },

    getDayIndex: function(date) {
        return this.props.dates
            .map(
                function (date) {
                    return date.toISOString();
                }
            )
            .indexOf(date.toISOString());
    },

    reloadDay: function(date) {
        var index = this.getDayIndex(date);

        if (-1 == index) {
            return;
        }

        this.loadTasksAtDate(date)
            .then(
                function (tasks) {
                    this.updateNumbers(tasks);

                    tasks.forEach(
                        function (task) {
                            task.date = task.date.split('T').shift(); // FIXME: need better timezone handling
                        }
                    );

                    var days = this.state.tasks;
                    days[index] = tasks;

                    this.setState({ tasks: days });
                }.bind(this)
            );
    },

    updateNumbers: function(tasks) {
        var estimate = 0,
            consumed = 0,
            remaining = 0;

        tasks.forEach(
            function (task) {
                estimate += task.estimate;
                consumed += task.consumed;
                remaining += task.remaining;
            }
        );

        this.setState({ estimate: estimate, consumed: consumed, remaining: remaining });
    },

    componentWillMount: function() {
        this.loadDates()
            .then(
                function (dates) {
                    this.loadTasks(dates)
                        .then(
                            function (tasks) {
                                this.updateNumbers(tasks);
                                var days = [];

                                dates.forEach(
                                    function (date) {
                                        var tasksForDay = [];

                                        tasks.forEach(
                                            function (task) {
                                                task.date = task.date.split('T').shift(); // FIXME: need better timezone handling

                                                if (task.date == date) {
                                                    tasksForDay.push(task);
                                                }
                                            }
                                        );

                                        days.push(tasksForDay);
                                    }
                                );

                                this.setState({ tasks: days });
                            }.bind(this)
                        );
                }.bind(this)
            );
    },

    render: function() {
        var avatarStyle = { backgroundImage: 'url(\'/' + this.props.avatar + '\')' },
            colorStyle = { backgroundColor: this.props.color },
            days = [],
            keyCounter = 0;

        this.state.tasks.forEach(
            function (day) {
                var tasks = [],
                    key = this.props.id + '-' + (++keyCounter);

                day.forEach(
                    function (task) {
                        var reloadDay = function() {
                            this.reloadDay(new Date(task.date));
                        }.bind(this);

                        tasks.push(
                            <Task key={task.id} {...task} color={this.props.color} reloadDay={reloadDay} />
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