var React = require('react');

module.exports = React.createClass({
    render: function() {
        return (
            <div className="sampleItem">
                {this.props.children}
            </div>
        );
    }
});