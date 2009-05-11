class IndexReportIdOnSearchengineLogs < ActiveRecord::Migration
  def self.up
    add_index :searchengine_logs, :report_id
  end

  def self.down
    remove_index :searchengine_logs, :report_id
  end
end
