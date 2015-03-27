<?php

/*
 * This file is part of the colinodell\omnipay-bundle package.
 *
 * (c) 2015 Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ColinODell\OmnipayBundle\Tests\Service;


use ColinODell\OmnipayBundle\Service\Omnipay;

class FakeOmnipay extends Omnipay
{
    protected $fakeClient;

    public function setFakeClient($client)
    {
        $this->fakeClient = $client;
    }

    protected function createClient()
    {
        return $this->fakeClient;
    }
}