node {

    try {

        stage ("Checkout") {
            checkout scm
        }

        stage ("Build") {
            sh "composer install"
            sh "php deptrac.phar -v analyze depfile.yml"
        }

        stage ("Static code analysis") {
            //sh "vendor/bin/phpcs --config-set ignore_warnings_on_exit 1 --report=checkstyle --report-file=var/checkstyle-result.xml -q /src"
            //step([$class: 'hudson.plugins.checkstyle.CheckStylePublisher', pattern: 'checkstyle-*'])
        }

        stage ("Generate report") {
            //publishHTML([allowMissing: false, alwaysLinkToLastBuild: false, keepAll: false, reportDir: 'var/code-coverage', reportFiles: 'index.html', reportName: 'HTML Report', reportTitles: 'Code Report'])
        }

        stage ('Test') {
            // Run any testing suites
            sh "vendor/bin/phpunit --config phpunit.xml --printer PHPUnit_TextUI_ResultPrinter"
            sh "vendor/bin/behat"
        }

        stage ('Deploy') {
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