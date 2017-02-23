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
use \CentreonPollerDisplayCentral\ConfigGenerate\Bam\Acl;
use \CentreonPollerDisplayCentral\ConfigGenerate\Bam\Host;
use \CentreonPollerDisplayCentral\ConfigGenerate\Bam\Hostgroup;
use \CentreonPollerDisplayCentral\ConfigGenerate\Bam\HostgroupRelation;
use \CentreonPollerDisplayCentral\ConfigGenerate\Bam\HostInformation;
use \CentreonPollerDisplayCentral\ConfigGenerate\Bam\HostRelation;
use \CentreonPollerDisplayCentral\ConfigGenerate\Bam\HostServiceRelation;
use \CentreonPollerDisplayCentral\ConfigGenerate\Bam\NagiosCfg;
use \CentreonPollerDisplayCentral\ConfigGenerate\Bam\NagiosServer;
use \CentreonPollerDisplayCentral\ConfigGenerate\Bam\Service;
use \CentreonPollerDisplayCentral\ConfigGenerate\Bam\ServiceInformation;


class ExportCentral extends AbstractObject
{
    public function generateObjects($poller)
    {

        $oAcl = new Acl();
        $oHost = new Host();
        $oHostgroup = new Hostgroup();
        $oHostgroupRelation = new HostgroupRelation();
        $oHostInformation = new HostInformation();
        $oHostRelation = new HostRelation();
        $oHostServiceRelation = new HostRelation();
        $oNagiosCfg = new NagiosCfg();
        $oNagiosServer = new NagiosServer();
        $oService = new Service();
        $oServiceInformation = new ServiceInformation();

        $contentFile = '';
        $contentFile .=  $oAcl->generateSql();
        $contentFile .=  $oHost->generateSql();
        $contentFile .=  $oHostgroup->generateSql();
        $contentFile .=  $oHostgroupRelation->generateSql();
        $contentFile .=  $oHostInformation->generateSql();
        $contentFile .=  $oHostRelation->generateSql();
        $contentFile .=  $oHostServiceRelation->generateSql();
        $contentFile .=  $oNagiosCfg->generateSql();
        $contentFile .=  $oNagiosServer->generateSql();
        $contentFile .=  $oService->generateSql();
        $contentFile .=  $oServiceInformation->generateSql();

        return $contentFile;
    }
}
