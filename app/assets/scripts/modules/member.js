var React = require('react'),
    Promise = require('promise'),
    reqwest = require('reqwest'),
    DateHelper = require('../imports/dateHelper'),
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
            estimate: 0,
            consumed: 0,
            remaining: 0,
            tasks: []
        };
    },



    /**
     * Loads all Tasks from the database for this Member and the parent Board's current week
     *
     * @return Promise
     */
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



    /**
     * Loads Tasks from the database for this Member and the given day
     *
     * @param Date date
     *
     * @return Promise
     */
    loadTasksAtDate: function(date) {
        var dateHelper = new DateHelper();

        return new Promise(
            function (resolve, reject) {
                reqwest({
                    url: this.props.taskUrl + dateHelper.convert(date),
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



    /**
     * Retrieves the given day's index in the parent Board's current week
     *
     * @param Date date
     *
     * @return int
     */
    getDayIndex: function(date) {
        return this.props.dates
            .map(
                function (date) {
                    return date.toISOString();
                }
            )
            .indexOf(date.toISOString());
    },



    /**
     * Reloads the given day's Tasks for this Member
     *
     * @param Date date
     *
     * @return void
     */
    reloadDay: function(date) {
        var dateHelper = new DateHelper(),
            index = this.getDayIndex(date);

        if (-1 == index) {
            return;
        }

        this.loadTasksAtDate(date)
            .then(
                function (tasks) {
                    this.updateNumbers(tasks);

                    tasks.forEach(
                        function (task) {
                            task.date = dateHelper.convert(task.date);
                        }
                    );

                    var days = this.state.tasks;
                    days[index] = tasks;

                    this.setState({ tasks: days });
                }.bind(this)
            );
    },



    /**
     * Updates this Member's Numbers from an unordered, just-fetched Task list
     *
     * @param Array tasks
     *
     * @return void
     */
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



    /**
     * Pre-mount React hook
     * Loads the tasks and sorts them by day for easier rendering
     *
     * @return void
     */
    componentWillMount: function() {
        var dateHelper = new DateHelper();

        this.loadTasks()
            .then(
                function (tasks) {
                    this.updateNumbers(tasks);
                    var days = [];

                    this.props.dates.forEach(
                        function (date) {
                            var tasksForDay = [];

                            tasks.forEach(
                                function (task) {
                                    if (dateHelper.compare(task.date, date)) {
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
    },



    /**
     * Rendering React hook
     * Builds the whole Member's week row
     *
     * @return void
     */
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