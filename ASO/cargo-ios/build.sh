#!/bin/bash

ROOT=$(cd `dirname $0`; pwd)
echo "ROOT: $ROOT"
AUTOVER=$(date +%y%m%d.%H%M%S)
echo "AUTOVER: $AUTOVER"
FAKEROOT="$ROOT/__fat-deb-root"
DEBOUT="$HOME/Desktop/cargo-$AUTOVER.deb"

function build_sub_module(){
	cd $ROOT/$1/
	rm -fr $ROOT/$1/packages
	make -s clean package > /dev/null 2>&1
	if [[ $? -eq 0 ]]; then
		echo "=====> $1 ... ok"
	else
		echo "=====> $1 is not ready"
		exit 1
	fi
	dpkg -X $ROOT/$1/packages/*.deb $FAKEROOT > /dev/null 2>&1
}

function build_fat_deb(){
	dpkg-deb -Zgzip -b $FAKEROOT $DEBOUT > /dev/null 2>&1
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
