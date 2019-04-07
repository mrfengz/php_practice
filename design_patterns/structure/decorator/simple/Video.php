<?php
class Video extends Decorator
{
    public function __construct(IComponent $siteNow)
    {
        $this->site = $siteNow;
    }

    public function getSite()
    {
        // TODO: Implement getSite() method.
        $fmat = "<br>;&nbsp;&nbsp; Video ";
        return $this->site->getSite() . $fmat;
    }

    public function getPrice()
    {
        // TODO: Implement getPrice() method.
        return $this->site->getPrice() + 350;
    }
}