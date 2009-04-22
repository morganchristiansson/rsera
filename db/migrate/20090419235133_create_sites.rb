class CreateWebsites < ActiveRecord::Migration
  def self.up
    create_table :sites do |t|
      t.string :host
      t.timestamps
    end
  end

  def self.down
    drop_table :websites
  end
end