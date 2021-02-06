/**
 * 生态圈，有昆虫
 * #表示墙壁，o表示昆虫
 */


var creatureTypes = new Dictionary();

creatureTypes.register = function(constructor, character) {
    constructor.prototype.character = character;
    this.store(character, constructor);
};


// 1.生态圈
var thePlan = [
    "########################",
    "#     #    #    o     ##",
    "#                      #",
    "#         #####        #",
    "##         #   #  ##   #",
    "#             ##   #   #",
    "##         ###    ##   #",
    "#     ###              #",
    "#     ##     o         #",
    "#  o #       #  o      #",
    "#    #         o       #",
    "########################",
];

// 2.点
function Point(x, y) {
    this.x = x;
    this.y = y;
}

// 移动
Point.prototype.add = function (other) {
    return new Point(this.x + other.x, this.y + other.y);
}

// 3.网格，将生态圈和网格，抽象为不同的类，功能职责单一

// 网格存储数值：方式一
/*
var grid = [
  ["0, 0", "1, 0", "2, 0"],
  ["0, 1", "1, 1", "2, 1"],
];*/

//方式二 x和y的坐标，可以根据 x+width（网格宽度） * y 找到
/*
var grid = [
    "0,0", "1,0", "2,0",
    "0,1", "1,1", "2,1",
];
grid[2+1*3] = "2, 1"    width:总共几列
*/


//width: 列数
//height: 行数

function Grid(width, height) {
    this.width = width;
    this.height = height;
    this.cells = new Array(width * height);
}

Grid.prototype.valueAt = function (point) {
    return this.cells[point.y * this.width + point.x];
}

Grid.prototype.setValueAt = function (point, value) {
    this.cells[point.y * this.width + point.x] = value;
}

Grid.prototype.isInside = function (point) {
    return point.x >= 0 && point.y >= 0 &&
        point.x < this.width && point.y < this.height;
}

Grid.prototype.moveValue = function (from, to) {
    this.setValueAt(to, this.valueAt(from));    //新位置
    this.setValueAt(from, undefined);   //原位置移除
}

Grid.prototype.each = function (action) {
    for (var y = 0; y < this.height; y++) {
        for (var x = 0; x < this.width; x++) {
            var point = new Point(x, y);
            action(point, this.valueAt(point))
        }
    }
}

//------------------------------------------------
// 昆虫移动方向
var directions = new Dictionary({
    "n": new Point(0, -1), //北
    "ne": new Point(1, -1), //东北
    "e": new Point(1, 0),   //东
    "se": new Point(1, 1),  //东南
    "s": new Point(0, 1),  //南
    "sw": new Point(-1, 1),  //西南
    "w": new Point(-1, 0),  //西
    "nw": new Point(-1, -1),  //西北
});

// 昆虫
function StupidBug() {
};

StupidBug.prototype.character = "o";

// 昆虫移动
StupidBug.prototype.act = function (surrondings) {
    return {type: 'move', directory: 's'};
}
// --------------昆虫 结束-----------------

//--------------- 生态圈 -------------
var wall = {};
wall.character = '#';

//根据元素的值，返回是昆虫、墙、还是空闲空间
/*function elementFromCharacter(element) {
    if (element == undefined) {
        return " ";
    } else{
        return element.character;
    }
}*/

//v2 更加通用的函数
/*function elementFromCharacter(character) {
    if (character == " ") {
        return undefined;
    } else if (character == "#") {
        return wall;
    } else if (character == "o") {
        return new StupidBug();
    }
}*/

// v3
function elementFromCharacter(character) {
    if (character == " ") {
        return undefined;
    } else if (character == '#') {
        return wall;
    } else if (creatureTypes.contains(character)) {
        return new (creatureTypes.lookup(character))();
    } else {
        throw new Error("Unknown character:" + character);
    }
}

function Terrarium(plan) {
    var grid = new Grid(plan[0].length, plan.length);

    for (var y = 0; y < plan.length; y++) {
        var line = plan[y];
        for (var x = 0; x < line.length; x++) {
            grid.setValueAt(new Point(x, y), elementFromCharacter(line.charAt(x)));
        }
    }

    this.grid = grid;
}


