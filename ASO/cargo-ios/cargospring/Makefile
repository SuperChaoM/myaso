GO_EASY_ON_ME = 1
ARCHS = arm64

include $(THEOS)/makefiles/common.mk

TWEAK_NAME = cargospring
cargospring_FILES = x_app.xm \
					x_sim.xm \
					x_volume.xm \
					x_alert.xm \
					../cargostore/StoreTaskManager.m

cargospring_LIBRARIES = rocketbootstrap
cargospring_PRIVATE_FRAMEWORKS = AppSupport
cargospring_CFLAGS = -I../include/
cargospring_LDFLAGS = -L../lib/ -lsimulatetouch

include $(THEOS_MAKE_PATH)/tweak.mk

after-install::
	install.exec "killall -9 SpringBoard"
