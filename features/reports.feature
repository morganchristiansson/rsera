Feature: View reports

	I want to see when reports were generated and how many rules are logged for each report.
	
	Scenario: View reports
		Given some test report data
		Then I should see reports listed with total number of rules for each report

