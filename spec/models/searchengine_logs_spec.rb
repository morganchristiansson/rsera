require File.expand_path(File.dirname(__FILE__) + '/../spec_helper')

describe SearchengineLogs do
  before(:each) do
    @valid_attributes = {
      :searchengine_id => 1,
      :keyword_id => 1,
      :contents => 
    }
  end

  it "should create a new instance given valid attributes" do
    SearchengineLogs.create!(@valid_attributes)
  end
end
