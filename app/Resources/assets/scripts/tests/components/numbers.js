(function () {
    'use strict';

    jest.autoMockOff();

    var React = require('react/addons'),
        Numbers = require('../../components/numbers');

    describe(
        'Numbers',
        function () {
            it(
                'should render the right values',
                function () {
                    var estimateData = 1,
                        consumedData = 0.5,
                        remainingData = 0.5,

                        numbers = React.addons.TestUtils.renderIntoDocument(
                            <Numbers estimate={estimateData} consumed={consumedData} remaining={remainingData} />
                        ),

                        estimate = React.addons.TestUtils.findRenderedDOMComponentWithClass(numbers, 'numbers__estimate'),
                        estimateValue = React.addons.TestUtils.findRenderedDOMComponentWithClass(estimate, 'numbers__value'),
                        consumed = React.addons.TestUtils.findRenderedDOMComponentWithClass(numbers, 'numbers__consumed'),
                        consumedValue = React.addons.TestUtils.findRenderedDOMComponentWithClass(consumed, 'numbers__value'),
                        remaining = React.addons.TestUtils.findRenderedDOMComponentWithClass(numbers, 'numbers__remaining'),
                        remainingValue = React.addons.TestUtils.findRenderedDOMComponentWithClass(remaining, 'numbers__value');

                    expect(parseFloat(estimateValue.getDOMNode().innerHTML)).toEqual(estimateData);
                    expect(parseFloat(consumedValue.getDOMNode().innerHTML)).toEqual(consumedData);
                    expect(parseFloat(remainingValue.getDOMNode().innerHTML)).toEqual(remainingData);
                }
            );

            it(
                'should display overconsumption',
                function () {
                    var estimateData = 1,
                        consumedData = 1.5,
                        remainingData = 0,

                        numbers = React.addons.TestUtils.renderIntoDocument(
                            <Numbers estimate={estimateData} consumed={consumedData} remaining={remainingData} />
                        ),

                        consumed = React.addons.TestUtils.findRenderedDOMComponentWithClass(numbers, 'numbers__consumed');

                    expect(consumed.props.className).toContain('is-over');
                }
            );

            it(
                'should display underestimation',
                function () {
                    var estimateData = 1,
                        consumedData = 1,
                        remainingData = 0.5,

                        numbers = React.addons.TestUtils.renderIntoDocument(
                            <Numbers estimate={estimateData} consumed={consumedData} remaining={remainingData} />
                        ),

                        remaining = React.addons.TestUtils.findRenderedDOMComponentWithClass(numbers, 'numbers__remaining');

                    expect(remaining.props.className).toContain('is-over');
                }
            );

            it(
                'should display overestimation',
                function () {
                    var estimateData = 1,
                        consumedData = 0.5,
                        remainingData = 0,

                        numbers = React.addons.TestUtils.renderIntoDocument(
                            <Numbers estimate={estimateData} consumed={consumedData} remaining={remainingData} />
                        ),

                        remaining = React.addons.TestUtils.findRenderedDOMComponentWithClass(numbers, 'numbers__remaining');

                    expect(remaining.props.className).toContain('is-under');
                }
            );
        }
    );
})();
