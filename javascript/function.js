
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

/**
 * 生成一个 0-below-1 之间的数字
 * @param  {[type]} below [description]
 * @return {[type]}       [description]
 */
function randomInteger(below) {
	return Math.floor(Math.random() * below);
}

/**
 * 随机返回数组的一个元素
 * @param  {array} array 数组
 * @return {mixed}       数组元素
 */
function randomElement(array) {
	if (array.length == 0)
		throw new Error("随机元素，数组不能为空");
	return array[randomInteger(array.length)];
}

/**
 * 过滤词语
 * @param  {string}  text 要过滤的词语
 * @return {Boolean}      [description]
 */
function isAcceptable(text) {
	var blackWords = ["ape", "monkey", "simian", "evolution"];
	var pattern = new RegExp(blackWords.join("|"), 'i');
	return !pattern.test(text);
}

/**
 * 将字符串按行分割
 * @param  {string} string 要分割的字符串
 * @return {array}        分割后的行数组
 */
function splitLines(string) {
	return string.split(/\r?\n/);
}

/**
 * 简单的解析INI文件方法
 * @param  {string} string ini文件内容
 * @return {array}        处理后的数组
 */
function parseINI(string) {
	var lines = splitLines(string);
	var categories = [];

	function newCategory(name) {
		var cat = {name: name, fields: []};
		categories.push(cat);
		return cat;
	}

	var currentCategory = newCategory("TOP");

	// todo
	forEach(lines, function(line)) {
		var match;
		// 空格 + ;，忽略
		if (/^\s*(;.*)?$/.test(line))
			return ;
		else if (match = line.match(/^\[(.*)\]$/)){	//[]
			currentCategory = newCategory(match[1]);
		} else if (match = line.match(/^(\w+)=(.*)$/)) {
			currentCategory.fields.push({name: match[1], value: match[2]});
		} else {
			throw new Error("Line '" + line + "' is invalid");
		}
	}

	return categories;
}

/**
 * 对for循环 进行封装
 * @param  {object|array}   array    对象或者数据，可以循环的家伙
 * @param  {Function} callback 回调方法
 * @return {null}            null
 */
function forEach(array, callback) {
	for(let i=0; i<array.length; i++) {
		callback(array[i]);
	}
}

// 检测对象自身是否含有某个属性，不包括继承的
function forEachIn(object, action) {
    for (var property in object) {
        // Object.prototype.hasOwnProperty.call(object, property) , 如果对象有一个hasOwnProperty属性的话
        if (Object.hasOwnProperty(property)) {
            action(property, Object[property]);
        }
    }
}

function print() {
	var result = [];
	forEach(arguments, function(arg){
		result.push(String(arg));
	});
	console.log(result.join(""))
}