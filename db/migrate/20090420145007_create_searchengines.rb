class CreateSearchengines < ActiveRecord::Migration
  def self.up
    create_table :searchengines do |t|
      t.string :title
      t.string :host
      t.string :query
      t.string :selector

      t.timestamps
    end
  end

  def self.down
    drop_table :searchengines
  end
end
