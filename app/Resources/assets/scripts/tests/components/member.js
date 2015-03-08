(function () {
    'use strict';

    jest.autoMockOff();

    var React = require('react/addons'),
        Member = require('../../components/member'),
        Day = require('../../components/day'),
        Task = require('../../components/task');

    describe(
        'Member',
        function () {
            describe(
                'Days',
                function () {
                    it(
                        'should render as many as dates in props',
                        function () {
                            var date = '1970-01-01',
                                member = React.addons.TestUtils.renderIntoDocument(<Member dates={['1970-01-01', '1970-01-02']} />),
                                days = React.addons.TestUtils.scryRenderedComponentsWithType(member, Day);

                            expect(days.length).toEqual(2);
                        }
                    )
                }
            );

            describe(
                'Numbers',
                function () {
                    it(
                        'should sum up to the right values',
                        function () {
                            var member = React.addons.TestUtils.renderIntoDocument(<Member dates={['1970-01-01']} />),
                                day = React.addons.TestUtils.findRenderedComponentWithType(member, Day);

                            // Mock Promise
                            day.loadTasks = jest.genMockFunction().mockReturnValue({
                                then: function(callback) {
                                    callback([
                                        { id: 1, estimate: 1, consumed: 0.5, remaining: 0.5 },
                                        { id: 2, estimate: 0.5, consumed: 0, remaining: 2 }
                                    ]);
                                }
                            });

                            // Inject Tasks
                            console.warn = jest.genMockFunction();
                            day.updateTasks();
                            expect(day.loadTasks.mock.calls.length).toEqual(1);
                            expect(console.warn.mock.calls.length).toBeGreaterThan(0);

                            // Test Member's Numbers' values
                            var info = React.addons.TestUtils.findRenderedDOMComponentWithClass(member, 'member__info'),
                                estimate = React.addons.TestUtils.findRenderedDOMComponentWithClass(info, 'numbers__estimate'),
                                estimateValue = React.addons.TestUtils.findRenderedDOMComponentWithClass(estimate, 'numbers__value'),
                                consumed = React.addons.TestUtils.findRenderedDOMComponentWithClass(info, 'numbers__consumed'),
                                consumedValue = React.addons.TestUtils.findRenderedDOMComponentWithClass(consumed, 'numbers__value'),
                                remaining = React.addons.TestUtils.findRenderedDOMComponentWithClass(info, 'numbers__remaining'),
                                remainingValue = React.addons.TestUtils.findRenderedDOMComponentWithClass(remaining, 'numbers__value'),
                                memberEstimate = parseFloat(estimateValue.props.children),
                                memberConsumed = parseFloat(consumedValue.props.children),
                                memberRemaining = parseFloat(remainingValue.props.children);

                            expect(memberEstimate).toEqual(1.5);
                            expect(memberConsumed).toEqual(0.5);
                            expect(memberRemaining).toEqual(2.5);

                            // Test Tasks' Numbers' values
                            var tasks = React.addons.TestUtils.scryRenderedComponentsWithType(member, Task),
                                taskEstimate = 0,
                                taskConsumed = 0,
                                taskRemaining = 0;

                            tasks.forEach(
                                function (task) {
                                    var estimate = React.addons.TestUtils.findRenderedDOMComponentWithClass(task, 'numbers__estimate'),
                                        estimateValue = React.addons.TestUtils.findRenderedDOMComponentWithClass(estimate, 'numbers__value'),
                                        consumed = React.addons.TestUtils.findRenderedDOMComponentWithClass(task, 'numbers__consumed'),
                                        consumedValue = React.addons.TestUtils.findRenderedDOMComponentWithClass(consumed, 'numbers__value'),
                                        remaining = React.addons.TestUtils.findRenderedDOMComponentWithClass(task, 'numbers__remaining'),
                                        remainingValue = React.addons.TestUtils.findRenderedDOMComponentWithClass(remaining, 'numbers__value');

                                    taskEstimate += parseFloat(estimateValue.props.children);
                                    taskConsumed += parseFloat(consumedValue.props.children);
                                    taskRemaining += parseFloat(remainingValue.props.children);
                                }
                            );

                            expect(taskEstimate).toEqual(memberEstimate);
                            expect(taskConsumed).toEqual(memberConsumed);
                            expect(taskRemaining).toEqual(memberRemaining);
                        }
                    );
                }
            );
        }
    );
})();
