<?php

/*
 * This file is part of the colinodell\omnipay-bundle package.
 *
 * (c) 2018 Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ColinODell\OmnipayBundle\Tests;

use ColinODell\OmnipayBundle\DependencyInjection\Compiler\GatewayTagCompilerPass;
use ColinODell\OmnipayBundle\OmnipayBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OmnipayBundleTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $bundle = new OmnipayBundle();
    }

    public function testBuild()
    {
        $container = new ContainerBuilder();

        $bundle = new OmnipayBundle();
        $bundle->build($container);

        $matchingPasses = [];
        foreach ($container->getCompilerPassConfig()->getPasses() as $compilerPass) {
            if ($compilerPass instanceof GatewayTagCompilerPass) {
                $matchingPasses[] = $compilerPass;
            }
        }

        $this->assertNotEmpty($matchingPasses);
    }
}
