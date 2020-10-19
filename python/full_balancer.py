import xmlrpc.client

API_ADDRESS = '192.168.134.135:8080'
API_KEY = ''
API_SECRET = ''
API_MODULE = 'snaptHA'

# the URL is always /api/apikey/apisecret/module - in this case module is snaptHA for the Balancer
API_FULL_URL = f'http://{API_ADDRESS}/api/{API_KEY}/{API_SECRET}/{API_MODULE}'

GROUP_MODE_HTTP = 'http'


def list_methods(proxy):
    methods = proxy.system.listMethods()
    methods.sort()
    return methods


def add_group(proxy, name, address='0.0.0.0:80', mode=GROUP_MODE_HTTP, maxconn=2000):
    return proxy.snaptHA.addGroup([
        f'listen {name} {address}',
        f'mode {mode}',
        f'maxconn {maxconn}',
    ])


def del_group(proxy, name):
    return proxy.snaptHA.delGroup([name])


def edit_group(proxy, name, changes):
    return proxy.snaptHA.editGroup([
        name,
        changes
    ])


def add_server(proxy, name, group, group_type='listen'):
    return proxy.snaptHA.addServer([
        group,
        name,
        group_type,
        f'{name} 10.0.0.50:80 check fall 3 rise 5 inter 2000 weight 10'
    ])


def delete_server(proxy, name, group):
    return proxy.snaptHA.deleteServer([
        group,
        name,
    ])

def reload_balancer(proxy):
    return proxy.snaptHA.reconfigure()


def info_request(proxy):
    return proxy.snaptHA.socketGet(['info'])


def data_request(proxy):
    return proxy.snaptHA.socketGet(['data'])


with xmlrpc.client.ServerProxy(API_FULL_URL) as proxy:
    methods = list_methods(proxy)
    for method in methods:
        if method.startswith('system'):
            continue
        print(f'- {method}')


    group_name = 'myGroup'
    server_name = 'web1'

    del_group_ok = del_group(proxy, group_name)
    print(f'Delete group: {del_group_ok}')

    add_group_ok = add_group(proxy, group_name)
    print(f'Add group: {add_group_ok}')

    edit_group_ok = edit_group(proxy, group_name, [
        f'listen {group_name} 0.0.0.0:80',
        'mode http',
        'maxconn 4000',
    ])
    print(f'Edit group: {edit_group_ok}')

    add_server_ok = add_server(proxy, server_name, group_name)
    print(f'Add server: {add_server_ok}')

    reload_balancer_ok = reload_balancer(proxy)
    print(f'Reload balancer: {reload_balancer_ok}')

    info_request_result = info_request(proxy)
    print(f'Info request: {info_request_result}')

    data_request_result = data_request(proxy)
    print(f'Data request: {data_request_result}')

    delete_server_ok = delete_server(proxy, server_name, group_name)
    print(f'Delete server: {delete_server_ok}')
