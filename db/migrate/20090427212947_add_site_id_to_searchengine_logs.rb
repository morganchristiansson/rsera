class AddSiteIdToSearchengineLogs < ActiveRecord::Migration
  def self.up
    add_column :searchengine_logs, :site_id, :integer
  end

  def self.down
    remove_column :searchengine_logs, :site_id
  end
end
