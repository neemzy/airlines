(function () {
    'use strict';

    jest.autoMockOff();

    var React = require('react/addons'),
        ItemTypes = require('../../imports/itemTypes');

    describe(
        'Day',
        function () {
            it(
                'should display dropping',
                function () {
                    // Partially mock drag'n'drop mixin to make up for internal errors
                    var dnd = require('react-dnd');
                    dnd.DragDropMixin.getActiveDropTargetType = jest.genMockFunction().mockReturnValue(ItemTypes.TASK);
                    jest.setMock('react-dnd', dnd);

                    // We must not require our component before the above is done
                    /*var Day = require('../../components/day'),
                        day = React.addons.TestUtils.renderIntoDocument(<Day />);

                    day.setState({ draggedItemType: ItemTypes.TASK });
                    React.addons.TestUtils.Simulate.dragEnter(day.getDOMNode(), { types: [ItemTypes.TASK] });

                    expect(day.props.className).toContain('day--hovered');*/
                }
            );
        }
    );
})();
