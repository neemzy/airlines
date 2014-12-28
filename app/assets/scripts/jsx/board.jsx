var React = require('react'),
    Promise = require('promise'),
    reqwest = require('reqwest'),
    Member = require('./member'),
    Task = require('./task'),
    Numbers = require('./numbers');

module.exports = React.createClass({
    getInitialState: function() {
        return { dates: [], members: [] };
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

    loadMembers: function() {
        return new Promise(
            function (resolve, reject) {
                reqwest({
                    url: this.props.memberUrl,
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

    componentWillMount: function() {
        this.loadDates()
            .then(
                function (dates) {
                    dates = dates.map(
                        function (date) {
                            return new Date(date);
                        }
                    );

                    this.setState({ dates: dates });
                }.bind(this)
            );

        this.loadMembers()
            .then(
                function (members) {
                    this.setState({ members: members });
                }.bind(this)
            );
    },

    render: function() {
        var members = [],
            days = [];

        this.state.dates.forEach(
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
                var taskUrl = member.url + this.props.week;

                members.push(
                    <Member key={member.id}
                            id={member.id}
                            name={member.name}
                            avatar={member.avatar}
                            color={member.color}
                            weekUrl={this.props.weekUrl}
                            taskUrl={taskUrl} />
                );
            }.bind(this)
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