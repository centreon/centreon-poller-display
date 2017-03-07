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
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\NagiosCfg;

/**
 * @package centreon-poller-display-central
 * @version 1.0.0
 * @author Centreon
 */
class CentreonPollerDisplayCentral_NagiosCfg extends PHPUnit_Framework_TestCase
{
    protected static $db;
    protected static $pollerDisplay;
    protected static $nagiosCfg;
    protected static $objectList;

    public function setUp()
    {
        self::$db = new CentreonDB();
        self::$pollerDisplay = 1;
        self::$nagiosCfg = new NagiosCfg(self::$db, self::$pollerDisplay);
        self::$objectList = array(
            array(
                'nagios_id' => '1',
                'nagios_name' => 'Centreon Engine',
                'nagios_server_id' => '1'
            )
        );
    }

    public function tearDown()
    {
        self::$db = null;
    }

    public function testGetList()
    {
        self::$db->addResultSet(
            'SELECT * FROM cfg_nagios WHERE nagios_server_id = 1',
            array(
                array(
                    'nagios_id' => '1',
                    'nagios_name' => 'Centreon Engine',
                    'nagios_server_id' => '1'
                )
            )
        );

        $sql = self::$nagiosCfg->getList();
        $this->assertEquals($sql, self::$objectList);
    }

    public function testGenerateSql()
    {

        $expectedResult = 'DELETE FROM cfg_nagios;
TRUNCATE cfg_nagios;
INSERT INTO `cfg_nagios` (`nagios_id`,`nagios_name`,`nagios_server_id`) VALUES (\'1\',\'Centreon Engine\',\'1\');';

        $sql = self::$nagiosCfg->generateSql(self::$objectList);
        $this->assertEquals($sql, $expectedResult);
    }
}
