/**
 * Multiplatform enterpritation of js .toFixed()
 * @param x - number value
 * @param n - number of fix position
 * @returns {Number}
 */
function floorNumber(x, n) {
	var mult = Math.pow(10, n);
	return Math.floor(x*mult)/mult;
}