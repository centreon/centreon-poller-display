<?php
/**
 * Copyright 2017 Centreon
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

use Centreon\Test\Behat\CentreonContext;

class ConfigurationExportContext extends CentreonContext
{
    /**
     *  @Given a central Centreon server and a poller with Poller Display
     */
    public function aCentralAndAPollerDisplay()
    {
    }

    /**
     *  @Given I am logged in the central server
     */
    public function iAmLoggedInTheCentralServer()
    {
    }

    /**
     *  @Given hosts linked to the poller
     */
    public function hostsLinkedToThePoller()
    {
    }

    /**
     *  @Given services linked to the poller
     */
    public function servicesLinkedToThePoller()
    {
    }

    /**
     *  @When I export the poller configuration
     */
    public function iExportThePollerConfiguration()
    {
    }

    /**
     *  @Then the hosts are monitored from the poller
     */
    public function theHostsAreMonitoredFromThePoller()
    {
    }

    /**
     *  @Then the services are monitored from the poller
     */
    public function theServicesAreMonitoredFromThePoller()
    {
    }

    /**
     *  @Then /^the hosts monitoring data is available from the ([a-z]*)$/
     */
    public function theHostsMonitoringDataIsPrintedOnTheServer($server)
    {
    }

    /**
     *  @Then /^the services monitoring data is available from the ([a-z]*)$/
     */
    public function theServicesMonitoringDataIsPrintedOnTheServer($server)
    {
    }
}
