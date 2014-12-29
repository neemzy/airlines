/**
 * Constructor
 *
 * @return void
 */
var ConvertDate = module.exports = function() {
};



/**
 * Converts a date to a YYYY-MM-DD string
 *
 * @param mixed date Date instance or string
 *
 * @return string
 */
ConvertDate.prototype.convert = function(date) {
    if (date instanceof Date) {
        date = date.toISOString();
    }

    return date.split('T').shift(); // handle ISO strings
};



/**
 * Compares two dates (regardless of time)
 *
 * @param mixed date1 Date instance or string
 * @param mixed date2 Date instance or string
 *
 * @return bool
 */
ConvertDate.prototype.compare = function(date1, date2) {
    date1 = this.convert(date1);
    date2 = this.convert(date2);

    return date1 === date2;
}