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
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\Contactgroup;


/**
 * @package centreon-poller-display-central
 * @version 1.0.0
 * @author Centreon
 */
class CentreonPollerDisplayCentral_Contactgroup extends PHPUnit_Framework_TestCase
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
        self::$contact = new Contactgroup(self::$db, self::$pollerDisplay);
        self::$objectListIn = array(
            array(
                'contact_contact_id' => '1',
                'contactgroup_cg_id' => '5'
            ),
            array(
                'contact_contact_id' => '6',
                'contactgroup_cg_id' => '3'
            )
        );
        self::$objectListOut = array(
            array(
                'cg_id' => '3',
                'cg_name' => 'group1'
            ),
            array(
                'cg_id' => '5',
                'cg_name' => 'group2'
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
            'SELECT * FROM contactgroup WHERE cg_id IN (5,3)',
            array(
                array(
                    'cg_id' => '3',
                    'cg_name' => 'group1'
                ),
                array(
                    'cg_id' => '5',
                    'cg_name' => 'group2'
                )
            )
        );

        $sql = self::$contact->getList(self::$objectListIn);
        $this->assertEquals($sql, self::$objectListOut);
    }

    public function testGenerateSql()
    {

        $expectedResult = 'DELETE FROM contactgroup;
TRUNCATE contactgroup;
INSERT INTO `contactgroup` (`cg_id`,`cg_name`) VALUES (\'3\',\'group1\'),(\'5\',\'group2\');';

        $sql = self::$contact->generateSql(self::$objectListOut);
        $this->assertEquals($sql, $expectedResult);
    }
}
