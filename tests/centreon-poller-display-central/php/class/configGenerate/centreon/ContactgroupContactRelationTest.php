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
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\ContactgroupContactRelation;


/**
 * @package centreon-poller-display-central
 * @version 1.0.0
 * @author Centreon
 */
class CentreonPollerDisplayCentral_ContactgroupContactRelation extends PHPUnit_Framework_TestCase
{
    protected static $db;
    protected static $pollerDisplay;
    protected static $contactg;
    protected static $objectListIn;
    protected static $objectListOut;

    public function setUp()
    {
        self::$db = new CentreonDB();
        self::$pollerDisplay = 1;
        self::$contactg = new ContactgroupContactRelation(self::$db, self::$pollerDisplay);
        self::$objectListIn = array(
            array(
                'contact_id' => '1',
                'contact_name' => 'toto'
            ),
            array(
                'contact_id' => '6',
                'contact_name' => 'tata'
            )
        );
        self::$objectListOut = array(
            array(
                'contact_contact_id' => '1',
                'contactgroup_cg_id' => '5'
            ),
            array(
                'contact_contact_id' => '6',
                'contactgroup_cg_id' => '3'
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
            'SELECT * FROM contactgroup_contact_relation WHERE contact_contact_id IN (1,6)',
            array(
                array(
                    'contact_contact_id' => '1',
                    'contactgroup_cg_id' => '5'
                ),
                array(
                    'contact_contact_id' => '6',
                    'contactgroup_cg_id' => '3'
                )
            )
        );

        $sql = self::$contactg->getList(self::$objectListIn);
        $this->assertEquals($sql, self::$objectListOut);
    }

    public function testGenerateSql()
    {

        $expectedResult = 'DELETE FROM contactgroup_contact_relation;
TRUNCATE contactgroup_contact_relation;
INSERT INTO `contactgroup_contact_relation` (`contact_contact_id`,`contactgroup_cg_id`) ' .
            'VALUES (\'1\',\'5\'),(\'6\',\'3\');';

        $sql = self::$contactg->generateSql(self::$objectListOut);
        $this->assertEquals($sql, $expectedResult);
    }
}
