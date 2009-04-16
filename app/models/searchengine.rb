class Searchengine < ActiveRecord::Base
	set_primary_key "zm_id"
	has_many :reportrules
	def to_s
		title
	end
end
