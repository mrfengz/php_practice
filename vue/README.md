#### 基础知识
> 基本语法

    var app = new Vue({
        el: "#app",
        data: {
            seen: false,
            todos: [
                {text: "学习Vue"},
                {text: "学习Go"},
                {text: "学习Swoole"},
            ],
            messsage: "Hello, Programer!",
        },
        methods: {
            reverseMessage: function(){
                this.message = this.message.split("").reverse().join("")
            }
        }
    })
    
    1. v-bind: title = "message"        # 属性值绑定
    2. v-on: click="reverseMessage"     # 绑定方法
    3. v-for="todo in todos"    {{todo.text}}   # 遍历
    4. v-if="seen"                      # 条件控制
    5. v-model="message"                # 表单双向绑定 
    6. v-html="html"                    # 输出原生html
    7. v-bind:[dynamic_key]="url"       # dynamic_key 是一个变量
    8. 缩写
        v-bind:href => :href
        v-bind:[dynamic_key] => :[dynamic_key]

        v-on:click => @click
        v-on:[dynamic_key] => @[event]    
> 组件化应用构建
    
    1. 定义： 
        Vue.component('todo-item', {
            //todo-item 组件现在接受一个 "prop", 类似于一个自定义的 attribute
            // 这个prop名字为 todo
            props: ["todo"],
            template: '<li>{{todo.text}}</li>'  
        })
        
        var app7 = new Vue({
            el: '#app7',
            data: {
                groceryList: [
                    {id: 0, text: '蔬菜'},
                    {id: 1, text: '奶酪'},
                    {id: 2, text: 'whatever'},
                ]
            }
        });
        
        <div id="app7">
            <ol>
                <todo-item
                    v-for="item in groceryList"
                    v-bind:todo="item"  #todo变量绑定item
                    v-bind:key="item.id"
                >
                </todo-item>
            </ol>
        </div>
        
    2. 组件的理解
        <div id="app">
            <app-nav></app-nav>
            <app-view>
                <app-sidebar></app-sidebar>
                <app-content></app-content>
            </app-view>
        </div>    
          
          
> 语法知识

    var data = {a: 1};
    var vw= new Vue({data: data}) 
    
    1. Vue实例的data属性可以执行通过 vw.属性名 访问, vw.a => 1
    2. vw.$data === data 
       vw.$el
       vm.$watch('a',function(newValue, oldValue){
            //'vm.a'值改变后会被调用
       })         
    3. vm.a修改时，会自动更新。vm.b修改，不会更新绑定的数据（初始化时没有值）
        Object.freeze(data)后，则修改vm.a后，不会响应式更新数据
    4. 不要在选项属性或者回调上使用 箭头函数，因为这货没有 this，会作为变量一直向上找，经常导致异常。
    5. 支持简单的javascript表达式，**仅支持单个表达式** 如 {{ ok ? "Yes" : "No" }}, 不支持 {{ if (ok) {return message;}  }}
    6. 动态键名
    7. 键名不要用大写
    8. 计算属性和侦听器
        computed：计算属性 
            计算属性会缓存计算结构，适用于复杂计算的
            方法适用于不依赖与动态属性的，需要实时计算的
            computed: {
                reverseMessage: function(){
                    return this.message.split("").reverse().join("");   //会缓存结果，当依赖的message值不发生变化时，返回的结果是缓存的结果
                }
            }
            
            methods: {
                now: function(){
                    return Date.now();  //如果写在computed中，因为这货不依赖变量，所以返回的结果一直不变
                }
            }
            
            设置setter
            computed: {
                fullName: {
                    //getter
                    get: function(){
                        return this.firstName + ' ' + this.lastName;
                    },
                    //setter
                    set: function(newValue) {
                        var names = newValue.split(' ');
                        this.firstName = names[0];
                        this.lastName = names[names.length - 1]
                    }
                }
            }
        
        watch: 侦听器
            当数据变化时，需要执行异步或者较大开销时，这个方式比较有用。
            watch: {
                firstName: function(val){
                    return val + ' ' + this.lastName;
                },
                lastName: function(val) {
                    return this.firstName + ' ' + val; 
                }
            }
- 条件渲染
    
    v-if / v-if v-else
    <div v-if="awesome">Vue is 牛</div>            
    <div v-else>Vue is 太牛了</div>
    
    v-else-if
    <div v-if="type === 'A'">A</div>
    <div v-else-if="type === 'B'">B</div>
    <div v-else-if="type === 'C'">C</div>
    <div v-else>Unknown</div>
    
    v-show,效果与v-if差不多，不过这个是控制是否显示，通过display属性作用的
        不支持与 template 连用，也不支持 v-else
    v-if是每次重新渲染的，它会尽可能复用已有元素，不想复用的话，可以使用key
        <div v-if="loginType === 'username'">
            <label>username</label>
            <input placeholder="please enter username">
        </div>
        <div v-else>
            <label>Email</label>
            <input placeholder="please enter email">
        </div>
        给两个input不同的key，比如key="username" 和 key="email"， 不会复用input元素

    Tips: 如果想同时控制多个元素的显隐，可以使用 <template v-if></template>
    
