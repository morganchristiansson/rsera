ActionController::Routing::Routes.draw do |map|

  map.resources :searchengines
  map.resources :searchengine_logs
  map.searchengine_logs_contents 'searchengine_logs/contents/:id', :controller => "SearchengineLogs", :action => "contents"

  map.resources :sites, :requirements => { :id => %r([^/;,]+) } do |ws|
    ws.resources :keywords, :only   => [:index,:new], :requirements => { :site_id => %r([^/;,]+) }
    ws.resources :reports, :only   => [:index,:new], :requirements => { :site_id => %r([^/;,]+) }
  end
  map.resources :keywords, :except => [:index,:new]
  map.resources :reports, :except => [:index,:new]

  map.root :controller => "Sites"
  

  map.site_trends 'sites/trends/:id', :controller => "Sites", :action => "trends", :requirements => { :id => %r([^/;,]+) }

  # The priority is based upon order of creation: first created -> highest priority.

  # Sample of regular route:
  #   map.connect 'products/:id', :controller => 'catalog', :action => 'view'
  # Keep in mind you can assign values other than :controller and :action

  # Sample of named route:
  #   map.purchase 'products/:id/purchase', :controller => 'catalog', :action => 'purchase'
  # This route can be invoked with purchase_url(:id => product.id)

  # Sample resource route (maps HTTP verbs to controller actions automatically):
  #   map.resources :products

  # Sample resource route with options:
  #   map.resources :products, :member => { :short => :get, :toggle => :post }, :collection => { :sold => :get }

  # Sample resource route with sub-resources:
  #   map.resources :products, :has_many => [ :comments, :sales ], :has_one => :seller
  
  # Sample resource route with more complex sub-resources
  #   map.resources :products do |products|
  #     products.resources :comments
  #     products.resources :sales, :collection => { :recent => :get }
  #   end

  # Sample resource route within a namespace:
  #   map.namespace :admin do |admin|
  #     # Directs /admin/products/* to Admin::ProductsController (app/controllers/admin/products_controller.rb)
  #     admin.resources :products
  #   end

  # You can have the root of your site routed with map.root -- just remember to delete public/index.html.
  # map.root :controller => "welcome"

  # See how all your routes lay out with "rake routes"

  # Install the default routes as the lowest priority.
  # Note: These default routes make all actions in every controller accessible via GET requests. You should
  # consider removing the them or commenting them out if you're using named routes and resources.
  map.connect ':controller/:action/:id', :requirements => { :id => %r([^/;,]+) }
  map.connect ':controller/:action/:id.:format', :requirements => { :id => %r([^/;,]+) }
  
  map.resources :sessions
  map.login 'login', :controller => "Sessions", :action => 'new'
  map.logout 'logout', :controller => "Sessions", :action => 'destroy'
end
