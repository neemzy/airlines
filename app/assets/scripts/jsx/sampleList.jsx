var React = require('react'),
    SampleItem = require('./sampleItem');

module.exports = React.createClass({
    loadItems: function() {
        this.setState(
            {
                items: [
                    { id: 'abc', content: 'ncvfrighbruithgirug' },
                    { id: 'def', content: 'fvrgerfgzesetgdffze' },
                    { id: 'ghi', content: 'xsqcsdcsfgrfthtyhhr' },
                    { id: 'jkl', content: 'crgfnreofeiodzehoif' }
                ]
            }
        )
    },

    getInitialState: function() {
        return { items: [] };
    },

    componentWillMount: function() {
        this.loadItems();
    },

    render: function() {
        var items = this.state.items.map(function (item) {
            return <SampleItem key={item.id}>{item.content}</SampleItem>;
        });

        return (
            <div className="sampleList">{items}</div>
        );
    }
});