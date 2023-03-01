<?php

function active_class($path, $active = 'active')
{
  return call_user_func_array('Request::routeIs', $path) ? $active : '';
}
function current_class($path, $current = 'current')
{
  return call_user_func_array('Request::routeIs', $path) ? $current : '';
}
function expanded_class($path, $active = 'is-expanded')
{
  return call_user_func_array('Request::routeIs', $path) ? $active : '';
}

function is_active_route($path)
{
  return call_user_func_array('Request::routeIs', (array)$path) ? 'true' : 'false';
}

function show_class($path)
{
  return call_user_func_array('Request::routeIs', (array)$path) ? 'show' : '';
}
