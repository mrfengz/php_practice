## 类关系图
    
-    每个工厂一个产品(*表示抽象方法)
    
          客户类-------> Creator(startFactory()|*factoryMethod())
                    |                            |      
                    |                            |  
                TextFactory                 GraphicFactory
                    |                            |
                    |           Product          | 
                    |       (*getProperities())  |     
                    |        |              |    |  
                    TextProduct          GraphicProduct                   
 
- 一个工厂多个产品     
    
        Client--------------->creator(doFactory(Product)|*factoryMethod)
           |                                    |
           |                                    |
           |                                    |                                                        
           |                                CountryFactory 
                                            |           |
        Product                             |           |
        |   |                               |           |
        |   |-----------AProduct <---------             |
        |---------------BProduct <----------------------
     
     client实例化工厂类CountryFactory,然后传入一个AProduct/BProduct对象，由工厂类调用IProduct子类的getProperties()方法