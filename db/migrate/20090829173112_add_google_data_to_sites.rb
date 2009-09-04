class AddGoogleDataToSites < ActiveRecord::Migration
  def self.up
    change_table :sites do |s|
      s.string :analytics_email
      s.string :analytics_password
      s.integer :analytics_profile_id
      s.string :analytics_metric
      s.string :analytics_filter
      s.string :analytics_token
    end
  end

  def self.down
    change_table :sites do |s|
      [:analytics_email, :analytics_password, :analytics_profile_id, :analytics_metric, :analytics_filter, :analytics_token].each do |c|
        s.remove c
      end
    end
  end
end
