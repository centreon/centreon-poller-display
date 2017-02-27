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
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\NagiosServer;

/**
 * @package centreon-poller-display-central
 * @version 1.0.0
 * @author Centreon
 */
class CentreonPollerDisplayCentral_NagiosServer extends PHPUnit_Framework_TestCase
{
    protected static $db;
    protected static $pollerDisplay;
    protected static $nagiosServer;

    public function setUp()
    {
        self::$db = new CentreonDB();
        self::$pollerDisplay = 1;
        self::$nagiosServer = new NagiosServer(self::$db, self::$pollerDisplay);
    }

    public function tearDown()
    {
        self::$db = null;
    }

    public function testGenerateSql()
    {

        $expectedResult = 'TRUNCATE nagios_server;
INSERT INTO nagios_server (id,name) VALUES (1,central);';

        self::$db->addResultSet(
            'SELECT * FROM nagios_server WHERE id = 1',
            array(
                array(
                    'id' => '1',
                    'name' => 'central'
                )
            )
        );

        $sql = self::$nagiosServer->generateSql();
        $this->assertEquals($sql, $expectedResult);
    }
}
