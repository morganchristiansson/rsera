class Keyword < ActiveRecord::Base
	belongs_to :site
	has_many :reportrules
	def to_s
		"#{id}-#{keyphrase}"
	end
end
