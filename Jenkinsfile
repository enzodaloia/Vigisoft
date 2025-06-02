pipeline {
    agent any

    environment {
        SYMFONY_ENV = 'prod'
        SSH_KEY = credentials('vigisoft-prod-ssh-key')
    }

    stages {
        stage('Deploy code with Ansible') {
            steps {
                script {
                    writeFile file: '/tmp/id_rsa', text: SSH_KEY
                    sh 'chmod 600 /tmp/id_rsa'

                    sh 'ansible-playbook -i ansible/inventory.ini ansible/deploy-vigisoft.yml --private-key /tmp/id_rsa'
                }
            }
        }

        stage('Install PHP dependencies') {
            steps {
                sh 'composer install --no-dev --optimize-autoloader --no-interaction'
            }
        }

        stage('Install Node dependencies') {
            steps {
                sh 'npm install'
                sh 'npm run build'
            }
        }

        stage('Run Symfony checks') {
            steps {
                sh 'php bin/console lint:yaml config/'
                sh 'php bin/console lint:twig templates/'
                sh 'php bin/console doctrine:schema:validate'
            }
        }

        stage('Configure Apache with Ansible') {
            steps {
                script {
                    // Reutiliser le fichier de clé temporaire
                    sh 'ansible-playbook -i ansible/inventory.ini ansible/configure_apache.yml --private-key /tmp/id_rsa'
                }
            }
        }
    }

    post {
        failure {
            mail to: 'ton.email@example.com',
                 subject: "Build Failed: ${env.JOB_NAME} #${env.BUILD_NUMBER}",
                 body: "La build a échoué. Vérifie Jenkins."
        }
    }
}
