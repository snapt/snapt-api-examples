import xmlrpclib

# the URL is always /api/apikey/apisecret/module
# in this case module is snaptHA for the Balancer

proxy = xmlrpclib.ServerProxy("http://localhost:8080/api/595-1eec-13f3-12fb/SNP54eda0bb8ec48324035047/snaptHA");
print(str(proxy.snaptHA.socketGet("info")));

