Feature:
	In order to optimize search rankings
	Customers should be able to
	See historical rankings changes to
	help them understand the results of their SEO strategies.
	
	Scenario: Monitor keywords
		Given the website rsera with 1 ranking for rsera
		I should see rankings for the website
		
	Scenario: View keywords by priority
		Given the priority 1 website rsera
		With 1 ranking for rsera
		When ranking is 1
		I should see rankings for the website

