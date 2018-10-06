THEOS_DEVICE_IP = 192.168.124.13
ARCHS = arm64
TARGET = iphone:latest:8.0

include $(THEOS)/makefiles/common.mk

TOOL_NAME = tooltest
tooltest_FILES = main.mm DaemonTaskManager.m


tooltest_LIBRARIES = rocketbootstrap
tooltest_PRIVATE_FRAMEWORKS = AppSupport

include $(THEOS_MAKE_PATH)/tool.mk
