var React = require('react'),
    Numbers = require('./numbers'),
    Task = require('./task');

module.exports = React.createClass({
    render: function() {
        var avatarStyle = { backgroundImage: 'url(\'' + this.props.avatar + '\')' };
        var colorStyle = { backgroundColor: this.props.color };

        return (
            <div className="member">
                <div className="member__info">
                    <div className="member__identity">
                        <div className="avatar avatar--medium" style={avatarStyle}></div>
                        <div className="member__name">
                            <span className="member__color" style={colorStyle}></span>
                            {this.props.name}
                        </div>
                    </div>
                    <div className="numbers">
                        <div className="numbers__estimate" title="Estimate">{this.props.estimate}</div>
                        <div className="numbers__consumed" title="Consumed">{this.props.consumed}</div>
                        <div className="numbers__remaining" title="Remaining">{this.props.remaining}</div>
                    </div>
                </div>
                {this.props.children}
            </div>
        );
    }
});