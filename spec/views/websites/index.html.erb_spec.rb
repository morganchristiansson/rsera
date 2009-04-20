require File.expand_path(File.dirname(__FILE__) + '/../../spec_helper')

describe "/websites/index.html.erb" do
  include WebsitesHelper
  
  before(:each) do
    assigns[:websites] = [
      stub_model(Website,
        :url => "value for url"
      ),
      stub_model(Website,
        :url => "value for url"
      )
    ]
  end

  it "renders a list of websites" do
    render
    response.should have_tag("tr>td", "value for url".to_s, 2)
  end
end

