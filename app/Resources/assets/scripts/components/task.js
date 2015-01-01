(function () {
    'use strict';

    var React = require('react/addons'),
        reqwest = require('reqwest'),
        DragDropMixin = require('react-dnd').DragDropMixin,
        ItemTypes = require('../imports/itemTypes'),
        DateHelper = require('../imports/dateHelper'),
        Numbers = require('./numbers'),
        Editable = require('./editable');

    module.exports = React.createClass({
        mixins: [
            DragDropMixin
        ],



        /**
         * Removes this Task from the database and triggers reloading for the member and day it belongs to
         *
         * @return void
         */
        remove: function() {
            if (!confirm('Are you sure ?')) {
                return;
            }

            reqwest({
                url: this.props.restUrl,
                type: 'json',
                method: 'DELETE',

                error: function(err) {
                    // TODO: error handling, if there's any need
                },

                success: function(response) {
                    this.props.reloadDay();
                }.bind(this)
            });
        },



        /**
         * Splits this Task in two and triggers reloading for the member and day it belongs to
         *
         * @return void
         */
        split: function() {
            reqwest({
                url: this.props.splitUrl,
                type: 'json',
                method: 'POST',

                error: function(err) {
                    // TODO: error handling, if there's any need
                },

                success: function(response) {
                    this.props.reloadDay();
                }.bind(this)
            });
        },



        /**
         * Updates this Task's fields
         *
         * @param object data Key-value pairs
         * @param function callback AJAX success callback
         *
         * @return void
         */
        update: function(data, callback) {
            reqwest({
                url: this.props.restUrl,
                type: 'json',
                method: 'PUT',
                data: data,

                error: function(err) {
                    // TODO: error handling, if there's any need
                },

                success: function() {
                    ('function' === typeof callback) && callback();
                    this.props.reloadDay();
                }.bind(this)
            });
        },



        /**
         * Moves this Task to the given member and date
         *
         * @param int      member   Member id
         * @param string   date     New date
         * @param function callback AJAX success callback
         *
         * @return void
         */
        move: function(member, date, callback) {
            var dateHelper = new DateHelper();

            if (!dateHelper.compare(this.props.date, date) || (this.props.member != member)) {
                this.update({ member: member, date: date }, callback);
            }
        },



        /**
         * Drag'n'drop mixin configuration callback
         *
         * @return void
         */
        configureDragDrop: function(registerType) {
            var dateHelper = new DateHelper();

            registerType(
                ItemTypes.TASK,
                {
                    dragSource: {
                        beginDrag: function() {
                            return {
                                item: this
                            };
                        }
                    }
                }
            );
        },



        /**
         * Rendering React hook
         * Passes props through to the Numbers and attaches action handlers
         *
         * @return void
         */
        render: function() {
            var style = {},
                nameStyle = { backgroundColor: this.props.color },
                numbers = {},
                keys = ['estimate', 'consumed', 'remaining', 'overConsumed', 'underEstimated', 'overEstimated'],

                classes = React.addons.classSet({
                    'task': true,
                    'task--dragged': this.getDragState(ItemTypes.TASK).isDragging
                }),

                handleNameInput = function(name) {
                    this.update({ name : name });
                }.bind(this);

            keys.forEach(
                function (key) {
                    numbers[key] = this.props[key];
                },
                this
            );

            return (
                <div className={classes} style={style} {...this.dragSourceFor(ItemTypes.TASK)}>
                    <div className="task__name" style={nameStyle}>
                        <Editable handleInput={handleNameInput}>{this.props.name}</Editable>
                    </div>
                    <Numbers {...numbers} handleInput={this.update} />
                    <div className="task__action-group">
                        <a className="task__action task__action--split" onClick={this.split}></a>
                        <a className="task__action task__action--remove" onClick={this.remove}></a>
                    </div>
                </div>
            );
        }
    });
})
();
