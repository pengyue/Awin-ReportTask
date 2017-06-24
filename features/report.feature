Feature: Transaction report
  In order to analyse the transactions on merchants
  As a marketing analyst
  I want to generate a transaction report on existing csv file
  So that I can view transactions with different currencies and merchants

  Scenario Outline:
    Given The transaction data can be read on merchant id "<merchant_id>"
    When I want to generate a report on merchant id "<merchant_id>"
    Then I should see transactions with merchant id "1"
    Examples:
    | merchant_id |
    | 1           |
