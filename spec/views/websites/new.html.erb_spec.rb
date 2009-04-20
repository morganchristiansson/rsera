require File.expand_path(File.dirname(__FILE__) + '/../../spec_helper')

describe "/websites/new.html.erb" do
  include WebsitesHelper
  
  before(:each) do
    assigns[:website] = stub_model(Website,
      :new_record? => true,
      :url => "value for url"
    )
  end

  it "renders new website form" do
    render
    
    response.should have_tag("form[action=?][method=post]", websites_path) do
      with_tag("input#website_url[name=?]", "website[url]")
    end
  end
end


