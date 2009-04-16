require File.expand_path(File.dirname(__FILE__) + '/../../spec_helper')

describe "/keywords/edit.html.erb" do
  include KeywordsHelper
  
  before(:each) do
    assigns[:keyword] = @keyword = stub_model(Keyword,
      :new_record? => false,
      :keyword => "value for keyword",
      :langcode => "value for langcode",
      :is_active => 1,
      :priority => 1
    )
  end

  it "renders the edit keyword form" do
    render
    
    response.should have_tag("form[action=#{keyword_path(@keyword)}][method=post]") do
      with_tag('input#keyword_keyword[name=?]', "keyword[keyword]")
      with_tag('input#keyword_langcode[name=?]', "keyword[langcode]")
      with_tag('input#keyword_is_active[name=?]', "keyword[is_active]")
      with_tag('input#keyword_priority[name=?]', "keyword[priority]")
    end
  end
end


