# This file is auto-generated from the current state of the database. Instead of editing this file, 
# please use the migrations feature of Active Record to incrementally modify your database, and
# then regenerate this schema definition.
#
# Note that this schema.rb definition is the authoritative source for your database schema. If you need
# to create the application database on another system, you should be using db:schema:load, not running
# all the migrations from scratch. The latter is a flawed and unsustainable approach (the more migrations
# you'll amass, the slower it'll run and the greater likelihood for issues).
#
# It's strongly recommended to check this file into your version control system.

ActiveRecord::Schema.define(:version => 20090507054852) do

  create_table "keywords", :force => true do |t|
    t.string   "keyword"
    t.string   "langcode"
    t.integer  "site_id"
    t.integer  "is_active"
    t.integer  "priority"
    t.datetime "created_at"
    t.datetime "updated_at"
  end

  create_table "reports", :force => true do |t|
    t.integer  "site_id"
    t.string   "name"
    t.datetime "created_at"
    t.datetime "updated_at"
  end

  create_table "searchengine_logs", :force => true do |t|
    t.integer  "report_id"
    t.integer  "searchengine_id"
    t.integer  "keyword_id"
    t.integer  "ranking"
    t.string   "indexed_page"
    t.binary   "contents"
    t.datetime "created_at"
    t.datetime "updated_at"
    t.integer  "site_id"
    t.integer  "results_count"
  end

  add_index "searchengine_logs", ["report_id"], :name => "index_searchengine_logs_on_report_id"

  create_table "searchengines", :force => true do |t|
    t.string   "title"
    t.string   "host"
    t.string   "langcode"
    t.string   "query"
    t.string   "selector"
    t.boolean  "active",     :default => true
    t.datetime "created_at"
    t.datetime "updated_at"
  end

  create_table "sites", :force => true do |t|
    t.string   "host"
    t.datetime "created_at"
    t.datetime "updated_at"
  end

end
