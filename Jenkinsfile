
pipeline {
     agent any

    options {
        disableConcurrentBuilds()
    }

    environment {
        IMAGE_REPO = 'bassam2080/myapp3'
   
    DOCKERHUB_CREDENTIALS = credentials('DockerHub')
    DOCKERHUB_USERNAME = 'bassam2080'
}
    

    stages {

        stage('Checkout') {
            steps {
                echo 'Downloading application from GitHub'

                git branch: 'main',
                    url: 'https://github.com/bassam2080/week72627.git'
            }
        }

        stage('Build') {
            steps {
                echo 'Building PHP Docker image'

                sh 'docker compose build webphp'
            }
        }

        stage('Run Containers') {
            steps {
                echo 'Starting PHP and MySQL containers'

                sh 'docker compose up -d --no-build'
                sh 'docker compose ps'
            }
        }

        stage('Testing') {
            steps {
                echo 'Testing PHP syntax'

                sh 'docker compose exec -T webphp php -l index.php'

                echo 'Testing HTTP response'

                sh '''
                    for i in $(seq 1 20); do
                        if docker compose exec -T webphp php -r \
                          'exit(@file_get_contents("http://127.0.0.1/") === false ? 1 : 0);'
                        then
                            echo "HTTP test passed"
                            break
                        fi

                        if [ "$i" -eq 20 ]; then
                            echo "HTTP test failed"
                            exit 1
                        fi

                        sleep 3
                    done
                '''

                echo 'Testing MySQL connectivity'

                sh '''
                    for i in $(seq 1 20); do
                        if docker compose exec -T db sh -c \
                          'mysql -uroot -p"$MYSQL_ROOT_PASSWORD" -e "SELECT 1"'
                        then
                            echo "Database test passed"
                            break
                        fi

                        if [ "$i" -eq 20 ]; then
                            echo "Database test failed"
                            exit 1
                        fi

                        sleep 3
                    done
                '''
            }
        }

        stage('Push to Docker Hub') {
    steps {
        echo 'Pushing Docker image to Docker Hub'

        sh '''
            echo "$DOCKERHUB_CREDENTIALS_PSW" | docker login \
                -u "$DOCKERHUB_CREDENTIALS_USR" --password-stdin

            IMAGE_ID=$(docker compose images -q webphp)

            docker tag "$IMAGE_ID" "$IMAGE_REPO:latest"

            docker push "$IMAGE_REPO:latest"

            docker logout
        '''
    }
}
      
    }

    post {
        success {
            echo 'CI/CD Pipeline completed successfully!'
        }
        failure {
            echo 'Pipeline failed. Image was not published.'
        }
    }

}
