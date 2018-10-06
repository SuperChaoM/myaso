include $(THEOS)/makefiles/common.mk

APPLICATION_NAME = apptest
apptest_FILES = main.m ZJAppDelegate.m ZJRootViewController.m
apptest_FRAMEWORKS = UIKit CoreGraphics

include $(THEOS_MAKE_PATH)/application.mk

after-install::
	install.exec "killall \"apptest\"" || true
