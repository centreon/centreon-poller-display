<?php
/*
 * Copyright 2005-2017 Centreon
 * Centreon is developped by : Julien Mathis and Romain Le Merlus under
 * GPL Licence 2.0.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation ; either version 2 of the License.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
 * PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, see <http://www.gnu.org/licenses>.
 *
 * Linking this program statically or dynamically with other modules is making a
 * combined work based on this program. Thus, the terms and conditions of the GNU
 * General Public License cover the whole combination.
 *
 * As a special exception, the copyright holders of this program give Centreon
 * permission to link this program with independent modules to produce an executable,
 * regardless of the license terms of these independent modules, and to copy and
 * distribute the resulting executable under terms of Centreon choice, provided that
 * Centreon also meet, for each linked independent module, the terms  and conditions
 * of the license of that module. An independent module is a module which is not
 * derived from this program. If you modify this program, you may extend this
 * exception to your version of the program, but you are not obliged to do so. If you
 * do not wish to do so, delete this exception statement from your version.
 *
 * For more information : contact@centreon.com
 *
 */

namespace CentreonPollerDisplayCentral\ConfigGenerate;

use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\AclActions;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\AclActionsRules;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\AclGroupActionsRelation;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\AclGroupContactgroupsRelation;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\AclGroupContactsRelation;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\AclGroupTopology;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\AclGroups;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\AclResources;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\AclResourcesGroupRelation;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\AclResourcesHost;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\AclResourcesHostCategorie;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\AclResourcesHostex;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\AclResourcesHostgroup;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\AclResourcesMeta;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\AclResourcesPoller;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\AclResourcesService;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\AclResourcesServiceCategorie;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\AclResourcesServicegroup;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\Contact;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\ContactHostRelation;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\ContactServiceRelation;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\Contactgroup;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\ContactgroupContactRelation;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\ContactgroupHostRelation;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\ContactgroupHostgroupRelation;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\ContactgroupServiceRelation;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\ContactgroupServicegroupRelation;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\Host;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\HostCategories;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\HostCategoriesRelation;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\HostInformation;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\HostRelation;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\HostServiceRelation;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\Hostgroup;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\HostgroupRelation;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\MetaContact;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\MetaContactgroup;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\MetaService;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\MetaServiceRelation;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\NagiosCfg;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\NagiosServer;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\Service;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\ServiceCategories;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\ServiceCategoriesRelation;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\ServiceInformation;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\Servicegroup;
use \CentreonPollerDisplayCentral\ConfigGenerate\Centreon\ServicegroupRelation;

class Centreon extends \AbstractObject
{
    protected $engine = false;
    protected $broker = true;
    protected $generate_filename = 'centreon-poller-display.sql';

