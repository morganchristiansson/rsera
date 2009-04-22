class SearchengineLog < ActiveRecord::Base
	belongs_to :keyword
	belongs_to :report
	belongs_to :searchengine
	def to_s
		"#{report}\t#{searchengine}\t#{keyword}\t#{ranking}"
	end
end
