require File.expand_path(File.dirname(__FILE__) + '/../../spec_helper')

describe "/reports/new.html.erb" do
  include ReportsHelper
  
  before(:each) do
    assigns[:report] = stub_model(Report,
      :new_record? => true
    )
  end

  it "renders new report form" do
    render
    
    response.should have_tag("form[action=?][method=post]", reports_path) do
    end
  end
end


