class CreateKeywords < ActiveRecord::Migration
  def self.up
    create_table :keywords do |t|
      t.string :keyword
      t.string :langcode
      t.integer :is_active
      t.integer :priority
      t.timestamps
    end
  end

  def self.down
    drop_table :keywords
  end
end
