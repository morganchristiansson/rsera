class Searchengine < ActiveRecord::Base
  has_many :logs, :class_name => "SearchengineLog"
	def to_s
		title
	end
end
