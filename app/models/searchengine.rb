class Searchengine < ActiveRecord::Base
  has_many :logs, :class_name => "SearchengineLog"
	has_many :reportrules
	def to_s
		title
	end
end
