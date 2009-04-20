require File.expand_path(File.dirname(__FILE__) + '/../../spec_helper')

describe "/websites/edit.html.erb" do
  include WebsitesHelper
  
  before(:each) do
    assigns[:website] = @website = stub_model(Website,
      :new_record? => false,
      :url => "value for url"
    )
  end

  it "renders the edit website form" do
    render
    
    response.should have_tag("form[action=#{website_path(@website)}][method=post]") do
      with_tag('input#website_url[name=?]', "website[url]")
    end
  end
end


