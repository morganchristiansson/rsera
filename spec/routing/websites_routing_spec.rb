require File.expand_path(File.dirname(__FILE__) + '/../spec_helper')

describe WebsitesController do
  describe "route generation" do
    it "maps #index" do
      route_for(:controller => "websites", :action => "index").should == "/websites"
    end
  
    it "maps #new" do
      route_for(:controller => "websites", :action => "new").should == "/websites/new"
    end
  
    it "maps #show" do
      route_for(:controller => "websites", :action => "show", :id => "1").should == "/websites/1"
    end
  
    it "maps #edit" do
      route_for(:controller => "websites", :action => "edit", :id => "1").should == "/websites/1/edit"
    end

  it "maps #create" do
    route_for(:controller => "websites", :action => "create").should == {:path => "/websites", :method => :post}
  end

  it "maps #update" do
    route_for(:controller => "websites", :action => "update", :id => "1").should == {:path =>"/websites/1", :method => :put}
  end
  
    it "maps #destroy" do
      route_for(:controller => "websites", :action => "destroy", :id => "1").should == {:path =>"/websites/1", :method => :delete}
    end
  end

  describe "route recognition" do
    it "generates params for #index" do
      params_from(:get, "/websites").should == {:controller => "websites", :action => "index"}
    end
  
    it "generates params for #new" do
      params_from(:get, "/websites/new").should == {:controller => "websites", :action => "new"}
    end
  
    it "generates params for #create" do
      params_from(:post, "/websites").should == {:controller => "websites", :action => "create"}
    end
  
    it "generates params for #show" do
      params_from(:get, "/websites/1").should == {:controller => "websites", :action => "show", :id => "1"}
    end
  
    it "generates params for #edit" do
      params_from(:get, "/websites/1/edit").should == {:controller => "websites", :action => "edit", :id => "1"}
    end
  
    it "generates params for #update" do
      params_from(:put, "/websites/1").should == {:controller => "websites", :action => "update", :id => "1"}
    end
  
    it "generates params for #destroy" do
      params_from(:delete, "/websites/1").should == {:controller => "websites", :action => "destroy", :id => "1"}
    end
  end
end
