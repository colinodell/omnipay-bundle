<?php

/*
 * This file is part of the colinodell\omnipay-bundle package.
 *
 * (c) 2018 Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ColinODell\OmnipayBundle\Service;

use Guzzle\Http\Client;
use Omnipay\Common\GatewayFactory;
use Omnipay\Common\GatewayInterface;
use Omnipay\Common\Helper;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class Omnipay
{
    /**
     * @var GatewayFactory
     */
    protected $gatewayFactory;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var GatewayInterface[]
     */
    protected $cache;

    /**
     * @var GatewayInterface[]
     */
    protected $registeredGateways = [];

    /**
     * @var string[]
     */
    protected $disabledGateways = [];

    /**
     * @var string
     */
    protected $defaultGatewayName;

    /**
     * @var bool
     */
    protected $initializeOnRegistration = false;

    /**
     * @param GatewayFactory $gatewayFactory
     * @param array          $config
     */
    public function __construct(GatewayFactory $gatewayFactory, array $config = array())
    {
        $this->gatewayFactory = $gatewayFactory;
        $this->config = $config;
    }

    /**
     * @param string $gatewayName
     *
     * @return GatewayInterface
     */
    public function get($gatewayName)
    {
        if (!isset($this->cache[$gatewayName])) {
            $gateway = $this->createGateway($gatewayName);
            $this->cache[$gatewayName] = $gateway;
        }

        return $this->cache[$gatewayName];
    }

    /**
     * @param GatewayInterface $gatewayInstance
     * @param string|null      $alias
     */
    public function registerGateway(GatewayInterface $gatewayInstance, $alias = null)
    {
        $name = $alias ?: Helper::getGatewayShortName(get_class($gatewayInstance));

        if (in_array($name, $this->disabledGateways)) {
            return;
        }

        $this->registeredGateways[$name] = $gatewayInstance;

        if ($this->initializeOnRegistration) {
            $gatewayInstance->initialize($this->getGatewayConfig($name));
            $this->cache[$name] = $gatewayInstance;
        }
    }

    /**
     * @param string[] $disabledGateways
     */
    public function setDisabledGateways(array $disabledGateways)
    {
        $this->disabledGateways = $disabledGateways;
    }

    /**
     * @return GatewayInterface
     */
    public function getDefaultGateway()
    {
        if (null === $this->defaultGatewayName) {
            throw new InvalidConfigurationException('Default gateway is not configured');
        }

        return $this->get($this->defaultGatewayName);
    }

    /**
     * @param string $defaultGatewayName
     */
    public function setDefaultGatewayName($defaultGatewayName)
    {
        $this->defaultGatewayName = $defaultGatewayName;
    }

    /**
     * @param boolean $initializeOnRegistration
     */
    public function initializeOnRegistration($initializeOnRegistration)
    {
        $this->initializeOnRegistration = $initializeOnRegistration;
    }

    /**
     * @param string $gatewayName
     * @return GatewayInterface
     */
    protected function createGateway($gatewayName)
    {
        $httpClient = new Client();

        if (isset($this->registeredGateways[$gatewayName])) {
            $gateway = $this->registeredGateways[$gatewayName];
        } else {
            /** @var GatewayInterface $gateway */
            $gateway = $this->gatewayFactory->create($gatewayName, $httpClient);
        }

        $gateway->initialize($this->getGatewayConfig($gatewayName));

        return $gateway;
    }

    /**
     * @param string $gatewayName
     * @return array
     */
    protected function getGatewayConfig($gatewayName)
    {
        return isset($this->config[$gatewayName]) ? $this->config[$gatewayName] : [];
    }
}
