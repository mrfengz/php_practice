function range(start, end) {
    if (arguments.length < 2) {
        end = start;
        start = 0;
    }
    arr = [];
    for(var i = start; i <= end; i++) {
        arr.push(i);
    }

    return arr;
}


// 就可以避免写for xxx了
function forEach(array, callback) {
    for(var i = 0; i < array.length; i++) {
        callback(array[i], i);
    }
}

/**
 * 求和
 * @param numbers
 * @returns {number}
 */
function sum(numbers) {
    var total = 0;
    forEach(numbers, function(number){
        total += number;
    });

    return total;
}

function splitParagraph(text) {
    function split(pos) {
        if (pos == text.length) {
            return [];
        }
        else if(text.chatAt(pos) == '*') {
            var end = findClosing("*", pos + 1),
                frag = {type: "emphasized", content: text.slice(pos+1, end)};

            return [frag].concat(split(end+1));
        }
        else if(text.charAt(pos) == '{') {
            var end = findClosing('}', pos + 1),
                frag = {type: 'footnote', content: text.slice(pos + 1, end)};

            return [frag] . concat(split(end+1));
        } else {
            var end = findOpeningOrEnd(pos),
                frag = {type: 'normal', content: text.slice(pos, end)};

            return [frag].concat(splot(end));
        }
    }

    // 找对应的字符的位置
    function findClosing(character, from) {
        var end = text.indexOf(character, from);
        if (end == -1) {
            throw new Error("Missing closing '" + character + "'" );
        } else {
            return end;
        }
    }

    function findOpeningOrEnd(from) {
        function indexOrEnd(character) {
           var index = text.indexOf(character, from);
           return index == -1 ? text.length : index;
        }

        return Math.min(indexOrEnd("*"), indexOrEnd("{"));
    }

    return split(0);
}


function split() {
    var pos = 0, fragments = [];
    while(pos < text.length) {
        if (text.chatAt(pos) == '*') {
            var end = findClosing('*', pos + 1);
            fragments.push({type: 'emphasized', content: text.slice(pos+1, end)});
            pos = end + 1;
        } else if (text.chatAt(pos) == '{') {
            var end = findClosing('}', pos+1);
            fragments.push({type: 'footnote', content: text.slice(pos + 1, end)});
            pos = end+1;
        } else {
            var end = findOpeningOrEnd(pos);
            fragments.push({type: 'nomral', content: text.slice(pos, end)});
            pos = end;
        }
    }

    return fragments;
}

// 段落：标题和普通段落处理
function processParagraph(paragraph) {
    var header = 0;
    while(paragraph.charAt(header) == '%') {
        header++;
        if (header > 0) {
            return {type: 'h' + header, content: splitParagraph(paragraph.slice(header+1))}
        } else {
            return {type: 'p', content: splitParagraph(paragraph)}
        }
    }
}

//移动脚注
function extractFootnotes(paragraphs) {
    var footnotes = [],
        currentNote = 0;

    function replaceFootnote(fragement) {
        if (fragement.type == 'footnote') {
            currentNote++;
            footnotes.push(fragement);
            fragement.number = currentNote;
            return {type: 'reference', number: currentNote};
        } else {
            return fragement;
        }

        forEach(paragraphs, function(paragraph) {
            paragraph.content = map(replaceFootnote, paragraph.content);
        });

        return footnotes;
    }
}

// 标准化html元素
function tag(name, content, attributes) {
    return {name: name, attributes: attributes, content: content};
}

function link(target, text) {
    return tag('a', [text], {href: target});
}

function htmlDoc(title, bodyContent) {
    return tag("html", [tag("head", [tag("title", [title])]),tag("body", bodyContent)]);
}

// 特殊字符转化
function escapeHtml(text) {
    var replacements = [[/&/g, "&amp;"], [/"/g, "&quot;"],
        [/</g, "&lt;"], [/>/g, "&gt;"]
    ];
    forEach(replacements, function(replace) {
        text = text.replace(replace[0], replace[1]);
    });

    return text;
}

// 生成属性
function renderAttributes(attributes) {
    if (attributes == null) return "";

    var result = [];
    for(var name in attributes) {
        result.push(" " + name + "=\"" + escapeHtml(attributes[name]) + "\"");
    }

    return result.join("");
}

//把一个html元素对象转为一个字符串
function renderHTML(element) {
    var pieces = [];

    function render(element) {
        // 文本节点
        if (typeof element == "string") {
            pieces.push(escapeHtml(element));
        // 不带内容的标签
        } else if (!element.content || element.content.length == 0) {
            pieces.push("<" + element.name + renderAttributes(element.attributes) + ">");
        // 带有内容的标签
        } else {
            pieces.push("<" + element.name + renderAttributes(element.attributes) + ">");
            forEach(element.content, render);
            pieces.push("</" + element.name + ">");
        }
    }

    render(element);
    return pieces.join("");
}

// 函数技巧
var op = {
    "+": function (a, b) {return a+b;},
    "*": function (a, b) {return a*b;},
    "==": function (a, b) {return a == b;},
    "===": function (a, b) {return a=== b;},
    "!": function (a) {return !a;}
//    more
};

[1, 2, 3, 4, 5].reduce(op["+"], 0) // 15

//分布应用 一个函数为参数，作为被调用的函数，一个或者多个参数，调用传入函数，原来的参数和新传入的参数，都被x调用
function partial(func) {
    var knownArgs = arguments;

    return function(){
        var realArgs = [];
        for(var i=1; i<knownArgs.length; i++) {
            realArgs.push(knownArgs[i]);
        }

        for(var i=0; i<arguments.length; i++) {
            realArgs.push(arguments[i]);
        }

        return func.apply(null, realArgs);
    }
}

[0, 2, 4, 6, 8].map(partial(op["+"], 1));

// 组合
function negate(func) {
    return function(){
        return !func.apply(null, arguments);
    }
}

// --------面向对象----------
function Rabbit(name)
{
    this.name = name;
}

Rabbit.constructor;
//Rabbit.prototype = {constructor: f}
Rabbit.prototype.run = function(speed) {
    this.speed = speed;
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

// 字典，包含四种方法，添加key、value的store(), 查询值的lookup(), 测试是否包含key的contains()和遍历词典内容的高阶函数 each()
function Dictionary(startValues) {
    this.values = startValues || {};
}
//存储
Dictionary.prototype.store = function(key, value) {
    this.values[key] = value;
}
// 查找key对应的值
Dictionary.prototype.lookup = function(key) {
    return this.values[key];
}
// 是否包含某个key（仅自身，不包括继承得到的）
Dictionary.prototype.contains = function(key) {
    return Object.prototype.propertyIsEnumerable.call(this.values, key);
}
// 遍历
Dictionary.prototype.each = function(action) {
    forEachIn(this.values, action);
}