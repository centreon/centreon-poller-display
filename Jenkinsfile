stage('Source') {
  node {
    sh 'cd /opt/centreon-build && git pull && cd -'
    dir('centreon-poller-display') {
      checkout scm
    }
    sh '/opt/centreon-build/jobs/poller-display/mon-poller-display-source.sh'
    source = readProperties file: 'source.properties'
    env.VERSION = "${source.VERSION}"
    env.RELEASE = "${source.RELEASE}"
  }
}

try {
  stage('Unit tests') {
    parallel 'centos6': {
      node {
        sh 'cd /opt/centreon-build && git pull && cd -'
        sh '/opt/centreon-build/jobs/poller-display/mon-poller-display-unittest.sh centos6'
      }
    },
    'centos7': {
      node {
        sh 'cd /opt/centreon-build && git pull && cd -'
        sh '/opt/centreon-build/jobs/poller-display/mon-poller-display-unittest.sh centos7'
        step([
          $class: 'hudson.plugins.checkstyle.CheckStylePublisher',
          pattern: '**/codestyle.xml',
          usePreviousBuildAsReference: true,
          useDeltaValues: true,
          failedNewAll: '0'
        ])
      }
    }
    if ((currentBuild.result ?: 'SUCCESS') != 'SUCCESS') {
      error('Unit tests stage failure.');
    }
  }

  stage('Package') {
    parallel 'centos6': {
      node {
        sh 'cd /opt/centreon-build && git pull && cd -'
        sh '/opt/centreon-build/jobs/poller-display/mon-poller-display-package.sh centos6'
      }
    },
    'centos7': {
      node {
        sh 'cd /opt/centreon-build && git pull && cd -'
        sh '/opt/centreon-build/jobs/poller-display/mon-poller-display-package.sh centos7'
      }
    }
    if ((currentBuild.result ?: 'SUCCESS') != 'SUCCESS') {
      error('Package stage failure.');
    }
  }

  stage('Bundle') {
    parallel 'centos6': {
      node {
        sh 'cd /opt/centreon-build && git pull && cd -'
        sh '/opt/centreon-build/jobs/poller-display/mon-poller-display-bundle.sh centos6'
      }
    },
    'centos7': {
      node {
        sh 'cd /opt/centreon-build && git pull && cd -'
        sh '/opt/centreon-build/jobs/poller-display/mon-poller-display-bundle.sh centos7'
      }
    }
    if ((currentBuild.result ?: 'SUCCESS') != 'SUCCESS') {
      error('Bundle stage failure.');
    }
  }

  stage('Acceptance tests') {
    parallel 'centos6': {
      node {
        sh 'cd /opt/centreon-build && git pull && cd -'
        sh '/opt/centreon-build/jobs/poller-display/mon-poller-display-acceptance.sh centos6'
        step([
          $class: 'XUnitBuilder',
          thresholds: [
            [$class: 'FailedThreshold', failureThreshold: '0'],
            [$class: 'SkippedThreshold', failureThreshold: '0']
          ],
          tools: [[$class: 'JUnitType', pattern: 'xunit-reports/**/*.xml']]
        ])
        archiveArtifacts allowEmptyArchive: true, artifacts: 'acceptance-logs/*.txt, acceptance-logs/*.png'
      }
    },
    'centos7': {
      node {
        sh 'cd /opt/centreon-build && git pull && cd -'
        sh '/opt/centreon-build/jobs/poller-display/mon-poller-display-acceptance.sh centos7'
        step([
          $class: 'XUnitBuilder',
          thresholds: [
            [$class: 'FailedThreshold', failureThreshold: '0'],
            [$class: 'SkippedThreshold', failureThreshold: '0']
          ],
          tools: [[$class: 'JUnitType', pattern: 'xunit-reports/**/*.xml']]
        ])
        archiveArtifacts allowEmptyArchive: true, artifacts: 'acceptance-logs/*.txt, acceptance-logs/*.png'
      }
    }
    if ((currentBuild.result ?: 'SUCCESS') != 'SUCCESS') {
      error('Acceptance tests stage failure.');
    }
  }

  if (env.BRANCH_NAME == 'master') {
    stage('Delivery') {
      node {
        sh 'cd /opt/centreon-build && git pull && cd -'
        sh '/opt/centreon-build/jobs/poller-display/mon-poller-display-delivery.sh'
      }
      if ((currentBuild.result ?: 'SUCCESS') != 'SUCCESS') {
        error('Delivery stage failure.');
      }
    }
  }
}
finally {
  buildStatus = currentBuild.result ?: 'SUCCESS';
  /*
  if ((buildStatus != 'SUCCESS') && (env.BRANCH_NAME == 'master')) {
    slackSend channel: '#monitoring-metrology', message: "@channel Centreon Poller Display build ${env.BUILD_NUMBER} was broken by ${source.COMMITTER}. Please fix it ASAP."
  }
  */
}