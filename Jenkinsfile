pipeline {
  agent any
  
  triggers {
    cron 'H 22 * * *'
  }
  
  options {
    buildDiscarder(logRotator(numToKeepStr: '30', artifactNumToKeepStr: '3'))
  }

  stages {
    stage('editorconfig') {
      steps {
        script {
          docker.image('mstruebing/editorconfig-checker').inside {
            sh 'ec -no-color'
          }
        }
      }
    }

    stage('test') {
      agent {
        dockerfile {
          dir 'docker/dev'    
        }
      }
      steps {
        sh 'composer install --no-progress'
        sh './vendor/bin/phpunit --log-junit phpunit-junit.xml || exit 0'
        junit 'phpunit-junit.xml'
      } 
    }

    stage('deploy') {
      steps {
        script {
          def image = docker.build("engine-listing-service:latest", "-f docker/prod/Dockerfile .")
          docker.withRegistry('https://docker-registry.ivyteam.io', 'docker-registry.ivyteam.io') {
            image.push()
          }
        }
      } 
    }
  }
}
