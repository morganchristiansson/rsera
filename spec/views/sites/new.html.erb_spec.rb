require File.expand_path(File.dirname(__FILE__) + '/../../spec_helper')

describe "/sites/new.html.erb" do
  include SitesHelper
  
  before(:each) do
    assigns[:site] = stub_model(Site,
      :new_record? => true,
      :url => "value for url"
    )
  end

  it "renders new site form" do
    render
    
    response.should have_tag("form[action=?][method=post]", sites_path) do
      with_tag("input#site_url[name=?]", "site[url]")
    end
  end
end


