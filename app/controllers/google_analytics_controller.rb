class GoogleAnalyticsController < ApplicationController

  require 'gattica'
  gs = Gattica.new('morgan.christiansson@gmail.com', 'icerju', 3132790)
  results = gs.get({:start_date => '2009-03-25',
                    :end_date => '2009-04-25',
                    :dimensions => ['keyword'],
                    :metrics => ['visits','transactions','transactionRevenue'],
                    :sort => ['-transactions'],
                    :filters => 'ga:transactions>0;ga:keyword!=(not set)'
                    })

  keywords = results.to_h["points"].map{|p|p.title[/^ga:keyword=(.*)$/,1]}
end

