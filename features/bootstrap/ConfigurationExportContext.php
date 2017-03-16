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
use Centreon\Test\Behat\Configuration\HostConfigurationPage;
use Centreon\Test\Behat\Monitoring\HostMonitoringDetailsPage;
use Centreon\Test\Behat\External\LoginPage;
use Centreon\Test\Behat\Configuration\ServiceConfigurationPage;
use Centreon\Test\Behat\Monitoring\ServiceMonitoringDetailsPage;

class ConfigurationExportContext extends CentreonContext
{
    private $baseUrlCentral;
    private $baseUrlPoller;
    private $hostName;
    private $serviceDescription;

    /**
     *  Constructor.
     */
    public function __construct()
    {
        $this->baseUrlCentral = '';
        $this->baseUrlPoller = 'http://poller/centreon/';
        $this->hostName = 'AcceptanceTestHost';
        $this->serviceDescription = 'AcceptanceTestService';
    }

    /**
     *  @Given a central Centreon server and a poller with Poller Display
     */
    public function aCentralAndAPollerDisplay()
    {
        $this->launchCentreonWebContainer('poller-display');
        $this->container->waitForAvailableUrl(
            'http://' . $this->container->getHost() . ':' .
            $this->container->getPort(80, 'poller')
        );
    }

    /**
     *  @Given I am logged in the central server
     */
    public function iAmLoggedInTheCentralServer()
    {
        $this->iAmLoggedIn();
    }

    /**
     *  @Given hosts linked to the poller
     */
    public function hostsLinkedToThePoller()
    {
        $page = new HostConfigurationPage($this);
        $page->setProperties(array(
            'name' => $this->hostName,
            'alias' => $this->hostName,
            'address' => 'localhost',
            'poller' => 'Poller',
            'templates' => 'generic-host',
            'max_check_attempts' => 1,
            'normal_check_interval' => 1,
            'retry_check_interval' => 1,
            'active_checks_enabled' => 1,
            'passive_checks_enabled' => 1
        ));
        $page->save();
    }

    /**
     *  @Given services linked to the poller
     */
    public function servicesLinkedToThePoller()
    {
        $page = new ServiceConfigurationPage($this);
        $page->setProperties(array(
            'hosts' => $this->hostName,
            'description' => $this->serviceDescription,
            'templates' => 'generic-service',
            'check_command' => 'check_centreon_dummy',
            'check_period' => '24x7',
            'max_check_attempts' => 1,
            'normal_check_interval' => 1,
            'retry_check_interval' => 1,
            'active_checks_enabled' => 1,
            'passive_checks_enabled' => 1
        ));
        $page->save();
    }

    /**
     *  @When I export the poller configuration
     */
    public function iExportThePollerConfiguration()
    {
        $this->restartAllPollers();
        $this->iAmLoggedOut();
    }

    /**
     *  @Then /^the hosts monitoring data is available from the ([a-z]*)$/
     */
    public function theHostsMonitoringDataIsPrintedOnTheServer($server)
    {
        // cron daemon is not started, run cron task manually.
        $this->container->execute("/bin/bash /usr/share/centreon/cron/centreon-poller-display-sync.sh", 'poller');
        sleep(3);

        // Login.
        $baseUrl = ($server == 'poller') ? $this->baseUrlPoller : $this->baseUrlCentral;
        $this->visit($baseUrl);
        $page = new LoginPage($this, false);
        $page->login('admin', 'centreon');

        // Visit details page.
        $this->spin(
            function ($context) use ($baseUrl) {
                $context->visit(
                    $baseUrl . 'main.php?p=20202&o=hd&host_name=' .
                    $context->hostName
                );
                $page = new HostMonitoringDetailsPage($context, null, false);
                $props = $page->getProperties();
                if (($props['poller'] !== 'Poller') ||
                    ($props['state'] !== HostMonitoringDetailsPage::STATE_UP)) {
                    throw new \Exception('Invalid host properties.');
                }
            },
            'Properties of host ' . $this->hostName .
            ' are not correct on server ' . $server . '.'
        );
    }

    /**
     *  @Then /^the services monitoring data is available from the ([a-z]*)$/
     */
    public function theServicesMonitoringDataIsPrintedOnTheServer($server)
    {
        // Visit details page.
        $baseUrl = ($server == 'poller') ? $this->baseUrlPoller : $this->baseUrlCentral;
        $this->spin(
            function ($context) use ($baseUrl) {
                $context->visit(
                    $baseUrl . '/main.php?p=20201&o=svcd&host_name=' .
                    $context->hostName . '&service_description=' .
                    $context->serviceDescription
                );
                $page = new ServiceMonitoringDetailsPage($context, null, null, false);
                $props = $page->getProperties();
                if ($props['state'] !== ServiceMonitoringDetailsPage::STATE_OK) {
                    throw new \Exception('Invalid service properties.');
                }
            },
            'Properties of service ' . $this->serviceDescription .
            ' are not correct on server ' . $server
        );

        // Logout.
        $this->visit($baseUrl . 'index.php?disconnect=1');
    }
}
