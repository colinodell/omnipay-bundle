<?php

/*
 * This file is part of the colinodell\omnipay-bundle package.
 *
 * (c) 2015 Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ColinODell\OmnipayBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class OmnipayExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $methods = $config['methods'];
        $methodNames = array_keys($methods);

        // Add configuration to the Omnipay service
        $omnipay = $container->getDefinition('omnipay');
        $omnipay->addArgument($methods);

        if ($disabledGateways = $config['disabled_gateways']) {
            $omnipay->addMethodCall('setDisabledGateways', [$disabledGateways]);
        }

        if ($defaultGateway = $config['default_gateway']) {
            $allowedValues = array_diff($methodNames, $disabledGateways);

            if (!in_array($defaultGateway, $methodNames)) {
                throw new InvalidConfigurationException(sprintf(
                    'You cannot specify non-existing gateway (%s) as default. Allowed values: %s',
                    $defaultGateway,
                    implode(', ', $allowedValues)
                ));
            }

            if (in_array($defaultGateway, $disabledGateways)) {
                throw new InvalidConfigurationException(sprintf(
                    'You cannot specify disabled gateway (%s) as default. Allowed values: %s',
                    $defaultGateway,
                    implode(', ', $allowedValues)
                ));
            }

            $omnipay->addMethodCall('setDefaultGatewayName', [$defaultGateway]);
        }

        if ($initializeOnRegistration = $config['initialize_gateway_on_registration']) {
            $omnipay->addMethodCall('initializeOnRegistration', [$initializeOnRegistration]);
        }
    }
}
