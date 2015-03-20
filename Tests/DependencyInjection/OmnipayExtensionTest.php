<?php

/*
 * This file is part of the colinodell\omnipay-bundle package.
 *
 * (c) 2015 Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ColinODell\OmnipayBundle\Tests\DependencyInjection;

use ColinODell\OmnipayBundle\DependencyInjection\OmnipayExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

abstract class OmnipayExtensionTest extends \PHPUnit_Framework_TestCase
{
    abstract protected function loadFromFile(ContainerBuilder $container, $file);

    public function testDefaultOmnipayService()
    {
        $container = $this->createContainerFromFile('default');

        $this->assertTrue($container->hasDefinition('omnipay'));

        $definition = $container->getDefinition('omnipay');

        $this->assertEquals('ColinODell\OmnipayBundle\Service\Omnipay', $definition->getClass());
    }

    public function testConfiguredOmnipayService()
    {
        $container = $this->createContainerFromFile('methods');

        $this->assertTrue($container->hasDefinition('omnipay'));

        $definition = $container->getDefinition('omnipay');

        $this->assertEquals('ColinODell\OmnipayBundle\Service\Omnipay', $definition->getClass());
        $this->assertEquals('Omnipay\Common\GatewayFactory', $definition->getArgument(0)->getClass());
        $this->assertEquals(self::getSampleMethodConfig(), $definition->getArgument(1));
    }

    protected static function getSampleMethodConfig()
    {
        return [
            'Stripe' => [
                'apiKey' => 'sk_test_BQokikJOvBiI2HlWgH4olfQ2',
            ],
            'PayPal_Express' => [
                'username' => 'test-facilitator_api1.example.com',
                'password' => '3MPI3VB4NVQ3XSVF',
                'signature' => '6fB0XmM3ODhbVdfev2hUXL2x7QWxXlb1dERTKhtWaABmpiCK1wtfcWd.',
                'testMode' => false,
                'solutionType' => 'Sole',
                'landingPage' => 'Login',
            ],
        ];
    }

    /**
     * @return ContainerBuilder
     */
    protected function createContainer()
    {
        $bundles = [
            'OmnipayBundle' => 'ColinODell\OmnipayBundle\OmnipayBundle',
        ];

        $container = new ContainerBuilder(new ParameterBag([
            'kernel.bundles'     => $bundles,
            'kernel.cache_dir'   => sys_get_temp_dir(),
            'kernel.debug'       => false,
            'kernel.environment' => 'test',
            'kernel.name'        => 'kernel',
            'kernel.root_dir'    => __DIR__,
        ]));

        return $container;
    }

    /**
     * @param string $file
     *
     * @return ContainerBuilder
     */
    protected function createContainerFromFile($file)
    {
        $container = $this->createContainer();

        $container->registerExtension(new OmnipayExtension());
        $this->loadFromFile($container, $file);

        $container->compile();

        return $container;
    }
}
