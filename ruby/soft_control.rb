require "xmlrpc/client"

API_ADDRESS = "192.168.1.20:8080"
API_KEY = ""
API_SECRET = ""
API_MODULE = "snaptHA"

# the URL is always /api/apikey/apisecret/module - in this case module is snaptHA for the Balancer
API_FULL_URL = "http://#{API_ADDRESS}/api/#{API_KEY}/#{API_SECRET}/#{API_MODULE}"

def soft_start(client, backend, server)
  client.call("#{API_MODULE}.soft_start", [
    backend,
    server,
  ])
end

def soft_stop(client, backend, server)
  client.call("#{API_MODULE}.soft_stop", [
    backend,
    server,
  ])
end

client = XMLRPC::Client.new2(API_FULL_URL)

backend = "TestGroup"
server = "test01"

soft_start_result = soft_start(client, backend, server)
puts "Soft start: #{soft_start_result}"

soft_stop_result = soft_stop(client, backend, server)
puts "Soft stop: #{soft_stop_result}"
