pipeline {
  agent any

  stages {
    stage('Deploy Symfony Docker') {
      steps {
        script {
          withCredentials([sshUserPrivateKey(credentialsId: 'vigisoft-prod-ssh-key', keyFileVariable: 'SSH_KEY_PATH')]) {
            sh '''
              ansible-playbook -i ansible/inventory.ini ansible/deploy-vigisoft.yml --private-key $SSH_KEY_PATH
            '''
          }
        }
      }
    }
  }
}
