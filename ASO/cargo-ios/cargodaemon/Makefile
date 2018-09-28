GO_EASY_ON_ME = 1
ARCHS = arm64

include $(THEOS)/makefiles/common.mk

TOOL_NAME = cargodaemon
cargodaemon_FILES = main.mm \
				DaemonTaskManager.m \
				CargoTask.m \
				DaemonHandyMan.m

cargodaemon_LIBRARIES = rocketbootstrap
cargodaemon_PRIVATE_FRAMEWORKS = AppSupport SpringBoardServices
cargodaemon_CFLAGS = -I../include/
cargodaemon_FRAMEWORKS = MobileCoreServices
cargodaemon_CODESIGN_FLAGS += -SEntitlements.xml

include $(THEOS_MAKE_PATH)/tool.mk
