class Site < ActiveRecord::Base
  has_many :reports
  has_many :keywords
  validates_presence_of :host
  def analytics_filter
    self[:analytics_filter].empty? ? nil : self[:analytics_filter]
  end

  def analytics_keywords
    #render :text => "Set your account to one of: "+ga.accounts.map {|a| "#{a.title} - #{a.profile_id}"}.join("\n\n")
    raise unless self.analytics_profile_id
    
    results = gattica.get({:start_date => 1.month.ago.to_date.to_s,
                           :end_date => Date.today.to_s,
                           :dimensions => 'keyword',
                           :metrics => "visits", #['visits','transactions','transactionRevenue'],
                           :sort => "-#{self.analytics_metric}",
                           :filters => [self.analytics_filter, "keyword!=(not set)"].compact.join(';') #'ga:transactions>0'
                           })
    @keywords = results.to_h["points"].map{|p|p.title[/^ga:keyword=(.*)$/,1]}
  end
  def to_param
    host
  end

private
  def gattica
    @ga ||= if not self.analytics_token
      @ga = Gattica.new :email => self.analytics_email, :password => self.analytics_password, :profile_id => self.analytics_profile_id
      self.analytics_token = ga.token
      self.save!
      @ga
    else
      @ga = Gattica.new :token => self.analytics_token, :profile_id => self.analytics_profile_id
    end
  end
end

