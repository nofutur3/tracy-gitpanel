<?php


class UnitCest
{
    public function _before(UnitTester $I)
    {
    }

    public function _after(UnitTester $I)
    {
    }

    // tests
    public function addPanelTest(UnitTester $I)
    {
        \Tracy\Debugger::getBar()
            ->addPanel(new \Nofutur3\GitPanel\Diagnostics\Panel(['production','staging']));
    }
}
