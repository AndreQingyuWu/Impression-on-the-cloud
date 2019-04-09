# -*- utf-8 -*-
import json

import phpserialize
import sys
import redis
import compare


def test(target):
    # redis
    targetName = target
    r = redis.Redis(host='127.0.0.1', port=6379)
    file_context = r.get(target)
    target = r.get(target + '_target')

    search_list = []

    content = phpserialize.loads(file_context, decode_strings='utf-8')
    content = eval(content)

    #
    key = phpserialize.loads(target, decode_strings='utf-8')
    search_key = eval(key)

    for i in range(len(content)):
        # print(i)
        # print(content[i]['characteristic_value'])
        search_list.append(eval(content[i]['characteristic_value']))
    c = compare.Compare(search_key, search_list, 1)

    for i in range(len(content)):
        del content[i]['id']
        del content[i]['created_at']
        del content[i]['updated_at']
        del content[i]['characteristic_value']

        sim_temp = c.feature_camparator(search_key, search_list, i)
        content[i]['sim'] = sim_temp
    result = phpserialize.dumps(content)
    r.set(targetName+'_res', result)
    print('success')


test(sys.argv[1])