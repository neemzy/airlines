(function () {
    'use strict';

    var ConvertDate = module.exports = function() {};

    /**
     * Converts a date to a YYYY-MM-DD string
     *
     * @param {(Date|string)} date
     *
     * @return {string}
     */
    ConvertDate.prototype.convert = function(date) {
        if (!(date instanceof Date)) {
            return this.convert(new Date(date));
        }

        return date.toISOString().split('T').shift(); // handle ISO strings
    };

    /**
     * Checks if two dates are equal (regardless of time)
     *
     * @param {(Date|string)} date1
     * @param {(Date|string)} date2
     *
     * @return {boolean}
     */
    ConvertDate.prototype.compare = function(date1, date2) {
        date1 = this.convert(date1);
        date2 = this.convert(date2);

        return date1 === date2;
    };
})();
