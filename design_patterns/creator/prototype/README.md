## 先看uml图
    
    ![原型](https://github.com/mrfengz/php_practice/blob/master/design_patterns/creator/prototype/prototype_uml.jpg)
- 实现原理：利用php的魔术方法__clone()实现。但是注意新创建的实例副本不会调用构造方法。
    
- 使用场景：创建某个原型对象的多个实例时，就可以使用原型模式。(比如研究果蝇雌雄变异，仅需要雌雄两个原型作为基础，变异则是克隆体)

- 示例说明
    有两个原型，雌雄果蝇。研究其关键属性eysColor,wingBeat,eyeUnit的变异情况.
    雄蝇还有个mated属性表示是否交配，雌蝇有个属性fecudity表示产卵数量。
    
    fly1/fly2进行交配，然后生成(克隆)c1Fly和c2Fly,这两个又生成(克隆)了updatedCloneFly.
    
- demo2的uml图
    抽象部门--market、management、engineer--克隆
    ![部门原型](https://github.com/mrfengz/php_practice/blob/master/design_patterns/creator/prototype/uml_demo2.jpg)