(function () {
    'use strict';

    var DateHelper = require('../imports/dateHelper'),
        dateHelper = new DateHelper();



    /**
     * Date instance or ISO string conversion to YYYY-MM-DD string tests
     */
    describe(
        'convert',
        function () {
            it(
                'should return the same value for a YYYY-MM-DD string',
                function () {
                    var date = '2014-12-22';

                    expect(dateHelper.convert(date)).toEqual(date);
                }
            );

            it(
                'should return the right date for a Date instance',
                function () {
                    var date = '2014-12-22',
                        instance = new Date(date);

                    expect(dateHelper.convert(instance)).toEqual(date);
                }
            );

            it(
                'should return the right date for an ISO string',
                function () {
                    var date = '2014-12-22',
                        string = new Date(date).toISOString();

                    expect(dateHelper.convert(string)).toEqual(date);
                }
            );
        }
    );



    /**
     * Universal date comparison (regardless of time) tests
     */
    describe(
        'compare',
        function () {
            it(
                'should return true for identical date strings',
                function () {
                    var date = '2014-12-22';

                    expect(dateHelper.compare(date, date)).toBe(true);
                }
            );

            it(
                'should return false for different date strings',
                function () {
                    var date1 = '2014-12-22',
                        date2 = '2014-12-23';

                    expect(dateHelper.compare(date1, date2)).toBe(false);
                }
            );

            it(
                'should return true for identical Date instances',
                function () {
                    var date = new Date('2014-12-22');

                    expect(dateHelper.compare(date, date)).toBe(true);
                }
            );

            it(
                'should return false for different Date instances',
                function () {
                    var date1 = new Date('2014-12-22'),
                        date2 = new Date('2014-12-23');

                    expect(dateHelper.compare(date1, date2)).toBe(false);
                }
            );

            it(
                'should return true for a string and a Date instance holding identical values',
                function () {
                    var string = '2014-12-22',
                        instance = new Date(string);

                    expect(dateHelper.compare(string, instance)).toBe(true);
                }
            );

            it(
                'should return false for a string and a Date instance holding different values',
                function () {
                    var string = '2014-12-22',
                        instance = new Date('2014-12-23');

                    expect(dateHelper.compare(string, instance)).toBe(false);
                }
            );
        }
    );
})
();
