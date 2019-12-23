def response(flow):
    print(flow.response.content)
    flow.response.status_code = 200
