import xmlrpc.client

API_ADDRESS = '192.168.134.135:8080'
API_KEY = ''
API_SECRET = ''
API_MODULE = 'snaptNginx'

# the URL is always /api/apikey/apisecret/module - in this case module is snaptNginx for the Accelerator
API_FULL_URL = f'http://{API_ADDRESS}/api/{API_KEY}/{API_SECRET}/{API_MODULE}'


def list_methods(proxy):
    methods = proxy.system.listMethods()
    methods.sort()
    return methods


def purge_cache_string(proxy, purge_string):
    return proxy.snaptNginx.purgeCacheString(purge_string)


with xmlrpc.client.ServerProxy(API_FULL_URL) as proxy:
    methods = list_methods(proxy)
    for method in methods:
        if method.startswith('system'):
            continue
        print(f'- {method}')

    purge_string = 'snapt-ui.com'

    purge_cache_string_result = purge_cache_string(proxy, purge_string)
    print(f'Purge cache string: {purge_cache_string_result}')
