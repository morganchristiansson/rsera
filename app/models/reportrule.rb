class Reportrule < ActiveRecord::Base
	set_primary_key "rr_id"
	belongs_to :report
	belongs_to :keyword
	belongs_to :searchengine
	def to_s
		"#{report}\t#{searchengine}\t#{keyword}\t#{ranking}"
	end
end
