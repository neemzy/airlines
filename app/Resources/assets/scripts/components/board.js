(function () {
    'use strict';

    var React = require('react'),
        Promise = require('promise'),
        reqwest = require('reqwest'),
        Member = require('./member'),
        Task = require('./task'),
        Numbers = require('./numbers'),
        Editable = require('./editable');

    module.exports = React.createClass({
        /**
         * Initial state React hook
         *
         * @return object
         */
        getInitialState: function() {
            return { dates: [], members: [] };
        },



        /**
         * Loads the working days' dates for the current week
         *
         * @return Promise
         */
        loadDates: function() {
            return new Promise(
                function (resolve, reject) {
                    reqwest({
                        url: this.props.weekUrl,
                        type: 'json',
                        method: 'GET',

                        error: function(err) {
                            reject(err);
                        },

                        success: function(dates) {
                            resolve(dates);
                        }
                    });
                }.bind(this)
            );
        },



        /**
         * Loads all Members from the database for this Board
         *
         * @return Promise
         */
        loadMembers: function() {
            return new Promise(
                function (resolve, reject) {
                    reqwest({
                        url: this.props.memberUrl,
                        type: 'json',
                        method: 'GET',

                        error: function(err) {
                            reject(err);
                        },

                        success: function(members) {
                            resolve(members);
                        }
                    });
                }.bind(this)
            );
        },



        /**
         * Updates this Board's name
         *
         * @param string name New name
         *
         * @return void
         */
        updateName: function(name) {
            reqwest({
                url: this.props.boardUrl,
                type: 'json',
                method: 'PUT',
                data: { name: name },

                error: function(err) {
                    // TODO: error handling, if there's any need
                },

                success: function() {
                    // Nothing more to do
                }
            });
        },



        /**
         * Pre-mount React hook
         * Triggers dates and Members loading
         *
         * @return void
         */
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



        /**
         * Rendering React hook
         * Builds the Board's header and the Members' containing rows
         *
         * @return void
         */
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
                    members.push(
                        <Member key={member.id} {...member} week={this.props.week} dates={this.state.dates} />
                    );
                },
                this
            );

            var handleNameInput = function(name) {
                this.updateName(name);
            }.bind(this);

            return (
                <div>
                    <h1 className="board-title">
                        <Editable handleInput={handleNameInput}>{this.props.name}</Editable>
                    </h1>
                    <div className="board">
                        <header className="board__header">
                            <div className="board__head-title">
                                <a href={this.props.prevUrl} className="board__week-link board__week-link--previous"></a>
                                S{this.props.week} {this.props.year}
                                <a href={this.props.nextUrl} className="board__week-link board__week-link--next"></a>
                            </div>
                            {days}
                        </header>
                        {members}
                    </div>
                </div>
            );
        }
    });
})();
