require File.expand_path(File.dirname(__FILE__) + '/../spec_helper')

describe Keyword do
  before(:each) do
    @valid_attributes = {
      :keyword => "value for keyword",
      :langcode => "value for langcode",
      :is_active => 1,
      :priority => 1
    }
  end

  it "should create a new instance given valid attributes" do
    Keyword.create!(@valid_attributes)
  end
end
