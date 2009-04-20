require File.expand_path(File.dirname(__FILE__) + '/../../spec_helper')

describe "/searchengines/show.html.erb" do
  include SearchenginesHelper
  before(:each) do
    assigns[:searchengine] = @searchengine = stub_model(Searchengine,
      :title => "value for title",
      :host => "value for host",
      :query => "value for query",
      :selector => "value for selector"
    )
  end

  it "renders attributes in <p>" do
    render
    response.should have_text(/value\ for\ title/)
    response.should have_text(/value\ for\ host/)
    response.should have_text(/value\ for\ query/)
    response.should have_text(/value\ for\ selector/)
  end
end

