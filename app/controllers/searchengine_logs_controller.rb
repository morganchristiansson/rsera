class SearchengineLogsController < ApplicationController
  # GET /searchengine_logs
  # GET /searchengine_logs.xml
  def index
    @searchengine_logs = SearchengineLog.all

    respond_to do |format|
      format.html # index.html.erb
      format.xml  { render :xml => @searchengine_logs }
    end
  end

  # GET /searchengine_logs/1
  # GET /searchengine_logs/1.xml
  def show
    @searchengine_log = SearchengineLog.find(params[:id])

    respond_to do |format|
      format.html # show.html.erb
      format.xml  { render :xml => @searchengine_log }
    end
  end

  # GET /searchengine_logs/new
  # GET /searchengine_logs/new.xml
  def new
    @searchengine_log = SearchengineLog.new

    respond_to do |format|
      format.html # new.html.erb
      format.xml  { render :xml => @searchengine_log }
    end
  end

  # GET /searchengine_logs/1/edit
  def edit
    @searchengine_log = SearchengineLog.find(params[:id])
  end

  # POST /searchengine_logs
  # POST /searchengine_logs.xml
  def create
    @searchengine_log = SearchengineLog.new(params[:searchengine_log])

    respond_to do |format|
      if @searchengine_log.save
        flash[:notice] = 'SearchengineLog was successfully created.'
        format.html { redirect_to(@searchengine_log) }
        format.xml  { render :xml => @searchengine_log, :status => :created, :location => @searchengine_log }
      else
        format.html { render :action => "new" }
        format.xml  { render :xml => @searchengine_log.errors, :status => :unprocessable_entity }
      end
    end
  end

  # PUT /searchengine_logs/1
  # PUT /searchengine_logs/1.xml
  def update
    @searchengine_log = SearchengineLog.find(params[:id])

    respond_to do |format|
      if @searchengine_log.update_attributes(params[:searchengine_log])
        flash[:notice] = 'SearchengineLog was successfully updated.'
        format.html { redirect_to(@searchengine_log) }
        format.xml  { head :ok }
      else
        format.html { render :action => "edit" }
        format.xml  { render :xml => @searchengine_log.errors, :status => :unprocessable_entity }
      end
    end
  end

  # DELETE /searchengine_logs/1
  # DELETE /searchengine_logs/1.xml
  def destroy
    @searchengine_log = SearchengineLog.find(params[:id])
    @searchengine_log.destroy

    respond_to do |format|
      format.html { redirect_to(searchengine_logs_url) }
      format.xml  { head :ok }
    end
  end
  
  def contents
    @searchengine_log = SearchengineLog.find(params[:id])
    raise ActiveRecord::RecordNotFound if @searchengine_log.contents.nil?
    render :text => @searchengine_log.contents.gsub(/<head>/,"<head><base href=\"#{@searchengine_log.searchengine.url}\">")
  end
end
