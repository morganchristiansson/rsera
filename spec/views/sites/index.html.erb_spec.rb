require File.expand_path(File.dirname(__FILE__) + '/../../spec_helper')

describe "/sites/index.html.erb" do
  include SitesHelper
  
  before(:each) do
    assigns[:sites] = [
      stub_model(Site,
        :url => "value for url"
      ),
      stub_model(Site,
        :url => "value for url"
      )
    ]
  end

  it "renders a list of sites" do
    render
    response.should have_tag("tr>td", "value for url".to_s, 2)
  end
end

