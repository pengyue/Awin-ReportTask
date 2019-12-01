Affiliate Window Candidate Task 
===============================

### Objective

To demonstrate your OOP and unit testing skills.

### Task

Create a simple report that shows transactions for a merchant id specified as command line argument.

The data.csv file contains dummy data in different currencies, the report should be in GBP.

Assume that data changes and comes from a database, csv file is just for simplicity, 
feel free to replace with sqlite if that helps.

Please add code, unit tests and documentation (docblocks, comments). You do not need to connect to a real currency 
webservice, a dummy webservice client that returns random or static values is fine.

Provided code is just an indication, do not feel the need to use them if you don't want to. If something is not clear, improvise.

Use any 3rd party framework or components as you see fit. Please use composer where possible if depending on 3rd party code.

### Assessment

Your task will be assessed on your use of OOP, dependency injection, unit testing and commenting against the level of 
the position for which you have applied.

Points will be deducted for leaving any redundant files in your code (e.g. left overs from framework skeleton app creation).


================ Task README =====================


# Awin Report Task project

The project is to create a command to generate a transaction report on merchant_id and date for all 3 currencies (GBP, EUR, USD).
It uses Symfony 3 framework and implemented with customized template, it utilizes dependency injection,
service container, unit tests, integration tests, behat tests, SOLID design pattern such as observer pattern.
It has nearly 100% code coverage on the main logic directories (src/).


[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/pengyue/Awin-ReportTask/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/pengyue/Awin-ReportTask/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/b/maplesyrupgroup/qp-ms-product-search/badges/coverage.png?b=master&s=b495c2948f06e0aebe39a9bd1c7dcf5ba5fbaf67)](https://scrutinizer-ci.com/b/maplesyrupgroup/qp-ms-product-search/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/pengyue/Awin-ReportTask/badges/build.png?b=master)](https://scrutinizer-ci.com/g/pengyue/Awin-ReportTask/build-status/master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/pengyue/Awin-ReportTask/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)


## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. 
See deployment for notes on how to deploy the project on a live system.

To start this project, use composer update to install all the dependencies


### Prerequisites

None


### Installing

A step by step series of examples that tell you have to get a development env running.

The report can be generate by running a symfony console command, the console command is located at
bin/console, the source data file is at var/storage/data.csv, and the output report file will 
generated at var/storage/report.csv. The code review report can be located at var/storage/code-coverage, 
the behat tests can be located at ./features 

Firs of all, install the project dependencies

```
Composer update
```

To generate all the transactions, then run the command

```
php bin/console report:merchant
```

To generate the transactions with merchant id 1, then run the command

```
php bin/console report:merchant 1
```

To generate the transactions with date 01/05/2010, then run the command

```
php bin/console report:merchant null 01/05/2010
```

To generate the transactions with merchant_id 1 and date 01/05/2010, then run the command

```
php bin/console report:merchant 1 01/05/2010
```

Please review the report csv file when each command is run, for example, it look like

```
"Merchant ID",Date,"Original Transaction","Currency Symbol","Transaction Amount","Transaction In GBP","Transaction In EUR","Transaction In USD"
1,01/05/2010,£50.00,£,50.00,£50.00,€55.50,$65.00
1,02/05/2010,£11.04,£,11.04,£11.04,€12.70,$14.02
1,02/05/2010,€1.00,€,1.00,£0.86,€1.00,$1.12
1,03/05/2010,$23.05,$,23.05,£18.44,€20.05,$23.05
```


## Running the tests

There are 3 kinds of automated tests, unit tests, integration test and behat tests

### Break down into end to end tests

To run unit tests with default Nyancat output.

```
vendor/bin/phpunit tests/unit
```

To run unit tests with plain PHPUnit output.

```
vendor/bin/phpunit tests/unit --printer PHPUnit_TextUI_ResultPrinter
```

To run integration tests.

```
vendor/bin/phpunit tests/integration
```

To run tests with generated code coverage.

```
vendor/bin/phpunit tests/ --coverage-clover=var/code-coverage/phpcov-unit.xml --coverage-html=var/code-coverage/phpcov-unit.html
```

To run behat tests.

```
vendor/bin/behat
```


### Dependency checks

The code has been run with the deptrac to verify any dependency violations. The dependencies diagram
can be found at var/artifacts/deptrac/dependeencies.png.

To run the command below to generate the report, before running the command, please install graphviz first.
(Please refer to https://github.com/sensiolabs-de/deptrac for installation)

```
php deptrac.phar analyze depfile.yml --formatter-graphviz-dump-image=var/artifacts/deptrac/dependencies.png
```

### Docker build

The Dockerfile has been added, to generate a new image, please run command below, 
remember removing 'vendor' and 'var/cache/*' before

```
docker build -t awin-task .
```


### And coding style tests

The code follows PSR/2 code standard, and use PSR-0 autoloading


## Deployment

There is no live deployment


## Contributing

None


## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/pengyue/Awin-ReportTask/tags). 


## Authors

* **Peng Yue** - *Initial work* - [ReportTask](https://github.com/pengyue/Awin-ReportTask)

See also the list of [contributors](https://github.com/pengyue/Awin-ReportTask/contributors) who participated in this project.


## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
