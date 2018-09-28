help:
	echo "make do"
	echo "make undo"

do:
	./generate_devil_curse.sh
	cp only_god_can_read.h ../include/

undo:
	test ../include/only_god_can_read.h && echo "" > ../include/only_god_can_read.h
