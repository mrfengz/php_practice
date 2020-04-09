require.config({
	baseUrl: './js/',	//单独引入main.js 此时当前目录为html所在目录
	paths: {
		"jquery": "libs/jquery",
		"bootstrap": "libs/bootstrap-3.3.7/js/bootstrap.min",
		"vue": "libs/vue"
	},
	shim: {
		bootstrap: {
			deps: ["jquery"],
		}
	}
});