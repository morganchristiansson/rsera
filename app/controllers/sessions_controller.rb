class SessionsController < ApplicationController
  skip_before_filter :authorize
  def new
  end

  def create
    session[:password] = params[:password]
    flash[:notice] = 'Successfully logged in'
    redirect_to root_path
  end
  
  def destroy
    reset_session
    flash[:notice] = 'Successfully logged out'
    redirect_to login_path
  end
end

