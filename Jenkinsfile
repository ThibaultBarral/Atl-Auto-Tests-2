pipeline {
    agent any

   
   environment {
        PATH = "$PATH:/usr/local/bin"
    }

    
    stages {
        stage('Checkout') {
            steps {
                git url: 'https://github.com/ThibaultBarral/Atl-Auto-Tests-2', branch: 'main'
            }
        }

        stage('Prepare Database') {
            steps {
                // Migrate the database schema to the latest version
                bat 'php bin/console doctrine:migrations:migrate --env=test --no-interaction'
            }
        }
        
        stage('Install dependencies') {
                steps {
                    // Utilisez la commande "bat" pour Windows
                    bat 'composer install'
                }
            }

        stage('Run tests') {
            steps {
                // Utilisez la commande "bat" pour Windows
                bat 'php bin/phpunit --log-junit tests/report.xml'
            }
        }
    }

    post {
        always {
            junit 'tests/report.xml'
        }
    }
}