class CreateSearchengineLogs < ActiveRecord::Migration
  def self.up
    create_table :searchengine_logs do |t|
      t.integer    :report_id
      t.integer    :searchengine_id
      t.integer    :keyword_id
      t.integer    :ranking
      t.string     :indexed_page
      t.binary     :contents
      t.timestamps
    end
  end

  def self.down
    drop_table   :searchengine_logs
  end
end
