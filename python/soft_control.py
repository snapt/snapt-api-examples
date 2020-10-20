import xmlrpc.client

API_ADDRESS = '192.168.134.135:8080'
API_KEY = ''
API_SECRET = ''
API_MODULE = 'snaptHA'

# the URL is always /api/apikey/apisecret/module - in this case module is snaptHA for the Balancer
API_FULL_URL = f'http://{API_ADDRESS}/api/{API_KEY}/{API_SECRET}/{API_MODULE}'


def soft_start(proxy, backend, server):
    return proxy.snaptHA.soft_start([
        backend,
        server,
    ])


def soft_stop(proxy, backend, server):
    return proxy.snaptHA.soft_stop([
        backend,
        server,
    ])


with xmlrpc.client.ServerProxy(API_FULL_URL) as proxy:
    backend = 'TestGroup';
    server = 'test01';

    soft_start_result = soft_start(proxy, backend, server)
    print(f'Soft start: {soft_start_result}')

    soft_stop_result = soft_stop(proxy, backend, server)
    print(f'Soft stop: {soft_stop_result}')
