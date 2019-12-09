// set build properties
properties([
    buildDiscarder(logRotator(numToKeepStr: '10')),   // keep only last 10 builds
    [$class: 'GithubProjectProperty', projectUrlStr: 'https://github.com/pengyue/Awin-ReportTask'],
//    pipelineTriggers([cron('@daily')])  // build once a day
])

node {

    environment {
        registry = "pengyue/awin-reporttask"
        registryCredential = ‘dockerhub’
    }

    def commitId = "latest"
    def user = env.BUILD_USER_ID

    def version = 'latest'
    def projectName = "awin-report-task"
    def slackBaseUrl = "https://triplanteam.slack.com/services/hooks/jenkins-ci/"
    def slackTeamDomain = "triplanteam"
    def ownerSlackChannel = "#deployment"
    def slackTokenCredentialId = "hQ42R4mSgcZfFxsyD8M0Dwt0"
    def ownerEmail = "penyues@gmail.com"

    stage ("Checkout") {

       milestone()
       checkout scm

       gitCommit = sh(returnStdout: true, script: 'git rev-parse HEAD').trim()
       shortCommit = gitCommit.take(7)

       //pom = readMavenPom file: 'pom.xml'
       //version = "${pom.version}-${BUILD_NUMBER}-${shortCommit}"

       version = "build-${BUILD_NUMBER}-${shortCommit}"
       echo "version: ${version}"
       currentBuild.description = version
    }

    stage('Setup JAVA env') {
       //env.JAVA_HOME="${tool 'jdk 8u51'}"
       //env.PATH="${env.JAVA_HOME}/bin:${env.PATH}"
       //sh 'java -version'
    }

    try {

       milestone()
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
          sh "vendor/bin/phpunit --config phpunit.xml --printer PHPUnit\\\\TextUI\\\\ResultPrinter"
          sh "vendor/bin/behat"
       }

       milestone()
       stage ('Docker Registration Push') {
          sh "echo 'PUSHING TO DockerHub ...'"
          docker.withRegistry('https://index.docker.io/v1/', 'dockerhub') {
              def app = docker.build("pengyue/awin-reporttask:${commitId}", '.').push()
          }
       }

       def currentDate = getCurrentDate()
       def deploy = canDeploy(currentDate)

       if (!deploy) {
           try {
               timeout(time: 1, unit: 'MINUTES') {
                   input message: 'Do you want to deploy outside of deployment hours?',
                       parameters: [[$class: 'BooleanParameterDefinition',
                                     defaultValue: false,
                                     description: 'Ticking this box will do a live deploy',
                                     name: 'Live Deploy']]
               }
               deploy = true
           } catch (err) {
               deploy = false
               echo "${err}"
               echo "Production deployment skipped."
           }
       }

       if (deploy) {
           // if we got till here w/o any errors, means job is successful
           currentBuild.result = 'SUCCESS'
           println("Automatic deployment allowed")
           lock('Production Deployment') {
               stage('Deploy to Production') {
                   //sh "make VERSION=${version} TRIGGER=${user} SERVER_LIST=${productionServerList} SPRING_PROFILES_ACTIVE=production deploy-production"
                   //trigger a kubernetes deployment here
               }
           }
       } else {
           currentBuild.result = "FAILURE"
           println("Automatic deployment to production is only allowed at the following times:")
           println("Mon-Thu 7 -> 17:30 London time")
           println("Fri 7 -> 12:00 London time")
           println("Run the build again within those time windows next time :p")
       }

    } catch(error) {
       currentBuild.result = "FAILURE"
       echo "${err}"
    } finally {
       // Any cleanup operations needed, whether we hit an error or not
       if (currentBuild.result == "FAILURE") {
           //sh "make clean version=${version}"
           //throw error
        }
    }

    if (currentBuild.result == "FAILURE" && currentBuild.previousBuild.result != 'FAILURE') {
        slackSend baseUrl: "${slackBaseUrl}",
            teamDomain: "${slackTeamDomain}",
            channel: "${ownerSlackChannel}",
            color: '#af0000',
            message: "master branch of ${projectName} has failed - ${env.BUILD_URL}",
            tokenCredentialId: "${slackTokenCredentialId}"

        mail from: 'zipp_yp@hotmail.com',
            to: "${ownerEmail}",
            subject: "${projectName} build failed",
            body: "${projectName} build failed: ${env.BUILD_URL}"

        // for recovery
    } else if (currentBuild.result == 'SUCCESS' && currentBuild.previousBuild.result != 'SUCCESS') {
        slackSend baseUrl: "${slackBaseUrl}",
            teamDomain: "${slackTeamDomain}",
            channel: "${ownerSlackChannel}",
            color: '#00960c',
            message: "master branch of ${projectName} has recovered! - ${env.BUILD_URL}",
            tokenCredentialId: "${slackTokenCredentialId}"

        mail from: 'zippo_yp@hotmail.com',
            to: "${ownerEmail}",
            subject: "${projectName} build has recovered",
            body: "${projectName} build has recovered: ${env.BUILD_URL}"
    }


    }

    def canDeploy(Date date) {

    // Monoday to Thursday between 07:00 and 17:30 London time
    // On Friday from 07:00 to 12:00 London time
    def londonTimeZone = TimeZone.getTimeZone('Europe/London')

    int day_of_week = date.format("u", londonTimeZone) as Integer
    int hour = date.format("HH", londonTimeZone) as Integer
    int minute = date.format("mm", londonTimeZone) as Integer

    // Monday - Thursday
    if (day_of_week < 5) {
        if ((hour >= 7 && hour < 17) || (hour == 17 && minute <= 30)) {
            return true
        }
    }

    // Friday
    if (day_of_week == 5) {
        // Between 7 and 12:00
        if ((hour >= 7) && (hour < 12)) {
            return true
        }
    }

    return false
}

def getCurrentDate() {

    def date = new Date()
    int day_of_week = date.format("u") as Integer
    int hour = date.format("HH") as Integer
    int minute = date.format("mm") as Integer

    println(date)
    println("Day of week: ${day_of_week}\t hour: ${hour}\t minute: ${minute}")

    return date
}