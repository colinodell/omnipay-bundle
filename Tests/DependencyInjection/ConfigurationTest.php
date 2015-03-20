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

use ColinODell\OmnipayBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testEmptyConfig()
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), []);

        $this->assertArrayHasKey('methods', $config);
        $this->assertEmpty($config['methods']);
    }

    public function testMethodConfig()
    {
        $sampleConfig = self::getSampleMethodConfig();

        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), [$sampleConfig]);

        $this->assertEquals($sampleConfig, $config);
    }

    protected static function getSampleMethodConfig()
    {
        return [
            'methods' => [
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
            ],
        ];
    }
}
