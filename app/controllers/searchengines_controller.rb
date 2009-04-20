class SearchenginesController < ApplicationController
  # GET /searchengines
  # GET /searchengines.xml
  def index
    @searchengines = Searchengine.all

    respond_to do |format|
      format.html # index.html.erb
      format.xml  { render :xml => @searchengines }
    end
  end

  # GET /searchengines/1
  # GET /searchengines/1.xml
  def show
    @searchengine = Searchengine.find(params[:id])

    respond_to do |format|
      format.html # show.html.erb
      format.xml  { render :xml => @searchengine }
    end
  end

  # GET /searchengines/new
  # GET /searchengines/new.xml
  def new
    @searchengine = Searchengine.new

    respond_to do |format|
      format.html # new.html.erb
      format.xml  { render :xml => @searchengine }
    end
  end

  # GET /searchengines/1/edit
  def edit
    @searchengine = Searchengine.find(params[:id])
  end

  # POST /searchengines
  # POST /searchengines.xml
  def create
    @searchengine = Searchengine.new(params[:searchengine])

    respond_to do |format|
      if @searchengine.save
        flash[:notice] = 'Searchengine was successfully created.'
        format.html { redirect_to(@searchengine) }
        format.xml  { render :xml => @searchengine, :status => :created, :location => @searchengine }
      else
        format.html { render :action => "new" }
        format.xml  { render :xml => @searchengine.errors, :status => :unprocessable_entity }
      end
    end
  end

  # PUT /searchengines/1
  # PUT /searchengines/1.xml
  def update
    @searchengine = Searchengine.find(params[:id])

    respond_to do |format|
      if @searchengine.update_attributes(params[:searchengine])
        flash[:notice] = 'Searchengine was successfully updated.'
        format.html { redirect_to(@searchengine) }
        format.xml  { head :ok }
      else
        format.html { render :action => "edit" }
        format.xml  { render :xml => @searchengine.errors, :status => :unprocessable_entity }
      end
    end
  end

  # DELETE /searchengines/1
  # DELETE /searchengines/1.xml
  def destroy
    @searchengine = Searchengine.find(params[:id])
    @searchengine.destroy

    respond_to do |format|
      format.html { redirect_to(searchengines_url) }
      format.xml  { head :ok }
    end
  end
end
