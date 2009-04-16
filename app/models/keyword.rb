class Keyword < ActiveRecord::Base
	belongs_to :website, :foreign_key => "ws_id"
	has_many :reportrules, :foreign_key => "zt_id"
	def to_s
		"#{id}-#{keyphrase}"
	end
end
