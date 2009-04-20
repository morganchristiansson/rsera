require File.expand_path(File.dirname(__FILE__) + '/../../spec_helper')

describe "/searchengines/index.html.erb" do
  include SearchenginesHelper
  
  before(:each) do
    assigns[:searchengines] = [
      stub_model(Searchengine,
        :title => "value for title",
        :host => "value for host",
        :query => "value for query",
        :selector => "value for selector"
      ),
      stub_model(Searchengine,
        :title => "value for title",
        :host => "value for host",
        :query => "value for query",
        :selector => "value for selector"
      )
    ]
  end

  it "renders a list of searchengines" do
    render
    response.should have_tag("tr>td", "value for title".to_s, 2)
    response.should have_tag("tr>td", "value for host".to_s, 2)
    response.should have_tag("tr>td", "value for query".to_s, 2)
    response.should have_tag("tr>td", "value for selector".to_s, 2)
  end
end

