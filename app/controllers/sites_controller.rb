class SitesController < ApplicationController
  # GET /sites
  # GET /sites.xml
  def index
    @sites = Site.all

    respond_to do |format|
      format.html # index.html.erb
      format.xml  { render :xml => @sites }
    end
  end

  # GET /sites/1
  # GET /sites/1.xml
  def show
    @site = Site.find_by_host(params[:id])
    respond_to do |format|
      format.html # show.html.erb
      format.xml  { render :xml => @site }
    end
  end

  # GET /sites/new
  # GET /sites/new.xml
  def new
    @site = Site.new
    respond_to do |format|
      format.html # new.html.erb
      format.xml  { render :xml => @site }
    end
  end

  # GET /sites/1/edit
  def edit
    @site = Site.find_by_host(params[:id])
  end

  # POST /sites
  # POST /sites.xml
  def create
    @site = Site.new(params[:site])
    respond_to do |format|
      if @site.save
        flash[:notice] = 'Site was successfully created.'
        format.html { redirect_to(@site) }
        format.xml  { render :xml => @site, :status => :created, :location => @site }
      else
        format.html { render :action => "new" }
        format.xml  { render :xml => @site.errors, :status => :unprocessable_entity }
      end
    end
  end

  # PUT /sites/1
  # PUT /sites/1.xml
  def update
    @site = Site.find_by_host(params[:id])
    respond_to do |format|
      if @site.update_attributes(params[:site])
        flash[:notice] = 'Site was successfully updated.'
        format.html { redirect_to(@site) }
        format.xml  { head :ok }
      else
        format.html { render :action => "edit" }
        format.xml  { render :xml => @site.errors, :status => :unprocessable_entity }
      end
    end
  end

  # DELETE /sites/1
  # DELETE /sites/1.xml
  def destroy
    @site = Site.find_by_host(params[:id])
    @site.destroy
    respond_to do |format|
      format.html { redirect_to(sites_url) }
      format.xml  { head :ok }
    end
  end
  
  def trends
    @site = Site.find_by_host(params[:id])
    @graph = params[:searchengine_id] && open_flash_chart_object(600,600, graph_code_site_url(@site, :searchengine_id => params[:searchengine_id]))
    respond_to do |format|
      format.html # show.html.erb
      format.xml  { render :xml => @site }
    end
  end

  def graph_code
    @site = Site.find_by_host params[:id]
    @searchengine = Searchengine.find params[:searchengine_id]

    chart = OpenFlashChart.new
    title = Title.new("SERP Report for #{@searchengine}")
    chart.set_title(title)

    params[:priority] ||= 1
    conditions = ["searchengine_logs.searchengine_id = ? AND reports.site_id = ? AND ranking IS NOT NULL", params[:searchengine_id], @site.id]
    dates = {}
    keyword_ids = {}
    rrs = SearchengineLog.find(:all, :conditions => conditions, :joins => [:report, :keyword], :order => "searchengine_logs.created_at")
    rrs.each do |rr|
      date = rr.report.created_at
      dates[date] ||= {}
      dates[date][rr.keyword.keyword] = rr.ranking > 0 ? rr.ranking : 51
      keyword_ids[rr.keyword.keyword] ||= []
    end
    x_labels = XAxisLabels.new
    x_labels.set_vertical
    x_labels.labels = dates.keys.sort
    x = XAxis.new
    x.set_labels(x_labels)
    chart.x_axis = x

    y = YAxis.new
    y.set_range(50,1,1)
    y.set_vertical()
    chart.y_axis = y

    dates.sort.each do |date,rankings|
    keyword_ids.keys.each do |keyword_id|
      keyword_ids[keyword_id] << rankings[keyword_id]
    end
    end
    colors = ['#808080','#C0C0C0','#000000','#000080','#0000FF','#008080','#00FFFF','#800080','#800000','#FF0000','#FF00FF','#008000','#00FF00','#808000','#FFFF00']
    i=0

    keyword_ids.sort.each do |keyword_id,values|
      line = Line.new
      line.set_text keyword_id
      line.set_values values
      line.set_colour colors[i%colors.length]
      i+=1
      line.set_tooltip "#key# #val#"
      chart.add_element(line)
    end
    render :text => chart.to_s
  end

  def analytics
    @site = Site.find_by_host params[:id]
    keywords = @site.analytics_keywords
    keywords.each do |keyword|
      @site.keywords.find_or_create_by_keyword keyword
    end
    flash[:notice] = "Found #{keywords.length} new and/or existing keywords from google analytics"
  end
end

