Given /^the keyword test keywords$/ do
	@keyword = "test keywords"
  visit keywords_path
  click_link "New keyword"
  fill_in "keyword", :with => @keyword
  click_button
end

When /^i change nationality to en$/ do
	@nationality = 'en'
  visit keywords_path
  click_link "Edit"
  fill_in "langcode", :with => @nationality
  click_button
end

Then /^the keyword should have the nationality en$/ do
	visit keywords_path
  response.should contain("en")
end

When /^i delete the keyword$/ do
  visit keywords_path
  click_link "Destroy"
end

Then /^the keyword should be in the list$/ do
  visit keywords_path
  response.should contain(@keyword)
end

Then /^the keyword should not be in the list$/ do
  visit keywords_path
  response.should_not contain(@keyword)
end

