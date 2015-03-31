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
use ColinODell\OmnipayBundle\Tests\FakeGateway;
use Omnipay\Common\GatewayFactory;
use Omnipay\PayPal\ProGateway;

class OmnipayTest extends \PHPUnit_Framework_TestCase
{
    public function testGetUnconfiguredGateway()
    {
        $omnipay = $this->createOmnipay();

        $gateway = $omnipay->get('PayPal_Pro');

        $this->assertTrue($gateway instanceof ProGateway);

        $this->assertEquals($gateway->getDefaultParameters(), $gateway->getParameters());
    }

    public function testGetConfiguredGateway()
    {
        $config = [
            'username' => 'test-facilitator_api1.example.com',
            'password' => '3MPI3VB4NVQ3XSVF',
            'signature' => '6fB0XmM3ODhbVdfev2hUXL2x7QWxXlb1dERTKhtWaABmpiCK1wtfcWd.',
            'testMode' => false,
        ];

        $omnipay = $this->createOmnipay(['PayPal_Pro' => $config]);

        $gateway = $omnipay->get('PayPal_Pro');

        $this->assertTrue($gateway instanceof ProGateway);
        $this->assertEquals($config, $gateway->getParameters());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetNonExistantGateway()
    {
        $omnipay = $this->createOmnipay();

        $gateway = $omnipay->get('sadfhjasfswef');
    }

    public function testGetCachedGateway()
    {
        $omnipay = $this->createOmnipay();

        $gateway1 = $omnipay->get('PayPal_Pro');
        $gateway2 = $omnipay->get('PayPal_Pro');

        $this->assertTrue($gateway1 === $gateway2);
    }

    public function testRegisterGateway()
    {
        $omnipay = $this->createOmnipay();

        $fakeGateway = new FakeGateway();

        $omnipay->registerGateway($fakeGateway);
        $actual = $omnipay->get('\ColinODell\OmnipayBundle\Tests\FakeGateway');

        $this->assertSame($fakeGateway, $actual);
    }

    /**
     * @param array $config
     *
     * @return Omnipay
     */
    protected function createOmnipay(array $config = [])
    {
        $factory = new GatewayFactory();

        return new Omnipay($factory, $config);
    }
}
