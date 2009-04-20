class Site < ActiveRecord::Base
  has_many :reports
  has_many :keywords
  def to_param
    url
  end
end
