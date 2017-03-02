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
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\Contact;


/**
 * @package centreon-poller-display-central
 * @version 1.0.0
 * @author Centreon
 */
class CentreonPollerDisplayCentral_Contact extends PHPUnit_Framework_TestCase
{
    protected static $db;
    protected static $pollerDisplay;
    protected static $contact;
    protected static $objectListInH;
    protected static $objectListInS;
    protected static $objectListOut;

    public function setUp()
    {
        self::$db = new CentreonDB();
        self::$pollerDisplay = 1;
        self::$contact = new Contact(self::$db, self::$pollerDisplay);
        self::$objectListInH = array(
            array(
                'host_host_id' => '1',
                'contact_id' => '2'
            ),
            array(
                'host_host_id' => '2',
                'contact_id' => '6'
            )
        );
        self::$objectListInS = array(
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
        self::$objectListOut = array(
            array(
                'contact_id' => '1',
                'contact_name' => 'toto'
            ),
            array(
                'contact_id' => '6',
                'contact_name' => 'tata'
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
            'SELECT * FROM contact WHERE contact_id IN (2,6,1)',
            array(
                array(
                    'contact_id' => '1',
                    'contact_name' => 'toto'
                ),
                array(
                    'contact_id' => '6',
                    'contact_name' => 'tata'
                )
            )
        );

        $sql = self::$contact->getList(self::$objectListInH,self::$objectListInS);
        $this->assertEquals($sql, self::$objectListOut);
    }

    public function testGenerateSql()
    {

        $expectedResult = 'DELETE FROM contact;
TRUNCATE contact;
INSERT INTO `contact` (`contact_id`,`contact_name`) VALUES (\'1\',\'toto\'),(\'6\',\'tata\');';

        $sql = self::$contact->generateSql(self::$objectListOut);
        $this->assertEquals($sql, $expectedResult);
    }
}
