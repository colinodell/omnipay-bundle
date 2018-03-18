<?php

/*
 * This file is part of the colinodell\omnipay-bundle package.
 *
 * (c) 2018 Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$container->loadFromExtension('omnipay', [
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
    'initialize_gateway_on_registration' => true,
]);
