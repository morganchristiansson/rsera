class Report < ActiveRecord::Base
  belongs_to :site
  has_many :reportrules
  has_many :searchengine_logs
  def to_s
    "#{rankdate}"
  end
end