Terrarium.prototype.toString = function(){
    var characters = [];
    var endOfLine = this.grid.width - 1;

    this.grid.each(function(point, value){
        characters.push(elementFromCharacter(value));

        if (point.x == endOfLine) {
            characters.push("\n");
        }

        return characters.join("");
    });
};

// 将昆虫收集起来， 或者其它有act属性的对象
Terrarium.prototype.listActingCreatures = function(){
    var found = [];
    this.grid.each(function(point, value) {
        if (value != undefined && value.act) {
            found.push({object: value, point: point});
        }
    });

    return found;
}

//列出周围的点，可以走动返回队形的点，不可访问返回 '#'
Terrarium.prototype.listSurroundings = function(center){
    var result = {};
    var grid = this.grid;
    directions.each(function(name, direcion){
        var place = center.add(direction);
        if (grid.isInside(place)) {
            result[name] = characterFromElement(grid.valueAt(place));
        }else {
            result[name] = '#';
        }

        return result;
    })
}

//检测所选方向是否能在网格内部并且无障碍物，否则就忽视它。
Terrarium.prototype.processCreature = function(creature, point) {
    var action = creature.act(this.listSurroundings(point));

    if (action.type == 'move' && directions.contains(action.direction)) {
        var to = point.add(directions.lookup(action.drection));
        if (this.grid.isInside(to) && this.grid.valueAt(to) == undefined) {
            this.grid.moveValue(point, to);
        } else {
            throw new Error("Unsupported action: " + acttion.type);
       }
    }
};

Terrarium.prototype.step = function(){
    forEach(this.listActingCreatures(), bind(this.processCreature, this));
};

// 创建一个生态圈
var terrarium = new Terrarium(thePlan);
terrarium.step();
// todo 未正确显示
console.log(terrarium);

console.log(creatureTypes)


// 新的昆虫类型
function BouncingBug(){
    this.direction = "ne";
}
BouncingBug.prototype.act = function(surroundings) {
    if (surroundings[this.direction] != " ") {
        this.direction = (this.direction == "ne") ? "sw" : "ne";
    }
    return {type: "move", direction: this.direction};
}
creatureTypes.register(BouncingBug, '%');

//喝醉酒的昆虫
function randomInteger(below) {
    return Math.floor(Math.random() * below);
}

Dictionary.prototype.names = function() {
    var names = [];
    this.each(function(name, value) {
        names.push(name);
    });
}

directions.names();
console.log(directions.names())

function randomElement(array) {
    if (array.length == 0) {
        throw new Error("数组为空");
    } else {
        return array[Math.floor(Math.random() * array.length)];
    }
}

console.log(randomElement(["heads", "tails"]));

function DrunkBug() {}
DrunkBug.prototype.act = function(sourroundings) {
    return {type: "move", direction: randomElement(directions.names())};
}

// 注册喝醉酒的昆虫类型
creatureTypes.register(DrunkBug, '~');

// 不同类型的昆虫，共有行为act和character特征，是多态的体现


/* 食物+繁殖 ，丰富的生态系统 */
/*
 * 更逼真的生态系统，添加食物和繁殖的概念
 * 概念：继承 LifeLikeTerrarium
 * 此实例的实现方式：将旧原型对象复制给新原型对象
 */

function clone(object) {
    function OneShotConstructor(){};
    OneShotConstructor.prototype = object;
    return new OneShotConstructor();
}

function LifeLikeTerrarium(plan) {
    Terrarium.call(this, plan);
}

LifeLikeTerrarium.prototype = clone(Terrarium.prototype);
LifeLikeTerrarium.prototype.constructor =LifeLikeTerrarium;

// 记录能量
LifeLikeTerrarium.prototype.processCreature = function(creature, point) {
    var energy, action, self = this;
    //从行为中提取方向，并进行错误检查
    function dir() {
        if (!directions.contains(action.direction)) return null;
        var target = point.add(directions.lookup(action.direction));
        if (self.grid.isInside(target)) {
            return target;
        } else {
            return null;
        }
    }

    //每一个行为都有对应的能量消耗或者补充
    action = creature.act(this.listSurroundings(point));

    if (action.type == "move") {
        energy = this.creatureMove(creature, point, dir());
    } else if (action.type == "eat") {
        energy = this.creatureEat(creature, dir());
    } else if (action.type == "photosynthesize") {
        energy = -1;
    } else if (action.type == "reproduce") {
        energy = this.creatureReproduce(creature, dir());
    } else if (action.type == "wait") {
        energy = 0.2;
    } else {
        throw new Error("Unsupported action: " + action.type);
    }

    creature.energy -= energy;
    //能量<0,消失
    if (creature.energy <= 0) {
        this.grid.setValue(point, undefined);
    }
}

