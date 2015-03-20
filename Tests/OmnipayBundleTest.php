<?php

/*
 * This file is part of the colinodell\omnipay-bundle package.
 *
 * (c) 2015 Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ColinODell\OmnipayBundle\Tests;

use ColinODell\OmnipayBundle\OmnipayBundle;

class OmnipayBundleTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $bundle = new OmnipayBundle();
    }
}
