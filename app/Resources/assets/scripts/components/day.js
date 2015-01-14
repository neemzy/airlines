(function () {
    'use strict';

    var React = require('react/addons'),
        Promise = require('promise'),
        reqwest = require('reqwest'),
        DragDropMixin = require('react-dnd').DragDropMixin,
        ItemTypes = require('../imports/itemTypes'),
        Task = require('./task');

    module.exports = React.createClass({
        mixins: [
            DragDropMixin
        ],



        /**
         * Initial state React hook
         *
         * @return object
         */
        getInitialState: function() {
            return {
                tasks: []
            };
        },



        /**
         * Loads Tasks from the database for this Day
         *
         * @return Promise
         */
        loadTasks: function() {
            return new Promise(
                function (resolve, reject) {
                    reqwest({
                        url: this.props.taskUrl,
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
         * Updates Tasks in commponent's state
         *
         * @return void
         */
        updateTasks: function() {
            this.loadTasks()
                .then(
                    function (tasks) {
                        this.setState({ tasks: tasks });
                        this.updateNumbers();
                    }.bind(this)
                );
        },



        /**
         * Updates parent Member's Numbers
         *
         * @return void
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



        /**
         * Drag'n'drop mixin configuration callback
         *
         * @param function registerType Item type registration closure
         *
         * @return void
         */
        configureDragDrop: function(registerType) {
            registerType(
                ItemTypes.TASK,
                {
                    dropTarget: {
                        acceptDrop: function(task) {
                            task.move(
                                this.props.member,
                                this.props.date,
                                this.updateTasks
                            );
                        }
                    }
                }
            );
        },



        /**
         * Pre-mount React hook
         * Triggers Task loading
         *
         * @return void
         */
        componentWillMount: function() {
            this.updateTasks();
        },



        /**
         * Rendering React hook
         *
         * @return void
         */
        render: function() {
            var tasks = [],

                classes = React.addons.classSet({
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
                <div className={classes} {...this.dropTargetFor(ItemTypes.TASK)}>{tasks}</div>
            );
        }
    });
})
();
