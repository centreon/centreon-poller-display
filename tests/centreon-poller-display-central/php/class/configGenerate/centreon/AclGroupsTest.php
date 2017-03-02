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
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\AclGroups;


/**
 * @package centreon-poller-display-central
 * @version 1.0.0
 * @author Centreon
 */
class CentreonPollerDisplayCentral_AclGroups extends PHPUnit_Framework_TestCase
{
    protected static $db;
    protected static $pollerDisplay;
    protected static $acl;
    protected static $objectListInC;
    protected static $objectListInCg;
    protected static $objectListOut;

    public function setUp()
    {
        self::$db = new CentreonDB();
        self::$pollerDisplay = 1;
        self::$acl = new AclGroups(self::$db, self::$pollerDisplay);
        self::$objectListInC = array(
            array(
                'contact_contact_id' => '1',
                'acl_group_id' => '14'
            ),
            array(
                'contact_contact_id' => '6',
                'acl_group_id' => '14'
            )
        );
        self::$objectListInCg = array(
            array(
                'cg_cg_id' => '4',
                'acl_group_id' => '1'
            )
        );
        self::$objectListOut = array(
            array(
                'acl_group_id' => '1',
                'acl_group_name' => 'guest'
            ),
            array(
                'acl_group_id' => '14',
                'acl_group_name' => 'toto'
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
            'SELECT * FROM acl_groups WHERE acl_group_id IN (1,14)',
            array(
                array(
                    'acl_group_id' => '1',
                    'acl_group_name' => 'guest'
                ),
                array(
                    'acl_group_id' => '14',
                    'acl_group_name' => 'toto'
                )
            )
        );

        $sql = self::$acl->getList(self::$objectListInC,self::$objectListInCg);
        $this->assertEquals($sql, self::$objectListOut);
    }

    public function testGenerateSql()
    {

        $expectedResult = 'DELETE FROM acl_groups;
TRUNCATE acl_groups;
INSERT INTO `acl_groups` (`acl_group_id`,`acl_group_name`) VALUES (\'1\',\'guest\'),(\'14\',\'toto\');';

        $sql = self::$acl->generateSql(self::$objectListOut);
        $this->assertEquals($sql, $expectedResult);
    }
}
