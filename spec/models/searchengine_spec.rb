require File.expand_path(File.dirname(__FILE__) + '/../spec_helper')

describe Searchengine do
  before(:each) do
    @valid_attributes = {
      :title => "value for title",
      :host => "value for host",
      :query => "value for query",
      :selector => "value for selector"
    }
  end

  it "should create a new instance given valid attributes" do
    Searchengine.create!(@valid_attributes)
  end
end
