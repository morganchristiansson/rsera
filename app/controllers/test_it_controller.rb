class TestItController < ApplicationController
  def index
    @graph = open_flash_chart_object(600,600,"/test_it/graph_code?zm_id=#{params[:zm_id]}")
  end

  def graph_code
  	@searchengine = Searchengine.find params[:zm_id]

		#zt_id = keyphrase_id = high priority keyphrases
    #conditions = ["zm_id=? AND zt_id IN(2,3,4,5,11,10,12,13,15,21,28,32,34,35,39,45,51,67,82,83,84,107,108)", @searchengine.id]
    conditions = {"reportrules.zm_id" => params[:zm_id], "keywords.priority" => params[:priority], "reports.ws_id" => params[:ws_id]}
    #FIXME ws_id
		dates = {}
		zt_ids = {}
		rrs = Reportrule.find(:all, :conditions => conditions, :joins => [:report, :keyword], :order => "reports.rankdate")
    rrs.each do |rr|
    	date = rr.report.rankdate
    	dates[date] ||= {}
    	dates[date][rr.keyword.keyword] = rr.ranking > 0 ? rr.ranking : 51
    	zt_ids[rr.keyword.keyword] ||= []
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
			zt_ids.keys.each do |zt_id|
				zt_ids[zt_id] << rankings[zt_id]
			end
    end
    colors = ['#808080','#C0C0C0','#000000','#000080','#0000FF','#008080','#00FFFF','#800080','#800000','#FF0000','#FF00FF','#008000','#00FF00','#808000','#FFFF00']
    i=0
    #zt_ids.keys.map do |zt_id|
	  #  Keyword.find(zt_id).keyword
    #end
    #raise zt_ids.inspect
		zt_ids.sort.each do |zt_id,values|
		  line = Line.new
		  line.set_text zt_id
		  line.set_values values
			line.set_colour colors[i%colors.length]
			line.set_tooltip "#key# #val#"
		  chart.add_element(line)
			i+=1
		end

    render :text => chart.to_s
  end
end

