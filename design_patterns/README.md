## PHP设计模式
    为什么采用面向对象编程
        1. 解决问题更容易。将大问题分解为小问题。
        2. 模块化。
        3. 加快开发和修改速度。
        
## 相关概念
    1. 类与对象
    
        类：属性和方法
    
    2. 单一职责
    
    3. 签名
        
        操作名(参数)--> 签名
    
    3. 抽象： 接口和抽象类
        
        抽象：指示一个对象的基本特征，与其他对象进行区分，从查看者的角度提供了清晰定义的概念边界
        
        抽象类： 
            1. 不能实例化
            2. 必须由具体类继承抽象类的接口
            3. 可以包含具体方法
            4. 可以包含属性
            
        接口：
            1. 不能包含属性，但可以包含常量
            2. 不能包含具体的方法(只有签名，无具体实现)

        * 类型提示：   一般使用接口(接口和抽象类) 作为类型提示。
        
    4. 特性
        1. 封装
            可见性控制
        2. 继承
            使用继承来获取某个类的属性和方法，同时可以进行自己的扩展。
        3. 多态
            多种形态。调用有相同接口的对象，完成不同的工作。
            比如动物鸣叫的方法，dog类实现为'汪汪',猫为'喵喵'。    
    
    5. MVC
        视图--控制器--模型
        
        展示了松耦合，分离不同的元素来完成一个任务，提供了更大的灵活性。

## 设计模式的原则

    1. 按接口而不是按照实现来编程。
        将变量设置为一个抽象类或者接口数据类型的实例，而不是一个具体实现的实例。
       代码提示中使用接口(即签名)数据类型(只要实现了该接口，其输出都可以预测)            
    2. 应当优先选择组合而不是类继承。                           
        继承： IS-A
        组合：HAS-A
        
        使用浅继承，避免修改父类影响到所有多层级的子类，导致未预期的错误。
        使用委托(组合)可以有助于避免紧密绑定

## 设计模式组织分类
   
    1. 创建型
        创建对象的模式。提供一些方法封装系统使用的具体类的有关知识，还可以隐藏实例创建和组合的相关信息。
    2. 结构型
        组合结构应当保证结构化。通过继承、组合对象建立新功能
    3. 行为型             
        核心是算法和对象之间的职责分配。  
    4. 类模式
        重点在于类和子类之间的关系。通过继承建立，是静态的，编译时已经固定。
    5. 对象模式
        对象模式强调的是可以在运行时可以改变的对象，更具有动态性。
        
    * 设计模式是编程中反复出现的常见问题的解决方案，但是也不是一成不变的。
    * 选择世界模式时需要考虑什么导致了重新设计？什么会变化(重点转化为封装那些变化的概念)？   
      