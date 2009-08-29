class Site < ActiveRecord::Base
  has_many :reports
  has_many :keywords
  validates_presence_of :host
  def to_param
    host
  end
end

