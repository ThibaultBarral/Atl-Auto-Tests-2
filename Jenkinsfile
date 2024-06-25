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
                sh 'php bin/console doctrine:migrations:migrate --env=test --no-interaction'
            }
        }
        
        stage('Install dependencies') {
            steps {
                sh 'composer install'
            }
        }

        stage('Run tests') {
            steps {
                sh 'php bin/phpunit --log-junit tests/report.xml'
            }
        }
    }

    post {
        always {
            junit 'tests/report.xml'
        }
    }
}
