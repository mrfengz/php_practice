
/**
 * 检测字符串是否以xxx开头
 * @param  {string} string      原始字符串
 * @param  {string} beginString 开头的字符串
 * @return {boolean}             
 */
function startWith(string, beginString){
	return string.slice(0, beginString.length) == beginString;
}

/**
 * 向集合中添加元素
 * @param {object} set  添加到该对象中
 * @param {array} vals 要添加的元素
 */
function addToSet(set, values) {
	for(var i=0; i<values.length; i++)
		set[values[i]] = true;
}

/**
 * 从集合中删除元素
 * @param  {object} set   
 * @param  {array} values 要删除的元素数组
 */
function removeFromSet(set, values) {
	for (var i = 0; i < values.length; i++)
		delete set[values[i]]
}


/**
 * 获取当前的毫秒时间戳
 * @return {number} 毫秒时间戳
 */
function getMicrotime() {
	return (new Date()).getTime();
}