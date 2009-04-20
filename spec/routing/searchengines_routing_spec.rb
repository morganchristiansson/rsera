require File.expand_path(File.dirname(__FILE__) + '/../spec_helper')

describe SearchenginesController do
  describe "route generation" do
    it "maps #index" do
      route_for(:controller => "searchengines", :action => "index").should == "/searchengines"
    end
  
    it "maps #new" do
      route_for(:controller => "searchengines", :action => "new").should == "/searchengines/new"
    end
  
    it "maps #show" do
      route_for(:controller => "searchengines", :action => "show", :id => "1").should == "/searchengines/1"
    end
  
    it "maps #edit" do
      route_for(:controller => "searchengines", :action => "edit", :id => "1").should == "/searchengines/1/edit"
    end

  it "maps #create" do
    route_for(:controller => "searchengines", :action => "create").should == {:path => "/searchengines", :method => :post}
  end

  it "maps #update" do
    route_for(:controller => "searchengines", :action => "update", :id => "1").should == {:path =>"/searchengines/1", :method => :put}
  end
  
    it "maps #destroy" do
      route_for(:controller => "searchengines", :action => "destroy", :id => "1").should == {:path =>"/searchengines/1", :method => :delete}
    end
  end

  describe "route recognition" do
    it "generates params for #index" do
      params_from(:get, "/searchengines").should == {:controller => "searchengines", :action => "index"}
    end
  
    it "generates params for #new" do
      params_from(:get, "/searchengines/new").should == {:controller => "searchengines", :action => "new"}
    end
  
    it "generates params for #create" do
      params_from(:post, "/searchengines").should == {:controller => "searchengines", :action => "create"}
    end
  
    it "generates params for #show" do
      params_from(:get, "/searchengines/1").should == {:controller => "searchengines", :action => "show", :id => "1"}
    end
  
    it "generates params for #edit" do
      params_from(:get, "/searchengines/1/edit").should == {:controller => "searchengines", :action => "edit", :id => "1"}
    end
  
    it "generates params for #update" do
      params_from(:put, "/searchengines/1").should == {:controller => "searchengines", :action => "update", :id => "1"}
    end
  
    it "generates params for #destroy" do
      params_from(:delete, "/searchengines/1").should == {:controller => "searchengines", :action => "destroy", :id => "1"}
    end
  end
end
