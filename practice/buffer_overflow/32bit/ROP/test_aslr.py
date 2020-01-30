from pwn import *

elf = ELF("./rop")
print "PLT : "
print elf.plt
print hex(elf.plt['printf'])
print elf.disasm(elf.plt['printf'], 20)

print "GOT : "
print elf.got
print hex(elf.got['printf'])
print elf.disasm(elf.got['printf'], 20)
