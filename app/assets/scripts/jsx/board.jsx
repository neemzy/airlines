var React = require('react'),
    Promise = require('promise'),
    reqwest = require('reqwest'),
    Numbers = require('./numbers'),
    Member = require('./member'),
    Task = require('./task');

module.exports = React.createClass({
    getInitialState: function() {
        return { members: [] };
    },

    getDateList: function() {
        return [
            new Date(this.props.day1),
            new Date(this.props.day2),
            new Date(this.props.day3),
            new Date(this.props.day4),
            new Date(this.props.day5)
        ];
    },

    loadMembers: function() {
        return new Promise(
            function (resolve, reject) {
                reqwest({
                    url: this.props.url,
                    type: 'json',
                    method: 'GET',

                    error: function(err) {
                        reject(err);
                    }.bind(this),

                    success: function(members) {
                        resolve(members);
                    }.bind(this)
                });
            }.bind(this)
        );
    },

    loadTasks: function(members) {
        // We will return members as the first item in our task promise array,
        // in order to grant ourselves access to it in the second callback
        var promises = [members];

        for (var key in members) {
            promises.push(
                new Promise(
                    function (resolve, reject) {
                        reqwest({
                            url: members[key].url + this.props.week,
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
                )
            );
        }

        return Promise.all(promises);
    },

    componentWillMount: function() {
        this.loadMembers()
            .then(
                function (members) {
                    return this.loadTasks(members);
                }.bind(this)
            )
            .then(
                function (tasks) {
                    members = tasks.shift(); // see above

                    members.forEach(
                        function (member) {
                            member.tasks = tasks.shift();
                        }
                    );

                    this.setState({ members: members });
                }.bind(this)
            );
    },

    render: function() {
        var members = [],
            days = [],
            dates = this.getDateList();

        dates.forEach(
            function (date) {
                days.push(
                    <div className="board__head-day" key={date.getTime()}>
                        {date.toLocaleString({}, { weekday: 'long', day: 'numeric', month: 'numeric' })}
                    </div>
                );
            }
        );

        this.state.members.forEach(
            function (member) {
                var days = [];

                dates.forEach(
                    function (date) {
                        date = date.toISOString().split('T').shift(); // FIXME: need better timezone handling

                        var tasks = [],
                            key = date + member.id;

                        member.tasks.forEach(
                            function (task) {
                                task.date = task.date.split('T').shift(); // FIXME: need better timezone handling

                                if (date == task.date) {
                                    tasks.push(
                                        <Task key={task.id}
                                              name={task.name}
                                              date={task.date}
                                              color={member.color}
                                              estimate={task.estimate}
                                              consumed={task.consumed}
                                              remaining={task.remaining}
                                              overConsumed={task.overConsumed}
                                              underEstimated={task.underEstimated}
                                              overEstimated={task.overEstimated} />
                                    );
                                }
                            }
                        );

                        days.push(
                            <div key={key} className="member__day">{tasks}</div>
                        );
                    }
                );

                members.push(
                    <Member key={member.id}
                            name={member.name}
                            avatar={member.avatar}
                            color={member.color}
                            estimate="0"
                            consumed="0"
                            remaining="0">{days}</Member>
                );
            }
        );

        return (
            <div className="board">
                <header className="board__header">
                    <div className="board__head-title">S{this.props.week}</div>
                    {days}
                </header>
                {members}
            </div>
        );
    }
});