pipeline {
    environment {
        GITHUB = credentials("github")
    }
    agent any
    stages {
        stage('GitHubPush') {
            steps { 
                sh "git push --force https://${GITHUB}@github.com/ikus060/nagios-plugins.git refs/remotes/origin/${BRANCH_NAME}:refs/heads/${BRANCH_NAME}"
                sh "git push https://${GITHUB}@github.com/ikus060/nagios-plugins.git --tags"
            }
        }
    }
}
