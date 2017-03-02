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
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\ContactServiceRelation;


/**
 * @package centreon-poller-display-central
 * @version 1.0.0
 * @author Centreon
 */
class CentreonPollerDisplayCentral_ContactServiceRelation extends PHPUnit_Framework_TestCase
{
    protected static $db;
    protected static $pollerDisplay;
    protected static $contact;
    protected static $objectListIn;
    protected static $objectListOut;

    public function setUp()
    {
        self::$db = new CentreonDB();
        self::$pollerDisplay = 1;
        self::$contact = new ContactServiceRelation(self::$db, self::$pollerDisplay);
        self::$objectListIn = array(
            array(
                'hsr_id' => '1',
                'hostgroup_hg_id' => null,
                'host_host_id' => '1',
                'servicegroup_sg_id' => null,
                'service_service_id' => '1'
            ),
            array(
                'hsr_id' => '2',
                'hostgroup_hg_id' => 20,
                'host_host_id' => null,
                'servicegroup_sg_id' => null,
                'service_service_id' => '5'
            )
        );
        self::$objectListOut = array(
            array(
                'csr_id' => '1',
                'service_service_id' => '10',
                'contact_id' => '1'
            ),
            array(
                'csr_id' => '2',
                'service_service_id' => '20',
                'contact_id' => '2'
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
            'SELECT * FROM contact_service_relation WHERE service_service_id IN (1,5)',
            array(
                array(
                    'csr_id' => '1',
                    'service_service_id' => '10',
                    'contact_id' => '1'
                ),
                array(
                    'csr_id' => '2',
                    'service_service_id' => '20',
                    'contact_id' => '2'
                )
            )
        );

        $sql = self::$contact->getList(self::$objectListIn);
        $this->assertEquals($sql, self::$objectListOut);
    }

    public function testGenerateSql()
    {

        $expectedResult = 'DELETE FROM contact_service_relation;
TRUNCATE contact_service_relation;
INSERT INTO `contact_service_relation` (`csr_id`,`service_service_id`,`contact_id`) ' .
            'VALUES (\'1\',\'10\',\'1\'),(\'2\',\'20\',\'2\');';

        $sql = self::$contact->generateSql(self::$objectListOut);
        $this->assertEquals($sql, $expectedResult);
    }
}