    public function generateObjects($poller_id)
    {
        $db = $this->backend_instance->db;

        $oAclActions = new AclActions($db,$poller_id);
        $oAclActionsRules = new AclActionsRules($db,$poller_id);
        $oAclGroupActionsRelation = new AclGroupActionsRelation($db,$poller_id);
        $oAclGroupContactgroupsRelation = new AclGroupContactgroupsRelation($db,$poller_id);
        $oAclGroupContactsRelation = new AclGroupContactsRelation($db,$poller_id);
        $oAclGroupTopology = new AclGroupTopology($db,$poller_id);
        $oAclGroups = new AclGroups($db,$poller_id);
        $oAclResources = new AclResources($db,$poller_id);
        $oAclResourcesGroupRelation = new AclResourcesGroupRelation($db,$poller_id);
        $oAclResourcesHost = new AclResourcesHost($db,$poller_id);
        $oAclResourcesHostCategorie = new AclResourcesHostCategorie($db,$poller_id);
        $oAclResourcesHostex = new AclResourcesHostex($db,$poller_id);
        $oAclResourcesHostgroup = new AclResourcesHostgroup($db,$poller_id);
        $oAclResourcesMeta = new AclResourcesMeta($db,$poller_id);
        $oAclResourcesPoller = new AclResourcesPoller($db,$poller_id);
        $oAclResourcesService = new AclResourcesService($db,$poller_id);
        $oAclResourcesServiceCategorie = new AclResourcesServiceCategorie($db,$poller_id);
        $oAclResourcesServicegroup = new AclResourcesServicegroup($db,$poller_id);
        $oContact = new Contact($db,$poller_id);
        $oContactHostRelation = new ContactHostRelation($db,$poller_id);
        $oContactServiceRelation = new ContactServiceRelation($db,$poller_id);
        $oContactgroup = new Contactgroup($db,$poller_id);
        $oContactgroupContactRelation = new ContactgroupContactRelation($db,$poller_id);
        $oContactgroupHostRelation = new ContactgroupHostRelation($db,$poller_id);
        $oContactgroupHostgroupRelation = new ContactgroupHostgroupRelation($db,$poller_id);
        $oContactgroupServiceRelation = new ContactgroupServiceRelation($db,$poller_id);
        $oContactgroupServicegroupRelation = new ContactgroupServicegroupRelation($db,$poller_id);
        $oHost = new Host($db,$poller_id);
        $oHostCategories = new HostCategories($db,$poller_id);
        $oHostCategoriesRelation = new HostCategoriesRelation($db,$poller_id);
        $oHostInformation = new HostInformation($db,$poller_id);
        $oHostRelation = new HostRelation($db,$poller_id);
        $oHostServiceRelation = new HostServiceRelation($db,$poller_id);
        $oHostgroup = new Hostgroup($db,$poller_id);
        $oHostgroupRelation = new HostgroupRelation($db,$poller_id);
        $oMetaContact = new MetaContact($db,$poller_id);
        $oMetaContactgroup = new MetaContactgroup($db,$poller_id);
        $oMetaService = new MetaService($db,$poller_id);
        $oMetaServiceRelation = new MetaServiceRelation($db,$poller_id);
        $oNagiosCfg = new NagiosCfg($db,$poller_id);
        $oNagiosServer = new NagiosServer($db,$poller_id);
        $oService = new Service($db,$poller_id);
        $oServiceCategories = new ServiceCategories($db,$poller_id);
        $oServiceCategoriesRelation = new ServiceCategoriesRelation($db,$poller_id);
        $oServiceInformation = new ServiceInformation($db,$poller_id);
        $oServicegroup = new Servicegroup($db,$poller_id);
        $oServicegroupRelation = new ServicegroupRelation($db,$poller_id);

        $sql = '';
        $sql .=  $oAclActions->generateSql(). "\n\n";
        $sql .=  $oAclActionsRules->generateSql(). "\n\n";
        $sql .=  $oAclGroupActionsRelation->generateSql(). "\n\n";
        $sql .=  $oAclGroupContactgroupsRelation->generateSql(). "\n\n";
        $sql .=  $oAclGroupContactsRelation->generateSql(). "\n\n";
        $sql .=  $oAclGroupTopology->generateSql(). "\n\n";
        $sql .=  $oAclGroups->generateSql(). "\n\n";
        $sql .=  $oAclResources->generateSql(). "\n\n";
        $sql .=  $oAclResourcesGroupRelation->generateSql(). "\n\n";
        $sql .=  $oAclResourcesHost->generateSql(). "\n\n";
        $sql .=  $oAclResourcesHostCategorie->generateSql(). "\n\n";
        $sql .=  $oAclResourcesHostex->generateSql(). "\n\n";
        $sql .=  $oAclResourcesHostgroup->generateSql(). "\n\n";
        $sql .=  $oAclResourcesMeta->generateSql(). "\n\n";
        $sql .=  $oAclResourcesPoller->generateSql(). "\n\n";
        $sql .=  $oAclResourcesService->generateSql(). "\n\n";
        $sql .=  $oAclResourcesServiceCategorie->generateSql(). "\n\n";
        $sql .=  $oAclResourcesServicegroup->generateSql(). "\n\n";
        $sql .=  $oContact->generateSql(). "\n\n";
        $sql .=  $oContactHostRelation->generateSql(). "\n\n";
        $sql .=  $oContactServiceRelation->generateSql(). "\n\n";
        $sql .=  $oContactgroup->generateSql(). "\n\n";
        $sql .=  $oContactgroupContactRelation->generateSql(). "\n\n";
        $sql .=  $oContactgroupHostRelation->generateSql(). "\n\n";
        $sql .=  $oContactgroupHostgroupRelation->generateSql(). "\n\n";
        $sql .=  $oContactgroupServiceRelation->generateSql(). "\n\n";
        $sql .=  $oContactgroupServicegroupRelation->generateSql(). "\n\n";
        $sql .=  $oHost->generateSql(). "\n\n";
        $sql .=  $oHostCategories->generateSql(). "\n\n";
        $sql .=  $oHostCategoriesRelation->generateSql(). "\n\n";
        $sql .=  $oHostInformation->generateSql(). "\n\n";
        $sql .=  $oHostRelation->generateSql(). "\n\n";
        $sql .=  $oHostServiceRelation->generateSql(). "\n\n";
        $sql .=  $oHostgroup->generateSql(). "\n\n";
        $sql .=  $oHostgroupRelation->generateSql(). "\n\n";
        $sql .=  $oMetaContact->generateSql(). "\n\n";
        $sql .=  $oMetaContactgroup->generateSql(). "\n\n";
        $sql .=  $oMetaService->generateSql(). "\n\n";
        $sql .=  $oMetaServiceRelation->generateSql(). "\n\n";
        $sql .=  $oNagiosCfg->generateSql(). "\n\n";
        $sql .=  $oNagiosServer->generateSql(). "\n\n";
        $sql .=  $oService->generateSql(). "\n\n";
        $sql .=  $oServiceCategories->generateSql(). "\n\n";
        $sql .=  $oServiceCategoriesRelation->generateSql(). "\n\n";
        $sql .=  $oServiceInformation->generateSql(). "\n\n";
        $sql .=  $oServicegroup->generateSql(). "\n\n";
        $sql .=  $oServicegroupRelation->generateSql(). "\n\n";

        $this->createFile($this->backend_instance->getPath());
        fwrite($this->fp, $sql);
        $this->close_file();
    }
}
