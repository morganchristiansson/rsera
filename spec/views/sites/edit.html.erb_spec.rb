require File.expand_path(File.dirname(__FILE__) + '/../../spec_helper')

describe "/sites/edit.html.erb" do
  include SitesHelper
  
  before(:each) do
    assigns[:site] = @site = stub_model(Site,
      :new_record? => false,
      :url => "value for url"
    )
  end

  it "renders the edit site form" do
    render
    
    response.should have_tag("form[action=#{site_path(@site)}][method=post]") do
      with_tag('input#site_url[name=?]', "site[url]")
    end
  end
end


