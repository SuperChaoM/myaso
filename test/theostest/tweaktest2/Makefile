THEOS_DEVICE_IP = 192.168.1.107
include $(THEOS)/makefiles/common.mk

TWEAK_NAME = tweaktest2
tweaktest2_FILES = Tweak.xm

include $(THEOS_MAKE_PATH)/tweak.mk

after-install::
	install.exec "killall -9 SpringBoard"
