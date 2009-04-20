class CreateWebsites < ActiveRecord::Migration
  def self.up
    create_table :websites do |t|
      t.string :url
      t.timestamp :datedag

      t.timestamps
    end
  end

  def self.down
    drop_table :websites
  end
end
