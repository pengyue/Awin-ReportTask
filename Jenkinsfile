node {

    try {

        def commit_id

        stage ("Checkout") {
            checkout scm
            //sh "git rev-parse --short HEAD > .git/commit-id"
            //commit_id = readFile('.git/commit-id').trim()
            commit_id = "latest"
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
            sh "echo 'Running tests ...'"
            sh "vendor/bin/phpunit --config phpunit.xml --printer PHPUnit\\TextUI\\ResultPrinter"
            sh "vendor/bin/behat"
        }

        stage ('Docker Registration Push') {
            sh "echo 'PUSHING TO DockerHub ...'"
            docker.withRegistry('https://index.docker.io/v1/', 'Dockerhub') {
                def app = docker.build("pengyue/awin-reporttask:${commit_id}", '.').push()
            }
        }

    } catch(error) {
        throw error
    } finally {
        // Any cleanup operations needed, whether we hit an error or not
    }

}