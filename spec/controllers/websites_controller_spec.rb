require File.expand_path(File.dirname(__FILE__) + '/../spec_helper')

describe WebsitesController do

  def mock_website(stubs={})
    @mock_website ||= mock_model(Website, stubs)
  end
  
  describe "GET index" do

    it "exposes all websites as @websites" do
      Website.should_receive(:find).with(:all).and_return([mock_website])
      get :index
      assigns[:websites].should == [mock_website]
    end

    describe "with mime type of xml" do
  
      it "renders all websites as xml" do
        Website.should_receive(:find).with(:all).and_return(websites = mock("Array of Websites"))
        websites.should_receive(:to_xml).and_return("generated XML")
        get :index, :format => 'xml'
        response.body.should == "generated XML"
      end
    
    end

  end

  describe "GET show" do

    it "exposes the requested website as @website" do
      Website.should_receive(:find).with("37").and_return(mock_website)
      get :show, :id => "37"
      assigns[:website].should equal(mock_website)
    end
    
    describe "with mime type of xml" do

      it "renders the requested website as xml" do
        Website.should_receive(:find).with("37").and_return(mock_website)
        mock_website.should_receive(:to_xml).and_return("generated XML")
        get :show, :id => "37", :format => 'xml'
        response.body.should == "generated XML"
      end

    end
    
  end

  describe "GET new" do
  
    it "exposes a new website as @website" do
      Website.should_receive(:new).and_return(mock_website)
      get :new
      assigns[:website].should equal(mock_website)
    end

  end

  describe "GET edit" do
  
    it "exposes the requested website as @website" do
      Website.should_receive(:find).with("37").and_return(mock_website)
      get :edit, :id => "37"
      assigns[:website].should equal(mock_website)
    end

  end

  describe "POST create" do

    describe "with valid params" do
      
      it "exposes a newly created website as @website" do
        Website.should_receive(:new).with({'these' => 'params'}).and_return(mock_website(:save => true))
        post :create, :website => {:these => 'params'}
        assigns(:website).should equal(mock_website)
      end

      it "redirects to the created website" do
        Website.stub!(:new).and_return(mock_website(:save => true))
        post :create, :website => {}
        response.should redirect_to(website_url(mock_website))
      end
      
    end
    
    describe "with invalid params" do

      it "exposes a newly created but unsaved website as @website" do
        Website.stub!(:new).with({'these' => 'params'}).and_return(mock_website(:save => false))
        post :create, :website => {:these => 'params'}
        assigns(:website).should equal(mock_website)
      end

      it "re-renders the 'new' template" do
        Website.stub!(:new).and_return(mock_website(:save => false))
        post :create, :website => {}
        response.should render_template('new')
      end
      
    end
    
  end

  describe "PUT udpate" do

    describe "with valid params" do

      it "updates the requested website" do
        Website.should_receive(:find).with("37").and_return(mock_website)
        mock_website.should_receive(:update_attributes).with({'these' => 'params'})
        put :update, :id => "37", :website => {:these => 'params'}
      end

      it "exposes the requested website as @website" do
        Website.stub!(:find).and_return(mock_website(:update_attributes => true))
        put :update, :id => "1"
        assigns(:website).should equal(mock_website)
      end

      it "redirects to the website" do
        Website.stub!(:find).and_return(mock_website(:update_attributes => true))
        put :update, :id => "1"
        response.should redirect_to(website_url(mock_website))
      end

    end
    
    describe "with invalid params" do

      it "updates the requested website" do
        Website.should_receive(:find).with("37").and_return(mock_website)
        mock_website.should_receive(:update_attributes).with({'these' => 'params'})
        put :update, :id => "37", :website => {:these => 'params'}
      end

      it "exposes the website as @website" do
        Website.stub!(:find).and_return(mock_website(:update_attributes => false))
        put :update, :id => "1"
        assigns(:website).should equal(mock_website)
      end

      it "re-renders the 'edit' template" do
        Website.stub!(:find).and_return(mock_website(:update_attributes => false))
        put :update, :id => "1"
        response.should render_template('edit')
      end

    end

  end

  describe "DELETE destroy" do

    it "destroys the requested website" do
      Website.should_receive(:find).with("37").and_return(mock_website)
      mock_website.should_receive(:destroy)
      delete :destroy, :id => "37"
    end
  
    it "redirects to the websites list" do
      Website.stub!(:find).and_return(mock_website(:destroy => true))
      delete :destroy, :id => "1"
      response.should redirect_to(websites_url)
    end

  end

end
