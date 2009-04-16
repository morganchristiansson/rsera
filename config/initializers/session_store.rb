# Be sure to restart your server when you modify this file.

# Your secret key for verifying cookie session data integrity.
# If you change this key, all old sessions will become invalid!
# Make sure the secret is at least 30 characters and all random, 
# no regular words or you'll be exposed to dictionary attacks.
ActionController::Base.session = {
  :key         => '_rsera_session',
  :secret      => '80e76ebaa388c21cbd90a0f4d338d2845bdab3489d3a9de4fe8f8673acde0e911bffb2bfc9131129126522dee47f3652b15637b546e37f6e2c611bf4b55f85b8'
}

# Use the database for sessions instead of the cookie-based default,
# which shouldn't be used to store highly confidential information
# (create the session table with "rake db:sessions:create")
# ActionController::Base.session_store = :active_record_store
