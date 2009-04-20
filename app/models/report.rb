class Report < ActiveRecord::Base
	belongs_to :website
	has_many :reportrules
	def to_s
		"#{rankdate}"
	end
end
