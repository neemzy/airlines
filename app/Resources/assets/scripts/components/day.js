(function () {
    'use strict';

    var React = require('react'),
        Promise = require('promise'),
        reqwest = require('reqwest'),
        classNames = require('classnames'),
        DragDropMixin = require('react-dnd').DragDropMixin,
        ItemTypes = require('../imports/itemTypes'),
        Task = require('./task');

    module.exports = React.createClass({
        mixins: [
            DragDropMixin
        ],

        statics: {
            /**
             * @param {Function} registerType
             */
            configureDragDrop: function(registerType) {
                registerType(
                    ItemTypes.TASK,
                    {
                        dropTarget: {
                            acceptDrop: function(day, task) {
                                task.move(
                                    day.props.member,
                                    day.props.date,
                                    day.updateTasks
                                );
                            }
                        }
                    }
                );
            }
        },

        /**
         * @return {Object}
         */
        getInitialState: function() {
            return {
                tasks: []
            };
        },

        /**
         * @param {String} method
         * @param {Object} data
         *
         * @return {Promise}
         */
        makeRequest: function(method, data) {
            if (undefined === method) {
                method = 'GET';
            }

            return new Promise(
                function (resolve, reject) {
                    reqwest({
                        url: this.props.taskUrl,
                        type: 'json',
                        method: method,
                        data: data,

                        error: function(err) {
                            reject(err);
                        },

                        success: function(response) {
                            resolve(response);
                        }
                    });
                }.bind(this)
            );
        },

        /**
         * Updates parent Member's Numbers
         */
        updateNumbers: function() {
            var estimate = 0,
                consumed = 0,
                remaining = 0;

            this.state.tasks.forEach(
                function (task) {
                    estimate += task.estimate;
                    consumed += task.consumed;
                    remaining += task.remaining;
                }
            );

            this.props.updateNumbers(
                this.props.date,
                {
                    estimate: estimate,
                    consumed: consumed,
                    remaining: remaining
                }
            );
        },

        updateTasks: function() {
            this.makeRequest()
                .then(
                    function (tasks) {
                        // We have to force a full re-render of this Day's Tasks,
                        // as React is unable to detect the change in the Editable
                        // when emptying a Task's name and failing to persist it
                        this.setState(
                            { tasks: [] },
                            function () {
                                this.setState(
                                    { tasks: tasks },
                                    function () {
                                        this.updateNumbers();
                                    }.bind(this)
                                );
                            }.bind(this)
                        );
                    }.bind(this)
                );
        },

        createTask: function() {
            this.makeRequest('POST', { name: 'Edit me' }) // dummy name
                .then(
                    function (task) {
                        // Optimistic display
                        var tasks = this.state.tasks;

                        tasks.push(task);
                        this.setState({ tasks: tasks });
                    }.bind(this)
                );
        },

        componentWillMount: function() {
            this.updateTasks();
        },

        /**
         * @return {Object}
         */
        render: function() {
            var tasks = [],

                classes = classNames({
                    'day': true,
                    'day--hovered': this.getDropState(ItemTypes.TASK).isHovering
                });

            this.state.tasks.forEach(
                function (task) {
                    tasks.push(
                        <Task key={task.id} {...task} handleUpdate={this.updateTasks} color={this.props.color} />
                    );
                },
                this
            );

            return (
                <div className={classes} {...this.dropTargetFor(ItemTypes.TASK)}>
                    {tasks}
                    <div className="action-group">
                        <a className="action-group__item action-group__item--create" title="Create task" onClick={this.createTask}></a>
                    </div>
                </div>
            );
        }
    });
})();
