require File.expand_path(File.dirname(__FILE__) + '/../spec_helper')

describe KeywordsController do
  describe "route generation" do
    it "maps #index" do
      route_for(:controller => "keywords", :action => "index").should == "/keywords"
    end
  
    it "maps #new" do
      route_for(:controller => "keywords", :action => "new").should == "/keywords/new"
    end
  
    it "maps #show" do
      route_for(:controller => "keywords", :action => "show", :id => "1").should == "/keywords/1"
    end
  
    it "maps #edit" do
      route_for(:controller => "keywords", :action => "edit", :id => "1").should == "/keywords/1/edit"
    end

  it "maps #create" do
    route_for(:controller => "keywords", :action => "create").should == {:path => "/keywords", :method => :post}
  end

  it "maps #update" do
    route_for(:controller => "keywords", :action => "update", :id => "1").should == {:path =>"/keywords/1", :method => :put}
  end
  
    it "maps #destroy" do
      route_for(:controller => "keywords", :action => "destroy", :id => "1").should == {:path =>"/keywords/1", :method => :delete}
    end
  end

  describe "route recognition" do
    it "generates params for #index" do
      params_from(:get, "/keywords").should == {:controller => "keywords", :action => "index"}
    end
  
    it "generates params for #new" do
      params_from(:get, "/keywords/new").should == {:controller => "keywords", :action => "new"}
    end
  
    it "generates params for #create" do
      params_from(:post, "/keywords").should == {:controller => "keywords", :action => "create"}
    end
  
    it "generates params for #show" do
      params_from(:get, "/keywords/1").should == {:controller => "keywords", :action => "show", :id => "1"}
    end
  
    it "generates params for #edit" do
      params_from(:get, "/keywords/1/edit").should == {:controller => "keywords", :action => "edit", :id => "1"}
    end
  
    it "generates params for #update" do
      params_from(:put, "/keywords/1").should == {:controller => "keywords", :action => "update", :id => "1"}
    end
  
    it "generates params for #destroy" do
      params_from(:delete, "/keywords/1").should == {:controller => "keywords", :action => "destroy", :id => "1"}
    end
  end
end
