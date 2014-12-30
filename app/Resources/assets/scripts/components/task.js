(function () {
    'use strict';

    var React = require('react'),
        reqwest = require('reqwest'),
        Numbers = require('./numbers'),
        Editable = require('./editable');

    module.exports = React.createClass({
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
         *
         * @return void
         */
        update: function(data) {
            reqwest({
                url: this.props.restUrl,
                type: 'json',
                method: 'PUT',
                data: data,

                error: function(err) {
                    // TODO: error handling, if there's any need
                },

                success: function(response) {
                    this.props.reloadDay();
                }.bind(this)
            });
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
                keys = ['estimate', 'consumed', 'remaining', 'overConsumed', 'underEstimated', 'overEstimated'];

            keys.forEach(
                function (key) {
                    numbers[key] = this.props[key];
                },
                this
            );

            var handleNameInput = function(name) {
                this.update({ name : name });
            }.bind(this);

            return (
                <div className="task" style={style}>
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
