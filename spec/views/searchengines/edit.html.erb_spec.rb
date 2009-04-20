require File.expand_path(File.dirname(__FILE__) + '/../../spec_helper')

describe "/searchengines/edit.html.erb" do
  include SearchenginesHelper
  
  before(:each) do
    assigns[:searchengine] = @searchengine = stub_model(Searchengine,
      :new_record? => false,
      :title => "value for title",
      :host => "value for host",
      :query => "value for query",
      :selector => "value for selector"
    )
  end

  it "renders the edit searchengine form" do
    render
    
    response.should have_tag("form[action=#{searchengine_path(@searchengine)}][method=post]") do
      with_tag('input#searchengine_title[name=?]', "searchengine[title]")
      with_tag('input#searchengine_host[name=?]', "searchengine[host]")
      with_tag('input#searchengine_query[name=?]', "searchengine[query]")
      with_tag('input#searchengine_selector[name=?]', "searchengine[selector]")
    end
  end
end


