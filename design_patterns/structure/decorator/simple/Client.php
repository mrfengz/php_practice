<?php
function __autoload($class)
{
    include $class . '.php';
}

class Client
{
    private $basicSite;

    public function __construct()
    {
        $this->basicSite = new BasicSite();
        $this->basicSite = $this->wrapComponent($this->basicSite);

        $siteShow = $this->basicSite->getSite();
        $format = "<br>&nbsp;&nbsp;<strong>Total = $";
        $price = $this->basicSite->getPrice();

        echo $siteShow . $format . $price . '</strong>';
    }

    /**
     * 包装器
     * @param IComponent $component
     * @return Database|IComponent|Maintance|Video
     */
    private function wrapComponent(IComponent $component)
    {
        $component = new Maintance($component);
        $component = new Video($component);
        $component = new Database($component);

        return $component;
    }
}

$worker = new Client();