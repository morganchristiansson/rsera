require File.expand_path(File.dirname(__FILE__) + '/../../spec_helper')

describe "/reports/edit.html.erb" do
  include ReportsHelper
  
  before(:each) do
    assigns[:report] = @report = stub_model(Report,
      :new_record? => false
    )
  end

  it "renders the edit report form" do
    render
    
    response.should have_tag("form[action=#{report_path(@report)}][method=post]") do
    end
  end
end


