Feature: Managed keywords

	So that i can quickly find and modify keywords
	As administrator
	I want to list all keywords
  
	Scenario: Add keyword
		Given the keyword test keywords
		Then the keyword should be in the list
  	
	Scenario: Change nationality
		Given the keyword test keywords
		When i change nationality to en
		Then the keyword should have the nationality en

	Scenario: Delete keyword
		Given the keyword test keywords
		When i delete the keyword
		Then the keyword should not be in the list

