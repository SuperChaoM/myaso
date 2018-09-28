GO_EASY_ON_ME = 1
ARCHS = arm64

include $(THEOS)/makefiles/common.mk

TWEAK_NAME = cargoitunes
cargoitunes_FILES = x_app.xm \
				x_req.xm \
				NSData+GZIP.m \
				iTunesTaskManager.m \
				../cargostore/StoreTaskManager.m

cargoitunes_LIBRARIES = rocketbootstrap z
cargoitunes_PRIVATE_FRAMEWORKS = AppSupport
cargoitunes_CFLAGS = -I../include/


include $(THEOS_MAKE_PATH)/tweak.mk

after-install::
	install.exec "killall -9 itunesstored"
