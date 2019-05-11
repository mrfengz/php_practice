<?php
class ZambeziCalc extends IHook
{
    protected function addTax()
    {
        $this->fullCost = $this->purchased + $this->purchased * 0.07;
    }

    protected function addShippingHook()
    {
        // TODO: Implement addShippingHook() method.
        if (!$this->hookSpecial) {
            $this->fullCost += 12.95;
        }
    }

    protected function displayCost()
    {
        // TODO: Implement displayCost() method.
        echo "Your full cost is $this->fullCost";
    }
}