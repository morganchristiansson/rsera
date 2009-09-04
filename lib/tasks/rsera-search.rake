#!/usr/bin/ruby
require 'config/environment.rb'
require 'hpricot'
require 'open-uri'
require 'cgi'

def do_search s, keyword
  url = s.query.gsub /\[__KEYPHRASE__\]/, CGI.escape(keyword.keyword)
  puts url

  doc=nil
  html=nil
  open(url) do |f|
    html=f.readlines.join ''
  end
  doc=Hpricot(html)
  #html=nil

  results = doc.search(s.selector)
  puts "#{results.length} results found"

  pos = 0
  found=false
  results.each do |result|
    pos += 1
    if result.to_plain_text.include? keyword.site.host
      puts "HIT at position #{pos} for keyword #{keyword.site.host}!"
      s.logs.create(:keyword_id => keyword.id,
                    :searchengine_id => s.id,
                    :report_id => @report.id,
                    :ranking => pos,
                    :results_count => results.length,
                    :indexed_page => result.to_plain_text,
                    :contents => html)
      found=true
      break
    end
  end
  if !found
    log=s.logs.create(:keyword_id => keyword.id,
                      :searchengine_id => s.id,
                      :report_id => @report.id,
                      :contents => html)
    puts "Logged result as #{log.id}"
  end
  #puts results.inspect
end

namespace :rsera do
  desc "Refresh SERP data"
  task:refresh do
    (ENV['SITE_ID'] ? Site.find(ENV['SITE_ID']) : (ENV['SITE'] ? Site.find_all_by_host(ENV['SITE']) : Site.find(:all))).each do |site|
      @report = Report.create :site_id => site.id
      puts "Created report #{@report.inspect}"
      if ENV.include?('SE')
        ss = [Searchengine.find ENV['SE'].to_i]
      else
        ss = Searchengine.find :all, :conditions => "active >0"
      end
      for s in ss
        conditions = []
        if s.langcode != '@@'
          conditions = ["langcode = ?", s.langcode]
        end
        for keyword in site.keywords.find(:all, :conditions => conditions)
          do_search s, keyword
          sleep 0.5
        end
      end #Searchengine.find
    end #Keyword.find_each
    puts "Finished #{@report.inspect}"
  end #task
end

