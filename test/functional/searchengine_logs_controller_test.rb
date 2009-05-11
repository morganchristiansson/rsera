require 'test_helper'

class SearchengineLogsControllerTest < ActionController::TestCase
  test "should get index" do
    get :index
    assert_response :success
    assert_not_nil assigns(:searchengine_logs)
  end

  test "should get new" do
    get :new
    assert_response :success
  end

  test "should create searchengine_log" do
    assert_difference('SearchengineLog.count') do
      post :create, :searchengine_log => { }
    end

    assert_redirected_to searchengine_log_path(assigns(:searchengine_log))
  end

  test "should show searchengine_log" do
    get :show, :id => searchengine_logs(:one).to_param
    assert_response :success
  end

  test "should get edit" do
    get :edit, :id => searchengine_logs(:one).to_param
    assert_response :success
  end

  test "should update searchengine_log" do
    put :update, :id => searchengine_logs(:one).to_param, :searchengine_log => { }
    assert_redirected_to searchengine_log_path(assigns(:searchengine_log))
  end

  test "should destroy searchengine_log" do
    assert_difference('SearchengineLog.count', -1) do
      delete :destroy, :id => searchengine_logs(:one).to_param
    end

    assert_redirected_to searchengine_logs_path
  end
end
