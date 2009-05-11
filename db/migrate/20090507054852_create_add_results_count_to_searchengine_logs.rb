class CreateAddResultsCountToSearchengineLogs < ActiveRecord::Migration
  def self.up
    add_column :searchengine_logs, :results_count, :integer
  end

  def self.down
    drop_table :searchengine_logs, :results_count
  end
end
