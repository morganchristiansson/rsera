require File.expand_path(File.dirname(__FILE__) + '/../../spec_helper')

describe "/reports/show.html.erb" do
  include ReportsHelper
  before(:each) do
    assigns[:report] = @report = stub_model(Report)
  end

  it "renders attributes in <p>" do
    render
  end
end