LifeLikeTerrarium.prototype.creatureMove = function(creature, from, to) {
    //检查方向，然后移动
    if (to != null && this.grid.valueAt(to) == undefined) {
        this.grid.moveValue(from, to);
        from.x = to.x;
        from.y = to.y;
    }
    return 1;
};


//进食，检查是否是真正的是食物，是否有能量，然后从网格删除能量，并将能量转移给食物
LifeLikeTerrarium.prototype.creatureEat = function(creature, source) {
    var energy = 1;
    if (source != null) {
        var meal = this.grid.valueAt(source);
        if (meal != undefined && meal.energy) {
            this.grid.setValue(source, undefined);
            energy -= meal.energy;
        }
    }
    return energy;
};

//生育，检测选择位置是否有效且为空，且繁殖消耗能量，为新生生物获取能量的2倍
LifeLikeTerrarium.prototype.creatureReproduce = function(creature, target) {
    var energy = 1;
    if (target != null && this.grid.valueAt(target) == undefined) {
        var species = elementFromCharacter(creature);
        var baby = elementFromCharacter(species);
        energy = baby.energy * 2;
        if (creature.energy >= energy) {
            this.grid.setValueAt(target, baby);
        }
    }
    return energy;
};

/* 添加植物，制造能量 */
function findDirections(surrondings, wanted) {
    var found = [];
    directions.each(function(name){
        if(surrondings[name] == wanted) {
            found.push(name)
        }
    });

    return found;
}

function Lichen() {
    this.energy = 5;
}

Lichen.prototype.act = function(surrondings) {
    var emptySpace = findDirections(surrondings, " ");
    if (this.energy >= 13 && emptySpace.length > 0) {
        return {type: "reproduce", direction: randomElement(emptySpace)}
    } else if (this.energy < 20) {
        return {type: "photosynthesize 光合作用"};
    } else {
        return {type: "wait"};
    }

    creatureTypes.register(Lichen, "*");
};

/* 食草动物 */
function LichenEater() {
    this.energy = 10;
}

LichenEater.prototype.act = function(surrondings) {
    var emptySpace = findDirections(surrondings, " ");
    var lichen = findDirections(surrondings, "*");

    if (this.energy >= 30 && emptySpace.length > 0) {
        return {type: "reproduce", direction: randomElement(emptySpace)};
    } else if (lichen.length > 0) {
        return {type: "eat", direction: randomElement(lichen)};
    } else if (emptySpace.length > 0) {
        return {type: "move", direction: randomElement(emptySpace)};
    } else {
        return {type: "wait"};
    }

    creatureTypes.register(LichenEater, "c");
}

var moodyCave = [
    "#######################",
    "#                  ####",
    "#       **           ##",
    "#       *##           #",
    "#   c    *#           #",
    "#   *    *#     c     #",
    "#       *#*     c     #",
    "#######################",
];

var terrarium = new LifeLikeTerrarium(moodyCave);
for(var i = 0; i< 10; i++) {
    for(var j = 0; j< 20; j++) {
        terrarium.step();
    }
    print(terrarium);
}

// 改进
function CleverLichenEater() {
    this.energy = 10;
    this.direction = "ne";
}

// 周围最少有2个青苔才会去吃，减少动物运动的随机性
CleverLichenEater.prototype.act = function(surrondings) {
    var emptySpace = findDirections(surrondings, " ");
    var lichen = findDirections(surrondings, "*");

    if (surrondings[this.direction] != " ") {
        this.direction = randomElement(emptySpace);
    }

    if (this.energy >= 30 && emptySpace.length > 0) {
        return {type: "reproduce", direction: randomElement(emptySpace)};
    } else if (lichen.length > 1) { //周围最少有2个才会去吃
        return {type: "eat", direction: randomElement(lichen)};
    } else if (emptySpace.length > 0) {
        return {type: "move", direction: randomElement(emptySpace)};
    } else {
        return {type: "wait"};
    }

    creatureTypes.register(CleverLichenEater, "c");
}

