class Reportrule < ActiveRecord::Base
	set_primary_key "rr_id"
	belongs_to :report, :foreign_key => "mt_id"
	belongs_to :keyword, :foreign_key => "zt_id"
	belongs_to :searchengine, :foreign_key => "zm_id"
	def to_s
		"#{report}\t#{searchengine}\t#{keyword}\t#{ranking}"
	end
end
