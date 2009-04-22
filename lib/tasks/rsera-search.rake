#!/usr/bin/ruby
require 'config/environment.rb'
require 'hpricot'
require 'open-uri'
require 'cgi'

def do_search s, keyword
  url = s.query.gsub /\[__KEYPHRASE__\]/, CGI.escape(keyword.keyword)
  puts "Searching #{s.title} for: #{keyword.keyword} using selector: #{s.selector}"
  puts url

  doc=nil
  html=nil
  open(url) do |f|
    html=f.readlines.join ''
  end
  log=s.logs.create(:contents => html, :keyword_id => keyword.id)
  puts "Logged result as #{log.id}"
  doc=Hpricot(html)
  html=nil

  results = doc.search(s.selector)
  puts "Found #{results.length} results"

  pos = 0
  results.each do |result|
    pos += 1
    if result.to_plain_text.include? keyword.site.url
      puts "HIT at position #{pos} for keyword #{keyword.site.url}!"
      @report.reportrules.create(:keyword_id => keyword.id,
                                 :searchengine_id => s.id,
                                 :ranking => pos,
                                 :indexed_page => result.to_plain_text)
      break
    end
  end
  #puts results.inspect
end

namespace :rsera do
  desc "Refresh SERP data"
  task:refresh do
    for site in Site.find :all
      @report = Report.create :site_id => site.id
      puts "Created report #{@report.inspect}"
      if ENV.include?('SE')
        ss = [Searchengine.find ENV['SE'].to_i]
      else
        ss = Searchengine.find :all, :conditions => "active >0"
      end
      for s in ss
        for keyword in site.keywords
          do_search s, keyword
          sleep 0.5
        end
      end #Searchengine.find
    end #Keyword.find_each
    puts "Finished #{@report.inspect}"
  end #task
end

