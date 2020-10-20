require "xmlrpc/client"

API_ADDRESS = "192.168.1.20:8080"
API_KEY = "1f29-1266-694-18ec"
API_SECRET = "SNP5f8d8ef59d229240105044"
API_MODULE = "snaptNginx"

# the URL is always /api/apikey/apisecret/module - in this case module is snaptHA for the Balancer
API_FULL_URL = "http://#{API_ADDRESS}/api/#{API_KEY}/#{API_SECRET}/#{API_MODULE}"

def list_methods(client)
  client.call("system.listMethods")
end

def purge_cache_string(client, purge_string)
  client.call("#{API_MODULE}.purgeCacheString", purge_string)
end

client = XMLRPC::Client.new2(API_FULL_URL)

methods = list_methods(client)
methods.each do |method|
  if method.start_with? "system"
    next
  end
  puts "- #{method}"
end

purge_cache_string_result = purge_cache_string(client, "snapt-ui.com")
puts "Purge cache string: #{purge_cache_string_result}"