//--------------- 生态圈 end -------------

// 一般内部函数中的this都是指向自己的，会无法访问外部的this，通常用self或者that保存this，现在可以这样搞

function bind(func, object) {
    return function(){
        return func.apply(object, arguments);
    }
}

var x= [];

var pushX = bind(x.push, x);

pushX('A');
pushX('B');

console.log(x); //[A, B]

// 有些人喜欢这样
var method = function(object, name) {
    return function() {
        return object[name].apply(object, arguments);
    }
}

var pushY = method(x, 'push');

/* 关于继承 */
Object.prototype.inherit = function(baseConstructor) {
    this.prototype = clone(baseConstructor.prototype);
    this.prototype.constructor = this;
};

Object.prototype.method = function(name, func) {
    this.prototype[name] = func;
};

// 使用
function StrangArray(){};
StrangArray.inherit(Array);
StrangArray.method("push", function(value) {
   Array.prototype.push.call(this, value);
   Array.prototype.push.call(this, value);
});
var strage = new StrangArray();
strang.push(4); // [4, 4]

// 替换new 关键字
Object.prototype.create = function() {
    var object = clone(this);
    if (object.construct != undefined) {
        object.construct.apply(object, arguments);
    }
    return object;
};

// 将传入的属性，复制给克隆的对象
Object.prototype.extend = function(properties) {
    var result = clone(this);
    forEachIn(properties, function(name, value) {
        result[name] = value;
    });
    return result;
};

var Item = {
    construct: function(name) {
        this.name = name;
    },
    inspect: function() {
        print("it is ", this.name, '.');
    },
    kick: function() {
        print("klunk!");
    },
    take: function(){
        print("you cannot lift ", this.name, '.');
    }
}

var lantern = Item.create("the brass lantern");
lantern.take();

// 继承并修改
var DetaildItem = Item.extend({
    construct: function(name, details) {
        console.log()
        Item.construct.call(this, name);
        this.details = details;
    },
    inspect: function(){
        console.log("you see", this.name, ", ", this.details, ".");
    }
});
console.log(DetaildItem)

// 报错，不知道啥原因
var giantSloth = DetaildItem.create("the giant sloth", "it is quietly having from a tree")
giantSloth.inspect();

// 混入，实现类似多重继承的功能
function mixInto(object, mixIn) {
    forEachIn(minIn, function(name, value) {
        object[name] = value;
    })
}

var SmallDetaildItem = clone(DetaildItem);
mixInto(SmallDetaildItem, SmallImem);

var dealMouse = SmallDetaildItem.create("Fred the mouse", "he is dead");
deadMouse.inspect();
deadMouse.kick();

/* 模块化 */

function map(callback, array) {
    var len = array.length, result = new Array(len);
    for(var i=0; i<len; i++)
        result[i] = callback(array[i]);

    return result;
}

(function bindMonthNameModule() {
    var names = ['一季度', '二季度', '三季度', '四季度'];
    function getQuarterName(number) {
        return names[number];
    }

    function getQuarterNumber(name) {
        for(var number=0; number<names.length; number++) {
            if (names[number] == name) {
                return number;
            }
        }
    }

    // 赋值给window变量
    // window.getQuarterName = getQuarterName;
    // window.getQuarterNumber = getQuarterNumber;
    provide({
        getQuarterName: getQuarterName,
        getQuarterNumber: getQuarterNumber,
    })
})();

// bindMonthNameModule();

function provide(values) {
    forEachIn(values, function(name, value) {
        window[name] = name;
    })
}

// 另一种写法
var Quarter = (function(){
    var names = ['一季度', '二季度', '三季度', '四季度'];
    return {
        getQuarterName: function (number) {
            return names[number];
        },
        getQuarterNumber: function (name) {
            for(var number=0; number<names.length; number++) {
                if (names[number] == name) {
                    return number;
                }
            }
        }
    }
})();