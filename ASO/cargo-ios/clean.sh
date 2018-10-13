#!/bin/bash


source ./env-setup.sh

ROOT=$(cd `dirname $0`; pwd)
echo "ROOT: $ROOT"
AUTOVER=$(date +%y%m%d.%H%M%S)
#echo "AUTOVER: $AUTOVER"
FAKEROOT="$ROOT/__fat-deb-root"
#DEBOUT="$HOME/Desktop/cargo-$AUTOVER.deb"
DEBOUT="$FAKEROOT/cargo-$AUTOVER.deb"

function build_sub_module(){
	echo "cd $ROOT/$1/"
	cd $ROOT/$1/
	echo "rm -fr $ROOT/$1/debs"
	rm -fr $ROOT/$1/debs
	
	#make -s clean package > /dev/null 2>&1
	echo "make clean > /dev/null 2>&1"
	make clean > /dev/null 2>&1
	
}

# clean
find $ROOT -name .DS_Store -type f | xargs rm -fr

build_sub_module cargodaemon
build_sub_module cargospring
build_sub_module cargoitunes
build_sub_module cargostore


