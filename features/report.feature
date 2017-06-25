Feature: Transaction report
  In order to analyse the transactions on merchants
  As a marketing analyst
  I want to generate a transaction report on existing csv file
  So that I can view transactions with different currencies and merchants

  Scenario Outline:
    Given The transaction data can be read on merchant id: "<merchantId>"
    When I want to generate a report on merchant id: "<merchantId>"
    Then I should see transactions csv file generated with only merchant id: "<merchantId>"
    Examples:
    | merchantId |
    | 1          |
