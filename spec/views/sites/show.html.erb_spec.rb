require File.expand_path(File.dirname(__FILE__) + '/../../spec_helper')

describe "/sites/show.html.erb" do
  include SitesHelper
  before(:each) do
    assigns[:site] = @site = stub_model(Site,
      :url => "value for url"
    )
  end

  it "renders attributes in <p>" do
    render
    response.should have_text(/value\ for\ url/)
  end
end

