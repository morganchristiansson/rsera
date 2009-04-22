class CreateSearchengines < ActiveRecord::Migration
  def self.up
    create_table :searchengines do |t|
      t.string :title
      t.string :host
      t.string :langcode
      t.string :query
      t.string :selector
			t.boolean :active, :default => true
      t.timestamps
    end
  end

  def self.down
    drop_table :searchengines
  end
end

