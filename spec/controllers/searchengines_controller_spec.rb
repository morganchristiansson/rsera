require File.expand_path(File.dirname(__FILE__) + '/../spec_helper')

describe SearchenginesController do

  def mock_searchengine(stubs={})
    @mock_searchengine ||= mock_model(Searchengine, stubs)
  end
  
  describe "GET index" do

    it "exposes all searchengines as @searchengines" do
      Searchengine.should_receive(:find).with(:all).and_return([mock_searchengine])
      get :index
      assigns[:searchengines].should == [mock_searchengine]
    end

    describe "with mime type of xml" do
  
      it "renders all searchengines as xml" do
        Searchengine.should_receive(:find).with(:all).and_return(searchengines = mock("Array of Searchengines"))
        searchengines.should_receive(:to_xml).and_return("generated XML")
        get :index, :format => 'xml'
        response.body.should == "generated XML"
      end
    
    end

  end

  describe "GET show" do

    it "exposes the requested searchengine as @searchengine" do
      Searchengine.should_receive(:find).with("37").and_return(mock_searchengine)
      get :show, :id => "37"
      assigns[:searchengine].should equal(mock_searchengine)
    end
    
    describe "with mime type of xml" do

      it "renders the requested searchengine as xml" do
        Searchengine.should_receive(:find).with("37").and_return(mock_searchengine)
        mock_searchengine.should_receive(:to_xml).and_return("generated XML")
        get :show, :id => "37", :format => 'xml'
        response.body.should == "generated XML"
      end

    end
    
  end

  describe "GET new" do
  
    it "exposes a new searchengine as @searchengine" do
      Searchengine.should_receive(:new).and_return(mock_searchengine)
      get :new
      assigns[:searchengine].should equal(mock_searchengine)
    end

  end

  describe "GET edit" do
  
    it "exposes the requested searchengine as @searchengine" do
      Searchengine.should_receive(:find).with("37").and_return(mock_searchengine)
      get :edit, :id => "37"
      assigns[:searchengine].should equal(mock_searchengine)
    end

  end

  describe "POST create" do

    describe "with valid params" do
      
      it "exposes a newly created searchengine as @searchengine" do
        Searchengine.should_receive(:new).with({'these' => 'params'}).and_return(mock_searchengine(:save => true))
        post :create, :searchengine => {:these => 'params'}
        assigns(:searchengine).should equal(mock_searchengine)
      end

      it "redirects to the created searchengine" do
        Searchengine.stub!(:new).and_return(mock_searchengine(:save => true))
        post :create, :searchengine => {}
        response.should redirect_to(searchengine_url(mock_searchengine))
      end
      
    end
    
    describe "with invalid params" do

      it "exposes a newly created but unsaved searchengine as @searchengine" do
        Searchengine.stub!(:new).with({'these' => 'params'}).and_return(mock_searchengine(:save => false))
        post :create, :searchengine => {:these => 'params'}
        assigns(:searchengine).should equal(mock_searchengine)
      end

      it "re-renders the 'new' template" do
        Searchengine.stub!(:new).and_return(mock_searchengine(:save => false))
        post :create, :searchengine => {}
        response.should render_template('new')
      end
      
    end
    
  end

  describe "PUT udpate" do

    describe "with valid params" do

      it "updates the requested searchengine" do
        Searchengine.should_receive(:find).with("37").and_return(mock_searchengine)
        mock_searchengine.should_receive(:update_attributes).with({'these' => 'params'})
        put :update, :id => "37", :searchengine => {:these => 'params'}
      end

      it "exposes the requested searchengine as @searchengine" do
        Searchengine.stub!(:find).and_return(mock_searchengine(:update_attributes => true))
        put :update, :id => "1"
        assigns(:searchengine).should equal(mock_searchengine)
      end

      it "redirects to the searchengine" do
        Searchengine.stub!(:find).and_return(mock_searchengine(:update_attributes => true))
        put :update, :id => "1"
        response.should redirect_to(searchengine_url(mock_searchengine))
      end

    end
    
    describe "with invalid params" do

      it "updates the requested searchengine" do
        Searchengine.should_receive(:find).with("37").and_return(mock_searchengine)
        mock_searchengine.should_receive(:update_attributes).with({'these' => 'params'})
        put :update, :id => "37", :searchengine => {:these => 'params'}
      end

      it "exposes the searchengine as @searchengine" do
        Searchengine.stub!(:find).and_return(mock_searchengine(:update_attributes => false))
        put :update, :id => "1"
        assigns(:searchengine).should equal(mock_searchengine)
      end

      it "re-renders the 'edit' template" do
        Searchengine.stub!(:find).and_return(mock_searchengine(:update_attributes => false))
        put :update, :id => "1"
        response.should render_template('edit')
      end

    end

  end

  describe "DELETE destroy" do

    it "destroys the requested searchengine" do
      Searchengine.should_receive(:find).with("37").and_return(mock_searchengine)
      mock_searchengine.should_receive(:destroy)
      delete :destroy, :id => "37"
    end
  
    it "redirects to the searchengines list" do
      Searchengine.stub!(:find).and_return(mock_searchengine(:destroy => true))
      delete :destroy, :id => "1"
      response.should redirect_to(searchengines_url)
    end

  end

end
