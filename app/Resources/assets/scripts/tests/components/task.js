(function () {
    'use strict';

    jest.autoMockOff();

    var React = require('react/addons'),
        ItemTypes = require('../../imports/itemTypes'),
        Task = require('../../components/task');

    describe(
        'Task',
        function () {
            it(
                'should display dropping',
                function () {
                    console.warn = jest.genMockFunction();
                    var task = React.addons.TestUtils.renderIntoDocument(<Task />);
                    expect(console.warn.mock.calls.length).toBeGreaterThan(0);

                    React.addons.TestUtils.Simulate.dragStart(task, { nativeEvent: { dataTransfer: {} }});

                    // see this : https://github.com/gaearon/react-dnd/issues/55
                }
            );
        }
    );
})();
