#!/bin/bash

source ./env-setup.sh

ROOT=$(cd `dirname $0`; pwd)
echo "ROOT: $ROOT"
AUTOVER=$(date +%y%m%d.%H%M%S)
echo "AUTOVER: $AUTOVER"
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
	echo "make package > /dev/null 2>&1"
	make package > /dev/null 2>&1
	if [[ $? -eq 0 ]]; then
		echo "=====> $1 ... ok"
	else
		echo "=====> $1 is not ready"
		exit 1
	fi
	echo "dpkg -X $ROOT/$1/debs/*.deb $FAKEROOT"
	#dpkg -X $ROOT/$1/debs/*.deb $FAKEROOT > /dev/null 2>&1
	dpkg -X $ROOT/$1/debs/*.deb $FAKEROOT 
}


function build_fat_deb(){
	chmod -R 775 $FAKEROOT
	echo "dpkg-deb -Zgzip -b $FAKEROOT $DEBOUT"
	#dpkg-deb -Zgzip -b $FAKEROOT $DEBOUT > /dev/null 2>&1
	dpkg-deb -Zgzip -b $FAKEROOT $DEBOUT
}

# clean
find $ROOT -name .DS_Store -type f | xargs rm -fr

# init paths
test -d $FAKEROOT && rm -fr $FAKEROOT
mkdir $FAKEROOT
cp -fr $ROOT/misc/* $FAKEROOT

# update versions
cd $ROOT
sed -i ""  "s|Version: .*|Version: $AUTOVER|g" $FAKEROOT/DEBIAN/control
sed -i "" "s|cargo/.*\";|cargo/$AUTOVER\";|g" include/constants.h

build_sub_module cargodaemon
build_sub_module cargospring
build_sub_module cargoitunes
build_sub_module cargostore
build_fat_deb
