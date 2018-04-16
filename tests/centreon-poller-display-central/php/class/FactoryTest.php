<?php
/**
 * Copyright 2016 Centreon
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

use \Centreon\Test\Mock\CentreonDB;
use \CentreonPollerDisplayCentral\Factory;
use Centreon\Test\Mock\DependencyInjector\ServiceContainer;
use Centreon\Test\Mock\DependencyInjector\ConfigurationDBProvider;

/**
 * @package centreon-poller-display-central
 * @version 1.0.0
 * @author Centreon
 */
class CentreonPollerDisplayCentral_FactoryTest extends PHPUnit_Framework_TestCase
{
    protected static $container;
    protected static $db;
    protected static $dbProvider;
    protected static $factory;
    
    public function setUp()
    {
        self::$container = new ServiceContainer();
        self::$db = new CentreonDB();
        self::$dbProvider = new ConfigurationDBProvider(self::$db);
        self::$dbProvider->register(self::$container);
        self::$factory = new Factory(self::$container);
    }
    
    public function tearDown()
    {
        self::$dbProvider->terminate(self::$container);
        self::$container->terminate();
        self::$db = null;
    }
    
    public function testNewPollerDisplay()
    {
        $pollerDisplayObj = self::$factory->newPollerDisplay();
        $this->assertInstanceOf('\CentreonPollerDisplayCentral\PollerDisplay', $pollerDisplayObj);
    }
}
