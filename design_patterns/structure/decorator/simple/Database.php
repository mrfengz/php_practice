<?php
class Database extends Decorator
{
    public function __construct(IComponent $siteNow)
    {
        $this->site = $siteNow;
    }

    public function getSite()
    {
        // TODO: Implement getSite() method.
        $fmat = "<br>&nbps;&nbps; Database ";
        return $this->site->getSite() . $fmat;
    }

    public function getPrice()
    {
        // TODO: Implement getPrice() method.
        return 800 + $this->site->getPrice();
    }

}