Given /^some test report data$/ do
	r=Report.create
	r.reportrules.create
	r.reportrules.create
	r.reportrules.create
end

Then /^I should see reports listed with total number of rules for each report$/ do
  visit reports_path
  #FIXME verify that the table has rows
end

