<?php

/*
 * This file is part of the colinodell\omnipay-bundle package.
 *
 * (c) 2015 Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ColinODell\OmnipayBundle\Tests\DependencyInjection\Compiler;

use ColinODell\OmnipayBundle\DependencyInjection\Compiler\GatewayTagCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class GatewayTagCompilerPassTest extends \PHPUnit_Framework_TestCase
{
    public function testProcessDoesntFailWhenOmnipayUndefined()
    {
        $container = $this->createContainer(false);

        $this->assertFalse($container->hasDefinition('omnipay'));

        $pass = new GatewayTagCompilerPass();
        $pass->process($container);

        $this->assertTrue(true, 'Just making sure nothing blew up');
    }

    public function testProcess()
    {
        $container = $this->createContainer(true);

        $pass = new GatewayTagCompilerPass();
        $pass->process($container);

        $omnipayDefinition = $container->findDefinition('omnipay');

        $methodCalls = $this->getMethodCallsByName($omnipayDefinition, 'registerGateway');
        $this->assertCount(1, $methodCalls);

        list($reference) = reset($methodCalls);
        $this->assertReferenceEquals('test.gateway', $reference);
    }

    /**
     * @param bool $withOmnipay
     *
     * @return ContainerBuilder
     */
    protected function createContainer($withOmnipay)
    {
        $container = new ContainerBuilder();

        if ($withOmnipay) {
            $container->setDefinition('omnipay', new Definition('ColinODell\OmnipayBundle\Service\Omnipay'));
        }

        $gatewayDefinition = new Definition('My\Fake\Gateway');
        $gatewayDefinition->addTag('omnipay.gateway');

        $container->setDefinition('test.gateway', $gatewayDefinition);

        return $container;
    }

    /**
     * @param Definition $serviceDefinition
     * @param string     $methodName
     *
     * @return array
     */
    protected function getMethodCallsByName(Definition $serviceDefinition, $methodName)
    {
        $ret = [];
        foreach ($serviceDefinition->getMethodCalls() as $methodCall) {
            list($name, $args) = $methodCall;
            if ($name === $methodName) {
                $ret[] = $args;
            }
        }

        return $ret;
    }

    /**
     * @param string    $expectedId
     * @param Reference $actualReference
     */
    private function assertReferenceEquals($expectedId, Reference $actualReference)
    {
        $this->assertEquals($expectedId, $actualReference->__toString());
    }
}
