pw_index="s1lKy"

#create 1aA` (number, letter, capital, special word)
pw_list=[]

word="`!@#$%^&*()_+-=\'\":;/\\<>,.~|[]{}?1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"
#word="1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"
for first in word:
    for second in word:
	pw_list.append(pw_index+first+second) #last

for content in pw_list:
    print content
