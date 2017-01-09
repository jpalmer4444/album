<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ApplicationTest\Utility;

use Application\Utility\DateUtils;
use Application\Utility\Logger;
use PHPUnit\Framework\TestCase;

/**
 * Description of DateUtilsTest
 *
 * @author jasonpalmer
 */
class DateUtilsTest extends TestCase{
    
    public function testGetDailyCutoff() {
        Logger::stderr(DateUtilsTest::class, __LINE__, "Testing DateUtils::getDailyCutoff()");
        $cutoff = DateUtils::getDailyCutoff();
        $this->assertNotNull($cutoff, "DateUtils::getDailyCutoff returned null!");
        //Logger::stderr(DateUtilsTest::class, __LINE__, "Cutoff DIFF " . $cutoff->diff(new DateTime())->format("%d days %h hours %i minutes %s seconds"));
    }
    
}
