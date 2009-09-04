class KeywordsController < ApplicationController
  # GET /keywords
  # GET /keywords.xml
  def index
    @site     = Site.find_by_host(params[:site_id])
    @keywords = @site.keywords

    respond_to do |format|
      format.html # index.html.erb
      format.xml  { render :xml => @keywords }
    end
  end

  # GET /keywords/new
  # GET /keywords/new.xml
  def new
    @site = Site.find_by_host(params[:site_id])
    @keyword = @site.keywords.new

    respond_to do |format|
      format.html # new.html.erb
      format.xml  { render :xml => @keyword }
    end
  end

  # GET /keywords/1/edit
  def edit
    @keyword = Keyword.find(params[:id])
  end

  # POST /keywords
  # POST /keywords.xml
  def create
    @site = Site.find_by_host(params[:site_id])
    @keyword = @site.keywords.new(params[:keyword])

    respond_to do |format|
      if @keyword.save
        flash[:notice] = 'Keyword was successfully created.'
        format.html { redirect_to(site_keywords_path(@site, @keyword)) }
        format.xml  { render :xml => @keyword, :status => :created, :location => @keyword }
      else
        format.html { render :action => "new" }
        format.xml  { render :xml => @keyword.errors, :status => :unprocessable_entity }
      end
    end
  end

  # PUT /keywords/1
  # PUT /keywords/1.xml
  def update
    @keyword = Keyword.find(params[:id])

    respond_to do |format|
      if @keyword.update_attributes(params[:keyword])
        flash[:notice] = 'Keyword was successfully updated.'
        format.html { redirect_to(@keyword) }
        format.xml  { head :ok }
      else
        format.html { render :action => "edit" }
        format.xml  { render :xml => @keyword.errors, :status => :unprocessable_entity }
      end
    end
  end

  # DELETE /keywords/1
  # DELETE /keywords/1.xml
  def destroy
    @keyword = Keyword.find(params[:id])
    @keyword.destroy

    respond_to do |format|
      format.html { redirect_to(keywords_url) }
      format.xml  { head :ok }
    end
  end
  
  # GET /keywords/1
  # GET /keywords/1.xml
  def show
    require 'cgi'
    @keyword = Keyword.find(params[:id])
    #FIXME group by searchengine_id
    @site    = @keyword.site
    @google_analytics_link = "https://www.google.com/analytics/reporting/keyword_detail?id=3132790&lp=%2Fanalytics%2Freporting%2Fkeywords&d1="+CGI.escape(@keyword.keyword)
    @graph = open_flash_chart_object(600,600, graph_code_site_keyword_path(@site, @keyword))

    respond_to do |format|
      format.html # show.html.erb
      format.xml  { render :xml => @keyword }
    end
  end

  def graph_code
    #FIXME dates do not nessecarirly line up for different searchengines
    #Use scatter charts instead!
    colors = ['#808080','#C0C0C0','#000000','#000080','#0000FF','#008080','#00FFFF','#800080','#800000','#FF0000','#FF00FF','#008000','#00FF00','#808000','#FFFF00']
    i=0

    keyword = Keyword.find(params[:id])
    logs = keyword.searchengine_logs.find(:all, :order => "searchengine_id, updated_at")
    engines = {}
    for log in logs
      engines[log.searchengine] ||= []
      engines[log.searchengine] << log
    end
    #raise logs.inspect
    #return
    logs=nil
    chart = OpenFlashChart.new
    
    #x_labels = XAxisLabels.new
    #x_labels.set_vertical
    #x_labels.labels = logs.map(&:created_at)
    #x = XAxis.new
    #x.set_labels x_labels
    #chart.x_axis = x

    y = YAxis.new
    y.set_range(50,1,1)
    y.set_vertical()
    chart.y_axis = y

    for engine,logs in engines.each_pair
      line = Line.new
      line.set_text engine.to_s
      line.set_values logs.map(&:ranking)
      line.set_colour colors[i%colors.length]
      i+=1
      line.set_tooltip engine.to_s
      chart.add_element(line)
    end
    render :text => chart.to_s
  end
end

