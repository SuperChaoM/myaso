GO_EASY_ON_ME = 1
ARCHS = arm64

include $(THEOS)/makefiles/common.mk

TWEAK_NAME = cargostore
cargostore_FILES = x_app.xm \
				x_mzstatic.xm \
				x_search.xm \
				x_product.xm \
				x_captcha.xm \
				x_req.xm \
				x_alert.xm \
				NSData+GZIP.m \
				StoreTaskManager.m \
				FloatingWindowViewController.m \
				YunDaMa.m \
				SuperHawk.m

# cargostore_FILES = x_captcha.xm \
# 				StoreTaskManager.m \
# 				YunDaMa.m

cargostore_LIBRARIES = rocketbootstrap z
cargostore_PRIVATE_FRAMEWORKS = AppSupport
cargostore_CFLAGS = -I../include/
cargostore_LDFLAGS = -L../lib/ -lsimulatetouch

include $(THEOS_MAKE_PATH)/tweak.mk

after-install::
	install.exec "killall -9 AppStore"
