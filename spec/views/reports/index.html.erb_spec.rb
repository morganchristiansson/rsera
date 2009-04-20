require File.expand_path(File.dirname(__FILE__) + '/../../spec_helper')

describe "/reports/index.html.erb" do
  include ReportsHelper
  
  before(:each) do
    assigns[:reports] = [
      stub_model(Report),
      stub_model(Report)
    ]
  end

  it "renders a list of reports" do
    render
  end
end

