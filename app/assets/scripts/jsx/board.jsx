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
        var promises = {},
            nbDays = this.getDateList().length;

        for (var i = 1; i <= nbDays; i++) {
            var date = this.props['day' + i];
            promises[date] = {};

            for (var key in members) {
                var url = members[key].url + date;

                promises[date][members[key].id] = new Promise(
                    function (resolve, reject) {
                        reqwest({
                            url: url,
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
            }
        }

        return promises;
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
                    console.dir(tasks);
                }
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

        /*var members = (
            <Member
             name="TPAN"
             color="rebeccapurple"
             avatar="https://pbs.twimg.com/profile_images/412193761507041280/1eeIlWpC.jpeg"
             estimate="5"
             consumed="1"
             remaining="4.5">
                <Task
                 name="DEV\nRetour lot 98"
                 date="2014-12-22"
                 estimate="1"
                 consumed="1"
                 remaining="0.5" />
                <Task
                 name="DEV\nRetour lot 99"
                 date="2014-12-22"
                 estimate="1"
                 consumed="1"
                 remaining="0.5" />
                <Task
                 name="DEV\nRetour lot 100"
                 date="2014-12-23"
                 estimate="1"
                 consumed="1"
                 remaining="0.5" />
            </Member>
        );*/

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