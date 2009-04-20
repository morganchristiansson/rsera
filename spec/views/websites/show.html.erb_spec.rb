require File.expand_path(File.dirname(__FILE__) + '/../../spec_helper')

describe "/websites/show.html.erb" do
  include WebsitesHelper
  before(:each) do
    assigns[:website] = @website = stub_model(Website,
      :url => "value for url"
    )
  end

  it "renders attributes in <p>" do
    render
    response.should have_text(/value\ for\ url/)
  end
end

