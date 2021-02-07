/**
 * #表示墙 0 石头 @玩家的开始位置
 * @type {{boulders: number, field: string[]}}
 */
var level = {
    boulders: 10,
    field: [
        "######  ##### ",
        "#    #  #   # ",
        "# 0  #### 0 # ",
        "# 0 @    0  # ",
        "#  #######0 # ",
        "####  #### ###",
        "      #      #",
        "      #0     #",
        "      # 0    #",
        "     ## 0    #",
        "     #*0 0   #",
        "     #########",
    ],
};

function Square(character, img) {
    this.img = img;
    var content = {"@": "player", "#": "wall", "*": "exit",
        " ": "empty", "0": "boulder"}[character];

    if (content == null) {
        throw new Error("未识别的标识符");
    }
    this.setContent(content);
}

Square.prototype.setContent=function(content) {
    this.content = content;
    this.img.src="img/sokoban/" + content + ".jpg";
}

function SokobanField(level) {
    this.fieldDiv = dom("DIV");
    this.squares = [];
    this.bouldersToGo = level.boulders;

    for(var y=0; y<level.field.length; y++) {
        var line = level.field[y], squareRow = [];

        for(var x=0; x<line.length;x++) {
            var img = dom(IMG);
            this.fieldDiv.appendChild(img);
            squareRow.push(new Square(line.charAt(x), img));

            if (line.chatAt(x) == "@") {
                this.playerPos = new Point(x, y);
            }
            this.fieldDiv.appendChild(dom("BR"));
            this.squares.push(squareRow);
        }
    }
}

SokobanField.prototype.status = function() {
    return this.bouldersToGo + " boulder" +
        (this.bouldersToGo == 1 ? "" : "s") + " to go.";
};

SokobanField.prototype.won = function(){
    return this.bouldersToGo <=0;
};

SokobanField.prototype.place = function(where) {
    where.appendChild(this.fieldDiv);
};

SokobanField.prototype.remove = function() {
    this.fieldDiv.parentNode.remove(this.fieldDiv);
};


SokobanField.prototype.move=function(direction) {
  var playerSquare=this.squares[this.playerPos.y][this.playerPos.x],
    targetPos = this.playerPos.add(direction),
    targetSqaure = this.squares[targetPos.y][targetPos.x];

  //先检查能否推动 boulder
  if (targetSqures.content == "boulder") {
      var pushPos = targetPos.add(direction),
          pushSquare = this.squares[pushPos.y][pushPos.x];

      if (pushSquare.content == "empty") {
          targetSqaure.setContent("empty");
          pushSqure.setContent("boulder");
      } else if (pushSquare.content == "exit") {
          targetSqaure.setContent("empty");
          this.bouldersToGo--;
      }
  }

    // 试着去推动它
    if (targetSqaure.content == "empty") {
        playerSquare.setContent("empty");
        targetSqaure.setContent("player");
        this.playerPos = targetPos;
    }
};

(new SokobanField(level)).place(document.body);

// 控制器对象
/*
 1. 准备放置游戏板的位置
 2. 创建和删除SokobanField对象
 3. 捕获键盘事件，并在当前游戏版上使用正确的参数调用move方法
 4. 持续跟踪当前的级别，过关后移动到下一个等级
 5. 添加按钮来重置当前等级或者整个游戏(返回0级别）
*/

function SokobanGame(levels, place) {
    this.levels = levels;
    var  newGame=dom("BUTTON", null, "新游戏");

    addHandler(newGame, "click", method(this, "newGame"));
    var reset = dom("BUTTON", null, "重置等级");
    addHandler(reset, "click", method(this, "resetLevel"));
    this.status=dom("DIV");

    this.container = dom("DIV", null, dom("H1", null, "Sokoban"),
        dom("DIV", null, newGame, " ", reset), this.status);

    place.appendChild(this.container);
    addHandler(document, "keydown", method(this, "keyDown"));
    this.newGame();
}

SokobanGame.prototype.newGame = function() {
    this.level = 0;
    this.resetLevel();
};

SokobanGame.prototype.resetLevel = function(){
  if(this.field)
      this.field.remove();

  this.field = new SokobanField(this.levels[this.level]);
  this.field.place(this.container);
  this.updateStatus();
};

SokobanGame.prototype.updateStatus = function() {
    this.status.innerHtml = "Level" + (1 + this.level) + ": " +
        this.field.status();
};

var arrowKeyCodes = {
    37: new Point(-1, 0),   //left
    38: new Point(0, -1),   //up
    39: new Point(1, 0),    //right
    40: new Point(0, 1)     //down
};

SokobanGame.prototype.keyDown=function(event) {
    if(arrowKeyCodes.hasOwnProperty(event.keyCode)){
        event.stop();
        this.field.move(arrowKeyCodes[event.keyCode]);
        this.updateStatus();
        if (this.field.won()) {
            if (this.level < this.levels.length - 1) {
                alert("恭喜！下一关...");
                this.level++;
                this.resetLevel();
            }
        } else {
            alert("你赢了！游戏结束");
            this.newGame();
        }
    }
};

/*xmlHttpRequest 异步请求对象*/
function requestObject() {
    if (window.XMLHttpRequest) {
        return new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        return new ActiveXObject("Msxml2.XMLHTTP");
    } else {
        throw new Error("无法创建http请求");
    }
}

var request = requestObject();
request.open("GET", "files/data.txt", false);   //第三个参数表示是否异步 false:同步， true:异步
request.send(null);

request.responseText;
request.getResponseHeader("Content-Type");
request.getAllResponseHeaders();    //所有响应头
request.status;  //200
request.stausText;  //OK


request.open("GET", "files/data.txt", true);
request.onreadystatechange = function(){
    if(request.readyState == 4) {
        console.log(request.status + " " + request.statusText);
    }
};

request.send(null);

//返回json处理
request.open("GET", "files/fruit.json", true);
request.onreadystatechange = function(){
    if (request.readyState == 4) {
        // 使用 eval()，同时用()包裹住，因为 {}会被当做代码块的开始
        var data = eval("(" + request.responseText + ")");
        console.log(data);
    }
}


//基本封装
function simpleHttpRequest(url, success, failure) {
    var request = requestObject();
    request.open("GET", url, true);
    request.onreadystatechange = function() {
        if (request.readyState == 4) {
            if (request.status == 200 || !failure) {
                success(request.responseText);
            } else if (failure) {
                failure(request.status, request.statusText);
            }
        }
    };
    request.send(null);
}

