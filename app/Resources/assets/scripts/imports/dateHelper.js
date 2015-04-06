(function () {
    'use strict';

    var ConvertDate = module.exports = function() {};

    /**
     * Converts a date to its YYYY-MM-DD notation
     *
     * @param {(Date|String)} date
     *
     * @return {String}
     */
    ConvertDate.prototype.convert = function(date) {
        if (date instanceof Date) {
            date = date.toISOString();
        }

        return date.split('T').shift(); // handle ISO strings
    };

    /**
     * Checks if two dates are equal (regardless of time)
     *
     * @param {(Date|String)} date1
     * @param {(Date|String)} date2
     *
     * @return {Boolean}
     */
    ConvertDate.prototype.compare = function(date1, date2) {
        date1 = this.convert(date1);
        date2 = this.convert(date2);

        return date1 === date2;
    };
})();
