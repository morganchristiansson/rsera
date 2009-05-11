class Keyword < ActiveRecord::Base
	belongs_to :site
	has_many :searchengine_logs
	def to_s
		"#{id}-#{keypword}"
	end
end
