var React = require('react'),
    CommentList = require('./commentList'),
    CommentForm = require('./commentForm'),
    reqwest = require('reqwest');

module.exports = React.createClass({
    loadComments: function() {
        reqwest({
            url: this.props.url,
            type: 'json',
            method: 'get',
            error: function(err) {
                // Couldn't fetch comments from server
            }.bind(this),

            success: function(comments) {
                this.setState({ comments: comments });
            }.bind(this)
        });
    },

    addComment: function(comment) {
        // Show it optimistically
        comment.id = 0;
        this.setState({ comments: this.state.comments.concat([comment]) });

        // submit comment, return success/failure boolean (use a promise !)
        // remove the comment from the list on failure (the one with an id equal to zero) !

        return true;
    },

    getInitialState: function() {
        return { comments: [] };
    },

    componentWillMount: function() {
        this.loadComments();

        // In the tutorial, they set an interval here to fetch comments again from the server periodically.
        // As for us, we will set up socket.io on the PHP server (https://github.com/RickySu/phpsocket.io),
        // which will allow us to use it clientside as well and achieve actual realtime updates.
        setInterval(this.loadComments, this.props.pollInterval);
    },

    render: function() {
        return (
            <div className="commentBox">
                <span className="commentBox-title">Lâche tes coms</span>
                <CommentList comments={this.state.comments} />
                <CommentForm saveComment={this.addComment} />
            </div>
        );
    }
});