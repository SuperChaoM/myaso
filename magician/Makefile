GO_EASY_ON_ME = 1
include $(THEOS)/makefiles/common.mk

TWEAK_NAME = magician
magician_FILES = x_mg.xm \
				x_io.xm \
				x_idfa.xm \
				x_ss_device.xm \
				vendor/NSData+FastHex.m

magician_CFLAGS = -Iinclude -Ivendor				
magician_FRAMEWORKS = CydiaSubstrate IOKit
magician_LIBRARIES = MobileGestalt

include $(THEOS_MAKE_PATH)/tweak.mk