require File.expand_path(File.dirname(__FILE__) + '/../spec_helper')

describe KeywordsController do

  def mock_keyword(stubs={})
    @mock_keyword ||= mock_model(Keyword, stubs)
  end
  
  describe "GET index" do

    it "exposes all keywords as @keywords" do
      Keyword.should_receive(:find).with(:all).and_return([mock_keyword])
      get :index
      assigns[:keywords].should == [mock_keyword]
    end

    describe "with mime type of xml" do
  
      it "renders all keywords as xml" do
        Keyword.should_receive(:find).with(:all).and_return(keywords = mock("Array of Keywords"))
        keywords.should_receive(:to_xml).and_return("generated XML")
        get :index, :format => 'xml'
        response.body.should == "generated XML"
      end
    
    end

  end

  describe "GET show" do

    it "exposes the requested keyword as @keyword" do
      Keyword.should_receive(:find).with("37").and_return(mock_keyword)
      get :show, :id => "37"
      assigns[:keyword].should equal(mock_keyword)
    end
    
    describe "with mime type of xml" do

      it "renders the requested keyword as xml" do
        Keyword.should_receive(:find).with("37").and_return(mock_keyword)
        mock_keyword.should_receive(:to_xml).and_return("generated XML")
        get :show, :id => "37", :format => 'xml'
        response.body.should == "generated XML"
      end

    end
    
  end

  describe "GET new" do
  
    it "exposes a new keyword as @keyword" do
      Keyword.should_receive(:new).and_return(mock_keyword)
      get :new
      assigns[:keyword].should equal(mock_keyword)
    end

  end

  describe "GET edit" do
  
    it "exposes the requested keyword as @keyword" do
      Keyword.should_receive(:find).with("37").and_return(mock_keyword)
      get :edit, :id => "37"
      assigns[:keyword].should equal(mock_keyword)
    end

  end

  describe "POST create" do

    describe "with valid params" do
      
      it "exposes a newly created keyword as @keyword" do
        Keyword.should_receive(:new).with({'these' => 'params'}).and_return(mock_keyword(:save => true))
        post :create, :keyword => {:these => 'params'}
        assigns(:keyword).should equal(mock_keyword)
      end

      it "redirects to the created keyword" do
        Keyword.stub!(:new).and_return(mock_keyword(:save => true))
        post :create, :keyword => {}
        response.should redirect_to(keyword_url(mock_keyword))
      end
      
    end
    
    describe "with invalid params" do

      it "exposes a newly created but unsaved keyword as @keyword" do
        Keyword.stub!(:new).with({'these' => 'params'}).and_return(mock_keyword(:save => false))
        post :create, :keyword => {:these => 'params'}
        assigns(:keyword).should equal(mock_keyword)
      end

      it "re-renders the 'new' template" do
        Keyword.stub!(:new).and_return(mock_keyword(:save => false))
        post :create, :keyword => {}
        response.should render_template('new')
      end
      
    end
    
  end

  describe "PUT udpate" do

    describe "with valid params" do

      it "updates the requested keyword" do
        Keyword.should_receive(:find).with("37").and_return(mock_keyword)
        mock_keyword.should_receive(:update_attributes).with({'these' => 'params'})
        put :update, :id => "37", :keyword => {:these => 'params'}
      end

      it "exposes the requested keyword as @keyword" do
        Keyword.stub!(:find).and_return(mock_keyword(:update_attributes => true))
        put :update, :id => "1"
        assigns(:keyword).should equal(mock_keyword)
      end

      it "redirects to the keyword" do
        Keyword.stub!(:find).and_return(mock_keyword(:update_attributes => true))
        put :update, :id => "1"
        response.should redirect_to(keyword_url(mock_keyword))
      end

    end
    
    describe "with invalid params" do

      it "updates the requested keyword" do
        Keyword.should_receive(:find).with("37").and_return(mock_keyword)
        mock_keyword.should_receive(:update_attributes).with({'these' => 'params'})
        put :update, :id => "37", :keyword => {:these => 'params'}
      end

      it "exposes the keyword as @keyword" do
        Keyword.stub!(:find).and_return(mock_keyword(:update_attributes => false))
        put :update, :id => "1"
        assigns(:keyword).should equal(mock_keyword)
      end

      it "re-renders the 'edit' template" do
        Keyword.stub!(:find).and_return(mock_keyword(:update_attributes => false))
        put :update, :id => "1"
        response.should render_template('edit')
      end

    end

  end

  describe "DELETE destroy" do

    it "destroys the requested keyword" do
      Keyword.should_receive(:find).with("37").and_return(mock_keyword)
      mock_keyword.should_receive(:destroy)
      delete :destroy, :id => "37"
    end
  
    it "redirects to the keywords list" do
      Keyword.stub!(:find).and_return(mock_keyword(:destroy => true))
      delete :destroy, :id => "1"
      response.should redirect_to(keywords_url)
    end

  end

end
