require "xmlrpc/client"

API_ADDRESS = "192.168.1.20:8080"
API_KEY = ""
API_SECRET = ""
API_MODULE = "snaptHA"

# the URL is always /api/apikey/apisecret/module - in this case module is snaptHA for the Balancer
API_FULL_URL = "http://#{API_ADDRESS}/api/#{API_KEY}/#{API_SECRET}/#{API_MODULE}"

def list_methods(client)
  client.call("system.listMethods")
end

def add_group(client, name, address = "0.0.0.0:80", mode = "http", maxconn = 2000)
  client.call("#{API_MODULE}.addGroup", [
    "listen #{name} #{address}",
    "mode #{mode}",
    "maxconn #{maxconn}",
  ])
end

def del_group(client, name)
  client.call("#{API_MODULE}.delGroup", [name])
end

def edit_group(client, name, changes)
  client.call("#{API_MODULE}.editGroup", [
    name,
    changes,
  ])
end

def add_server(client, name, group, group_type = "listen")
  client.call("#{API_MODULE}.addServer", [
    group,
    name,
    group_type,
    "#{name} 10.0.0.50:80 check fall 3 rise 5 inter 2000 weight 10",
  ])
end

def delete_server(client, name, group)
  client.call("#{API_MODULE}.deleteServer", [
    group,
    name,
  ])
end

def reload_balancer(client)
  client.call("#{API_MODULE}.reconfigure")
end

def info_request(client)
  client.call("#{API_MODULE}.socketGet", ["info"])
end

def data_request(client)
  client.call("#{API_MODULE}.socketGet", ["data"])
end

client = XMLRPC::Client.new2(API_FULL_URL)

methods = list_methods(client)
methods.each do |method|
  if method.start_with? "system"
    next
  end
  puts "- #{method}"
end

group_name = "myGroup"
server_name = "web1"

del_group_ok = del_group(client, group_name)
puts "Delete group: #{del_group_ok}"

add_group_ok = add_group(client, group_name)
puts "Add group: #{add_group_ok}"

edit_group_ok = edit_group(client, group_name, [
  "listen #{group_name} 0.0.0.0:80",
  "mode http",
  "maxconn 4000",
])
puts "Edit group: #{edit_group_ok}"

add_server_ok = add_server(client, server_name, group_name)
puts "Add server: #{add_server_ok}"

reload_balancer_ok = reload_balancer(client)
puts "Reload balancer: #{reload_balancer_ok}"

info_request_result = info_request(client)
puts "Info request: #{info_request_result}"

data_request_result = data_request(client)
puts "Data request: #{data_request_result}"

delete_server_ok = delete_server(client, server_name, group_name)
puts "Delete server: #{delete_server_ok}"
