require File.expand_path(File.dirname(__FILE__) + '/../spec_helper')

describe Website do
  before(:each) do
    @valid_attributes = {
      :url => "value for url",
      :datedag => Time.now
    }
  end

  it "should create a new instance given valid attributes" do
    Website.create!(@valid_attributes)
  end
end
