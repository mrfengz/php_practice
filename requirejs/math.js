// amd写法 不依赖其他文件
define(function(){
	function add(a, b) {
		return a + b;
	}

	return {
		add: add,
	}
})