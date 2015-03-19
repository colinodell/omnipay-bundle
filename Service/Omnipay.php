<?php

/*
 * This file is part of the colinodell\omnipay-bundle package.
 *
 * (c) 2015 Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ColinODell\OmnipayBundle\Service;

use Guzzle\Http\Client;
use Omnipay\Common\GatewayFactory;
use Omnipay\Common\GatewayInterface;

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

    protected function createGateway($gatewayName)
    {
        $httpClient = new Client();

        /** @var GatewayInterface $gateway */
        $gateway = $this->gatewayFactory->create($gatewayName, $httpClient);

        $config = isset($this->config[$gatewayName]) ? $this->config[$gatewayName] : [];

        $gateway->initialize($config);

        return $gateway;
    }
}