- 循环
    
    数组
    
        v-for="item in items"
        v-for="{item, index} in items"
    
    对象
        
        v-for="{item,key,index} in items"
        
    为了让渲染的元素保持顺序，最好给每项一个 key
    <template v-for="item in items" :key="item.id"></template>    
- 事件

    v-on:click="counter"
    
    var vm = new Vue({
        el: '#app',
        data: {
            _counter: 0
        }
        methods: {
            counter: function(){
                return this._counter++;
            }
        }
    })    
    
    事件修饰符(可以连缀写)
    * .stop     不向上冒泡
    * .prevent  阻止默认行为
    * .capture  触发事件的元素，自己首先执行的时间
    * .self     触发事件的元素必须是自己
    * .once 
    * .passive 等待事件完成后，比如滚动结束后才会触发
    
    按键修饰符 可以使用KeyboardEvent.key作为修饰符
    * v-on:keyup.enter="submit" 点击enter时调用vue.submit()方法
    * v-on:keyup.page-down="onPageDown" 
    
    按键码 keyCode（已经被废弃了）
    - v-on.keyup.13="submit"
    - .enter    
    - .tab    
    - .delete    
    - .esc   
    - .space    
    - .up    
    - .down    
    - .left    
    - .right
    Tips: 可以通过config.keyCodes全局配置，自定义按键修饰器别名
    
    系统修饰符(可以组合)
    - .ctrl
    - .alt
    - .shift
    - .meta    
    v-on:click..ctrl # click+ctrl键
    
    .exact 精确控制时间
    
    鼠标修饰符
    - .left
    - .middle
    - .right
    
- 表单输入绑定
    
    v-model 可以在textarea,input,select上创建双向数据绑定

    **Tips**:会忽略value/checked/selected的初始值，应该使用data属性声明初始值 
        new Vue({
            data: {
                a: 1
            }
        })
        
    修饰符
    - .number
    - .trim
    - .lazy    
    
- 组件基础
```    
    Vue.component('button-counter', {
        //必须为函数，不能为对象，这样每个组件返回的就是一个对象的copy，而不是引用
        data: function(){
            return {
                counter: 0;
            }
        },
        // props里边的值，可以在template中使用，在html代码中，通过v-bind将值赋给prop，可以传为对象
        props: ['title'],
        template: '<button v-on:click="counter++">你一共点击了 {{ counter }} 次</button>'
    })    
```    
    **Tips**: 只能有一个根元素，可以用一个根元素把其他元素包裹起来
    
    监听子组件事件
```        
<blog-post
          ...
          v-on:enlarge-text="postFontSize += 0.1"
        ></blog-post>
        
        <button v-on:click="$.emit('enlarge-text')">放大字体</button>
        
        或者在vue的methods属性中添加一个方法修改字体
            onEnlargeText: function(enlargeAmount){
                this.postFontSize += enlargeAmount;
            }
        
        传递参数 $event接受
            <blog-post
              ...
              v-on:enlarge-text="postFontSize += $event"
            ></blog-post>
            <button v-on:click="$.emit('enlarge-text', 0.1)">放大字体</button>
 ```           
   在自定义组件中使用v-model
   ```vue
    <input v-model="searchText">
    等价于
    <input
      v-bind:value="searchText"
      v-on:input="searchText = $event.target.value"
    >
    
    自定义组件相当于这样的：
    <custom-input
      v-bind:value="searchText"
      v-on:input="searchText = $event"
    ></custom-input>
    
    定义一个组件
    Vue.component('custom-input', {
      props: ['value'],
      template: `
        <input
          v-bind:value="value"
          v-on:input="$emit('input', $event.target.value)"
        >
      `
    })
    
    使用：
    <custom-input v-model="searchText"></custom-input>
        
```         
    通过插槽分发内容    todo
    动态组件            todo
            
> class与style绑定
   
    var classObject = {
        active: true,
        'text-danger': false
    }
    
    # 对象语法
    v-bind:class="{'active': isActive}"    
    v-bind:class="{'active': isActive, 'text-danger': hasError}"    
    v-bind:class="{classObject}"    
    
    # 数组语法
    v-bind:class="[errorClass, activeClass]"
    v-bind:class="[errorClass, isActive ? activeClass : '']"
    
    # style
    v-bind:style="{ color: activeColor, fontSize: fontSize + 'px' }"
    
> 生命周期

    ![生命周期图](./images/vue_lifetime.png)      