require File.expand_path(File.dirname(__FILE__) + '/../spec_helper')

describe ReportsController do

  def mock_report(stubs={})
    @mock_report ||= mock_model(Report, stubs)
  end
  
  describe "GET index" do

    it "exposes all reports as @reports" do
      Report.should_receive(:find).with(:all).and_return([mock_report])
      get :index
      assigns[:reports].should == [mock_report]
    end

    describe "with mime type of xml" do
  
      it "renders all reports as xml" do
        Report.should_receive(:find).with(:all).and_return(reports = mock("Array of Reports"))
        reports.should_receive(:to_xml).and_return("generated XML")
        get :index, :format => 'xml'
        response.body.should == "generated XML"
      end
    
    end

  end

  describe "GET show" do

    it "exposes the requested report as @report" do
      Report.should_receive(:find).with("37").and_return(mock_report)
      get :show, :id => "37"
      assigns[:report].should equal(mock_report)
    end
    
    describe "with mime type of xml" do

      it "renders the requested report as xml" do
        Report.should_receive(:find).with("37").and_return(mock_report)
        mock_report.should_receive(:to_xml).and_return("generated XML")
        get :show, :id => "37", :format => 'xml'
        response.body.should == "generated XML"
      end

    end
    
  end

  describe "GET new" do
  
    it "exposes a new report as @report" do
      Report.should_receive(:new).and_return(mock_report)
      get :new
      assigns[:report].should equal(mock_report)
    end

  end

  describe "GET edit" do
  
    it "exposes the requested report as @report" do
      Report.should_receive(:find).with("37").and_return(mock_report)
      get :edit, :id => "37"
      assigns[:report].should equal(mock_report)
    end

  end

  describe "POST create" do

    describe "with valid params" do
      
      it "exposes a newly created report as @report" do
        Report.should_receive(:new).with({'these' => 'params'}).and_return(mock_report(:save => true))
        post :create, :report => {:these => 'params'}
        assigns(:report).should equal(mock_report)
      end

      it "redirects to the created report" do
        Report.stub!(:new).and_return(mock_report(:save => true))
        post :create, :report => {}
        response.should redirect_to(report_url(mock_report))
      end
      
    end
    
    describe "with invalid params" do

      it "exposes a newly created but unsaved report as @report" do
        Report.stub!(:new).with({'these' => 'params'}).and_return(mock_report(:save => false))
        post :create, :report => {:these => 'params'}
        assigns(:report).should equal(mock_report)
      end

      it "re-renders the 'new' template" do
        Report.stub!(:new).and_return(mock_report(:save => false))
        post :create, :report => {}
        response.should render_template('new')
      end
      
    end
    
  end

  describe "PUT udpate" do

    describe "with valid params" do

      it "updates the requested report" do
        Report.should_receive(:find).with("37").and_return(mock_report)
        mock_report.should_receive(:update_attributes).with({'these' => 'params'})
        put :update, :id => "37", :report => {:these => 'params'}
      end

      it "exposes the requested report as @report" do
        Report.stub!(:find).and_return(mock_report(:update_attributes => true))
        put :update, :id => "1"
        assigns(:report).should equal(mock_report)
      end

      it "redirects to the report" do
        Report.stub!(:find).and_return(mock_report(:update_attributes => true))
        put :update, :id => "1"
        response.should redirect_to(report_url(mock_report))
      end

    end
    
    describe "with invalid params" do

      it "updates the requested report" do
        Report.should_receive(:find).with("37").and_return(mock_report)
        mock_report.should_receive(:update_attributes).with({'these' => 'params'})
        put :update, :id => "37", :report => {:these => 'params'}
      end

      it "exposes the report as @report" do
        Report.stub!(:find).and_return(mock_report(:update_attributes => false))
        put :update, :id => "1"
        assigns(:report).should equal(mock_report)
      end

      it "re-renders the 'edit' template" do
        Report.stub!(:find).and_return(mock_report(:update_attributes => false))
        put :update, :id => "1"
        response.should render_template('edit')
      end

    end

  end

  describe "DELETE destroy" do

    it "destroys the requested report" do
      Report.should_receive(:find).with("37").and_return(mock_report)
      mock_report.should_receive(:destroy)
      delete :destroy, :id => "37"
    end
  
    it "redirects to the reports list" do
      Report.stub!(:find).and_return(mock_report(:destroy => true))
      delete :destroy, :id => "1"
      response.should redirect_to(reports_url)
    end

  end

end
