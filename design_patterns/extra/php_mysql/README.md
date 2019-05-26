Client----------   Subject
                   request()
                      |
                      |
RealSubject -------  Proxy
request()           request() ---- realSubject->Request()


Client ------------ Subject
                     request()
                     |
                     |
                     |
RealSubject <----- Proxy
                    Request()
                    |   |
                    |   |
                    |   |
                    |   |
                  高安全性模块                    