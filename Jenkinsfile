node {

    try {

        stage("Checkout") {
            check scm
        }

        stage("Build") {
            sh "composer install"
        }

        stage('test') {
            // Run any testing suites
            sh "vendor/bin/phpunit --config phpunit.xml --printer PHPUnit_TextUI_ResultPrinter"
            sh "vendor/bin/behat"
        }

        stage('deploy') {
            // If we had ansible installed on the server, setup to run an ansible playbook
            // sh "ansible-playbook -i ./ansible/hosts ./ansible/deploy.yml"
            sh "echo 'DEPLOYING...'"
        }

    } catch(error) {
        throw error
    } finally {
        // Any cleanup operations needed, whether we hit an error or not
    }

}