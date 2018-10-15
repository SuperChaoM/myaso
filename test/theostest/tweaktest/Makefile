THEOS_DEVICE_IP = 192.168.124.14

ARCHS = armv7 arm64
TARGET = iphone:latest:8.0

include $(THEOS)/makefiles/common.mk

TWEAK_NAME = tweaktest
tweaktest_FILES = Tweak.xm

tweaktest_LIBRARIES = rocketbootstrap
tweaktest_PRIVATE_FRAMEWORKS = AppSupport
cargodaemon_FRAMEWORKS = MobileCoreServices
include $(THEOS_MAKE_PATH)/tweak.mk

after-install::
	install.exec "killall -9 SpringBoard"
