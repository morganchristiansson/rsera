class Website < ActiveRecord::Base
	set_primary_key "ws_id"
	has_many :reports, :foreign_key => "ws_id"
end
