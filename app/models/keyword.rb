class Keyword < ActiveRecord::Base
	belongs_to :site
	has_many :searchengine_logs

  validates_associated :site
	def to_param
		"#{id}-#{keyword}"
	end
end
