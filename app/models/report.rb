class Report < ActiveRecord::Base
	belongs_to :website
	has_many :reportrules
	has_many :searchengine_logs
	def to_s
		"#{rankdate}"
	end
end
