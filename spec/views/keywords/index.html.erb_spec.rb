require File.expand_path(File.dirname(__FILE__) + '/../../spec_helper')

describe "/keywords/index.html.erb" do
  include KeywordsHelper
  
  before(:each) do
    assigns[:keywords] = [
      stub_model(Keyword,
        :keyword => "value for keyword",
        :langcode => "value for langcode",
        :is_active => 1,
        :priority => 1
      ),
      stub_model(Keyword,
        :keyword => "value for keyword",
        :langcode => "value for langcode",
        :is_active => 1,
        :priority => 1
      )
    ]
  end

  it "renders a list of keywords" do
    render
    response.should have_tag("tr>td", "value for keyword".to_s, 2)
    response.should have_tag("tr>td", "value for langcode".to_s, 2)
    response.should have_tag("tr>td", 1.to_s, 2)
    response.should have_tag("tr>td", 1.to_s, 2)
  end
end

