require File.expand_path(File.dirname(__FILE__) + '/../spec_helper')

describe Report do
  before(:each) do
    @valid_attributes = {
      :rankdate => Time.now
    }
  end

  it "should create a new instance given valid attributes" do
    Report.create!(@valid_attributes)
  end
end