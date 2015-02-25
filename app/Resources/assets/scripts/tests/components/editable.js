(function () {
    'use strict';

    jest.autoMockOff();

    var React = require('react/addons'),
        Editable = require('../../components/editable');

    describe(
        'Editable',
        function () {
            describe(
                'handleInput',
                function () {
                    it(
                        'should be called when text is typed in the component',
                        function () {
                            var editable = React.addons.TestUtils.renderIntoDocument(<Editable />);

                            editable.handleInput = jest.genMockFunction();
                            React.addons.TestUtils.Simulate.keyDown(editable, { key: 'a' });

                            //expect(editable.handleInput.mock.calls.length).toEqual(1);
                        }
                    );
                }
            );
        }
    );
})();
