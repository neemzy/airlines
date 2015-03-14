(function () {
    'use strict';

    jest.autoMockOff();

    var React = require('react/addons'),
        Board = require('../../components/board');

    describe(
        'Board',
        function () {
            describe(
                'Heading',
                function () {
                    it(
                        'should render with the right dates',
                        function () {
                            var board = React.addons.TestUtils.renderIntoDocument(<Board />);

                            // Mock Promise
                            board.loadDates = jest.genMockFunction().mockReturnValue({
                                then: function(callback) {
                                    callback(['1970-01-01', '1970-01-02', '1970-01-03']);
                                }
                            });

                            // Render header
                            board.updateData();
                            expect(board.loadDates.mock.calls.length).toEqual(1);

                            // Check header rendering
                            var headings = React.addons.TestUtils.scryRenderedDOMComponentsWithClass(board, 'board__head-day');

                            expect(headings.length).toEqual(3);
                            expect(headings[0].props.children).toEqual(new Date('1970-01-01').toDateString());
                            expect(headings[1].props.children).toEqual(new Date('1970-01-02').toDateString());
                            expect(headings[2].props.children).toEqual(new Date('1970-01-03').toDateString());
                        }
                    );
                }
            );

            describe(
                'Members',
                function () {
                    it(
                        'should render with the right names',
                        function () {
                            var board = React.addons.TestUtils.renderIntoDocument(<Board />);

                            // Mock Promise
                            board.loadMembers = jest.genMockFunction().mockReturnValue({
                                then: function(callback) {
                                    callback([
                                        { id: 1, name: 'Jessie' },
                                        { id: 2, name: 'James' }
                                    ]);
                                }
                            });

                            // Render Members
                            board.updateData();
                            expect(board.loadMembers.mock.calls.length).toEqual(1);

                            // Check Members rendering
                            var members = React.addons.TestUtils.scryRenderedDOMComponentsWithClass(board, 'member__name');

                            expect(members.length).toEqual(2);
                            expect(members[0].props.children[1]).toContain('Jessie');
                            expect(members[1].props.children[1]).toContain('James');
                        }
                    );
                }
            );
        }
    );
})();
