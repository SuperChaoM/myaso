#!/bin/bash

GOD_HEADER_FILE="only_god_can_read.h"
STRING_SYMBOL_FILE="function-list.txt"

function clear_old_versions()
{
	test -f $GOD_HEADER_FILE && rm -f $GOD_HEADER_FILE
	touch $GOD_HEADER_FILE
}

function ramdom_string()  
{  
    openssl rand -base64 64 | tr -cd 'a-zA-Z' |head -c 32  
}  


function hide_logs()
{
	echo "  #define NSLog(format, ...) {} " >> $GOD_HEADER_FILE
}

function curse_symbols()
{
	cat "$STRING_SYMBOL_FILE" | while read -ra line; \
	do  
	    if [[ ! -z "$line" ]]; then  
	        spell=`ramdom_string`  
	        echo $line $spell  
	        echo "  #define $line $spell" >> $GOD_HEADER_FILE  
	    fi  
	done  
}

function begin_header()
{
	echo "#ifndef nkaso_only_god_can_read_h" >> $GOD_HEADER_FILE
	echo "#define nkaso_only_god_can_read_h" >> $GOD_HEADER_FILE
}

function end_header()
{
	echo "#endif // nkaso_only_god_can_read_h" >> $GOD_HEADER_FILE
}


clear_old_versions
begin_header
hide_logs
curse_symbols
end_header


