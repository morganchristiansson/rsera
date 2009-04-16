class Report < ActiveRecord::Base
	set_primary_key "mt_id"
	belongs_to :website, :foreign_key => "ws_id"
	has_many :reportrules, :foreign_key => "rr_id"
	def to_s
		"#{rankdate}"
	end
end
