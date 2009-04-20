require File.expand_path(File.dirname(__FILE__) + '/../spec_helper')

describe ReportsController do
  describe "route generation" do
    it "maps #index" do
      route_for(:controller => "reports", :action => "index").should == "/reports"
    end
  
    it "maps #new" do
      route_for(:controller => "reports", :action => "new").should == "/reports/new"
    end
  
    it "maps #show" do
      route_for(:controller => "reports", :action => "show", :id => "1").should == "/reports/1"
    end
  
    it "maps #edit" do
      route_for(:controller => "reports", :action => "edit", :id => "1").should == "/reports/1/edit"
    end

  it "maps #create" do
    route_for(:controller => "reports", :action => "create").should == {:path => "/reports", :method => :post}
  end

  it "maps #update" do
    route_for(:controller => "reports", :action => "update", :id => "1").should == {:path =>"/reports/1", :method => :put}
  end
  
    it "maps #destroy" do
      route_for(:controller => "reports", :action => "destroy", :id => "1").should == {:path =>"/reports/1", :method => :delete}
    end
  end

  describe "route recognition" do
    it "generates params for #index" do
      params_from(:get, "/reports").should == {:controller => "reports", :action => "index"}
    end
  
    it "generates params for #new" do
      params_from(:get, "/reports/new").should == {:controller => "reports", :action => "new"}
    end
  
    it "generates params for #create" do
      params_from(:post, "/reports").should == {:controller => "reports", :action => "create"}
    end
  
    it "generates params for #show" do
      params_from(:get, "/reports/1").should == {:controller => "reports", :action => "show", :id => "1"}
    end
  
    it "generates params for #edit" do
      params_from(:get, "/reports/1/edit").should == {:controller => "reports", :action => "edit", :id => "1"}
    end
  
    it "generates params for #update" do
      params_from(:put, "/reports/1").should == {:controller => "reports", :action => "update", :id => "1"}
    end
  
    it "generates params for #destroy" do
      params_from(:delete, "/reports/1").should == {:controller => "reports", :action => "destroy", :id => "1"}
    end
  end
end
