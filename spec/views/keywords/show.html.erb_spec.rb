require File.expand_path(File.dirname(__FILE__) + '/../../spec_helper')

describe "/keywords/show.html.erb" do
  include KeywordsHelper
  before(:each) do
    assigns[:keyword] = @keyword = stub_model(Keyword,
      :keyword => "value for keyword",
      :langcode => "value for langcode",
      :is_active => 1,
      :priority => 1
    )
  end

  it "renders attributes in <p>" do
    render
    response.should have_text(/value\ for\ keyword/)
    response.should have_text(/value\ for\ langcode/)
    response.should have_text(/1/)
    response.should have_text(/1/)
  end
end

