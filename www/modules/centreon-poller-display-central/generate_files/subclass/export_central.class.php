<?php
/*
 * Centreon
 *
 * Source Copyright 2005-2016 Centreon
 *
 * Unauthorized reproduction, copy and distribution
 * are not allowed.
 *
 * For more informations : contact@centreon.com
 *
 */
require_once dirname(__FILE__) . '/../../core/class/config-generate/central/Acl.php';
require_once dirname(__FILE__) . '/../../core/class/config-generate/central/Host.php';
require_once dirname(__FILE__) . '/../../core/class/config-generate/central/Hostgroup.php';
require_once dirname(__FILE__) . '/../../core/class/config-generate/central/HostgroupRelation.php';
require_once dirname(__FILE__) . '/../../core/class/config-generate/central/HostInformation.php';
require_once dirname(__FILE__) . '/../../core/class/config-generate/central/HostRelation.php';
require_once dirname(__FILE__) . '/../../core/class/config-generate/central/HostServiceRelation.php';
require_once dirname(__FILE__) . '/../../core/class/config-generate/central/NagiosCfg.php';
require_once dirname(__FILE__) . '/../../core/class/config-generate/central/NagiosServer.php';
require_once dirname(__FILE__) . '/../../core/class/config-generate/central/Service.php';
require_once dirname(__FILE__) . '/../../core/class/config-generate/central/ServiceInformation.php';


class ExportCentral extends AbstractObject {

    public function generateObjects($poller) {

        ExportCentral::getInstance()->generateObjects();


    }
}
