class SitesController < ApplicationController
  # GET /websites
  # GET /websites.xml
  def index
    @sites = Site.all

    respond_to do |format|
      format.html # index.html.erb
      format.xml  { render :xml => @sites }
    end
  end

  # GET /websites/1
  # GET /websites/1.xml
  def show
    @site = Site.find_by_host(params[:id])

    respond_to do |format|
      format.html # show.html.erb
      format.xml  { render :xml => @site }
    end
  end

  # GET /websites/new
  # GET /websites/new.xml
  def new
    @site = Site.new

    respond_to do |format|
      format.html # new.html.erb
      format.xml  { render :xml => @site }
    end
  end

  # GET /websites/1/edit
  def edit
    @site = Site.find_by_host(params[:id])
  end

  # POST /websites
  # POST /websites.xml
  def create
    @site = Site.new(params[:website])

    respond_to do |format|
      if @site.save
        flash[:notice] = 'Website was successfully created.'
        format.html { redirect_to(@site) }
        format.xml  { render :xml => @site, :status => :created, :location => @site }
      else
        format.html { render :action => "new" }
        format.xml  { render :xml => @site.errors, :status => :unprocessable_entity }
      end
    end
  end

  # PUT /websites/1
  # PUT /websites/1.xml
  def update
    @site = Site.find_by_host(params[:id])

    respond_to do |format|
      if @site.update_attributes(params[:website])
        flash[:notice] = 'Website was successfully updated.'
        format.html { redirect_to(@site) }
        format.xml  { head :ok }
      else
        format.html { render :action => "edit" }
        format.xml  { render :xml => @site.errors, :status => :unprocessable_entity }
      end
    end
  end

  # DELETE /websites/1
  # DELETE /websites/1.xml
  def destroy
    @site = Site.find_by_host(params[:id])
    @site.destroy

    respond_to do |format|
      format.html { redirect_to(websites_url) }
      format.xml  { head :ok }
    end
  end
  
  def trends
    @site = Site.find_by_host(params[:id])
    @graph = open_flash_chart_object(600,600, url_for(:id => params[:id], :searchengine_id => params[:searchengine_id], :action => "graph_code"))

    respond_to do |format|
      format.html # show.html.erb
      format.xml  { render :xml => @site }
    end
  end

  def graph_code
  	@site = Site.find_by_host params[:id]
  	@searchengine = Searchengine.find params[:searchengine_id]

		#keyword_id = keyphrase_id = high priority keyphrases
    #conditions = ["searchengine_id=? AND keyword_id IN(2,3,4,5,11,10,12,13,15,21,28,32,34,35,39,45,51,67,82,83,84,107,108)", @searchengine.id]
    params[:priority] ||= 1
    conditions = {"searchengine_logs.searchengine_id" => params[:searchengine_id], "keywords.priority" => params[:priority], "reports.site_id" => @site.id}
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
    x_labels.set_vertical()
    #x_labels.labels = tmp
    x_labels.labels = dates.keys.sort
    x = XAxis.new
    x.set_labels(x_labels)


    title = Title.new("SERP Report for #{@searchengine}")
    chart = OpenFlashChart.new
    chart.set_title(title)
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
			line.set_tooltip "#key# #val#"
		  chart.add_element(line)
			i+=1
		end

    render :text => chart.to_s
  end
end

