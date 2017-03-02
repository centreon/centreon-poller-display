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
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\ServiceInformation;


/**
 * @package centreon-poller-display-central
 * @version 1.0.0
 * @author Centreon
 */
class CentreonPollerDisplayCentral_ServiceInformation extends PHPUnit_Framework_TestCase
{
    protected static $db;
    protected static $pollerDisplay;
    protected static $serviceInformation;
    protected static $objectListIn;
    protected static $objectListOut;

    public function setUp()
    {
        self::$db = new CentreonDB();
        self::$pollerDisplay = 1;
        self::$serviceInformation = new ServiceInformation(self::$db, self::$pollerDisplay);
        self::$objectListIn = array(
            array(
                'hsr_id' => '1',
                'hostgroup_hg_id' => null,
                'host_host_id' => '1',
                'servicegroup_sg_id' => null,
                'service_service_id' => '1'
            )
        );
        self::$objectListOut = array(
            array(
                'esi_id' => '1',
                'service_service_id' => '1'
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
            'SELECT * FROM extended_service_information WHERE service_service_id IN (1)',
            array(
                array(
                    'esi_id' => '1',
                    'service_service_id' => '1'
                )
            )
        );

        $sql = self::$serviceInformation->getList(self::$objectListIn);
        $this->assertEquals($sql, self::$objectListOut);
    }

    public function testGenerateSql()
    {
        $expectedResult = 'DELETE FROM extended_service_information;
TRUNCATE extended_service_information;
INSERT INTO `extended_service_information` (`esi_id`,`service_service_id`) VALUES (\'1\',\'1\');';

        $sql = self::$serviceInformation->generateSql(self::$objectListOut);
        $this->assertEquals($sql, $expectedResult);
    }
}
