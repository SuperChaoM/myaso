#import "macro.h"
#import "msg.h"
#import "task_ret_codes.h"
#import "../cargostore/StoreTaskManager.h"

%hook UIAlertController

- (void)viewDidAppear:(BOOL)arg1 {
	%log;
	%orig;

	NSString *alertTitle = [self title];
	NSLog(@"###cargo alert title is >>> %@ <<<", alertTitle);
	NSString *alertMessage = [self message];
	NSLog(@"###cargo alert message is >>> %@ <<<", alertMessage);
	NSArray *alertActions = [self actions];

	// basic login ui
	if ([@"登录 iTunes Store" isEqualToString:alertTitle]){
		BOOL userEntered = NO;
		BOOL passEntered = NO;

		if (alertMessage != nil){
			// only pass is needed
			userEntered = YES;
		}

		NSDictionary *task = [NSDictionary dictionaryWithContentsOfFile:CG_PATH(@"Task.plist")];
		NSString *username = [task objectForKey:@"email"];
		NSString *password = [task objectForKey:@"password"];
		NSArray *textFieldVCs = MSHookIvar<NSArray *>(self, "_childViewControllers");
		for (id tfvc in textFieldVCs){
			NSArray *tfs = [tfvc textFields];
			for (id tf in tfs){
				if ([tf isSecureTextEntry]){
					[tf setText:password];
					passEntered = YES;
					NSLog(@"#cargo password *** is entered");
				}else if (NO == userEntered) {
					[tf setText:username];
					userEntered = YES;
					NSLog(@"#cargo username %@ is entered", username);
				}
			}
			break;
		}

		if (userEntered && passEntered){
			NSLog(@"#cargo let us click ok to login");
			for (id act in alertActions){
				NSString *actTitle = [act title];
				if ([@"好" isEqualToString: actTitle]){
					NSLog(@"#cargo clicked 好");
					[self nkcg_click:act];
					break;
				}
			}
		}

		return;
	}

	// authenticate fail
	if ([alertTitle containsString:@"验证失败"] && [alertMessage containsString:@"您的 Apple ID 或密码不正确。"]){
		// 0: 取消 1: 再试一次
		for (id act in alertActions){
			NSString *actTitle = [act title];
			if ([@"取消" isEqualToString: actTitle]){
				NSLog(@"#cargo clicked 取消");
				[self nkcg_click:act];
				break;
			}
		}
		return;
	}

	//Apple ID Server Error,This Action Connot be complete
	if ([alertTitle containsString:@"验证失败"] && ([alertMessage containsString:@"连接 Apple ID 服务器时出错。"] || [alertMessage containsString:@"This Action"])){
		// 0: 取消 1: 再试一次
		for (id act in alertActions){
			NSString *actTitle = [act title];
			if ([@"再试一次" isEqualToString: actTitle]){
				NSLog(@"#cargo clicked 再试一次");
				[self nkcg_click:act];
				break;
			}
		}
		return;
	}

	//battery low
	if ([alertTitle isEqualToString:@"电池电量不足"]){
		// 0: 关闭 1: 低电量模式
		for (id act in alertActions){
			NSString *actTitle = [act title];
			if ([@"关闭" isEqualToString: actTitle]){
				NSLog(@"#cargo clicked 电池电量不足-关闭");
				[self nkcg_click:act];
				break;
			}
		}
		return;
	}

	//cannot connect to iTunes
	if ([alertTitle isEqualToString:@"无法连接到 iTunes Store"]){
		// 0: 取消 1: 重试
		for (id act in alertActions){
			NSString *actTitle = [act title];
			if ([@"重试" isEqualToString: actTitle]){
				NSLog(@"#cargo clicked 无法连接到iTunes-重试");
				[self nkcg_click:act];
				break;
			}
			if ([@"好" isEqualToString: actTitle]){
				NSLog(@"#cargo clicked 无法连接到iTunes-好");
				[self nkcg_click:act];
				[[StoreTaskManager sharedManager] appStore_markTaskFail:NET_ERR_ITUNES_CONNECTION];
				break;
			}
		}
		return;
	}

	//cannot connect to iTunes
	if ([alertTitle containsString:@"是否要在此设备上启用自动下载"]){
		// 0: 取消 1: 好
		for (id act in alertActions){
			NSString *actTitle = [act title];
			if ([@"取消" isEqualToString: actTitle]){
				NSLog(@"#cargo clicked 是否要在此设备上启用自动下载-取消");
				[self nkcg_click:act];
				break;
			}
		}
		return;
	}

	// other puchase need password?
	if ([alertTitle containsString:@"在此设备上的其他购买是否需要密码？"]){
		// 0: 始终需要， 1: 15 分钟后需要
		for (id act in alertActions){
			NSString *actTitle = [act title];
			if ([@"始终需要" isEqualToString: actTitle]){
				NSLog(@"#cargo clicked 始终需要");
				[self nkcg_click:act];
				break;
			}
		}
		return;
	}


	// captcha required
	if ([alertTitle containsString:@"获取"] && [alertMessage containsString:@"我们需要先进行一个简短的验证"]){
		for (id act in alertActions){
			NSString *actTitle = [act title];
			if ([@"继续" isEqualToString: actTitle]){
				NSLog(@"#cargo clicked 继续");
				[self nkcg_click:act];
				break;
			}
		}
		return;
	}

	// save password for free items
	if ([alertTitle containsString:@"是否为免费项目保存密码？"]){
		// 0: 是， 1: 否
		for (id act in alertActions){
			NSString *actTitle = [act title];
			if ([@"否" isEqualToString: actTitle]){
				NSLog(@"#cargo clicked 否");
				[self nkcg_click:act];
				break;
			}
		}
		return;
	}

	// need login
	if ([alertTitle containsString:@"需要登录"] && [alertMessage containsString:@"的密码"]){
		NSDictionary *task = [NSDictionary dictionaryWithContentsOfFile:CG_PATH(@"Task.plist")];
		NSString *username = [task objectForKey:@"email"];
		NSString *password = [task objectForKey:@"password"];
		NSArray *textFieldVCs = MSHookIvar<NSArray *>(self, "_childViewControllers");
		for (id tfvc in textFieldVCs){
			NSArray *tfs = [tfvc textFields];
			for (id tf in tfs){
				if ([tf isSecureTextEntry]){
					[tf setText:password];
					NSLog(@"#cargo password *** is entered");
					break;
				}
			}
		}
		for (id act in alertActions){
			NSString *actTitle = [act title];
			if ([@"获取" isEqualToString: actTitle]){
				NSLog(@"#cargo clicked 获取");
				[self nkcg_click:act];
				break;
			}
		}
		return;
	}

  //login Failed
	if ([alertTitle isEqualToString:@"未能登陆"]){
		for (id act in alertActions){
			NSString *actTitle = [act title];
			if ([@"好" isEqualToString: actTitle]){
				NSLog(@"#cargo clicked 未能登陆-好");
				[self nkcg_click:act];
				break;
			}
		}
		[[StoreTaskManager sharedManager] appStore_markTaskFail:MAGICIAN_ERR_LOGIN];
		return;
	}

	if ([alertTitle containsString:@"您的 Apple ID"] && ([alertTitle containsString:@"停用"] || [alertTitle containsString:@"禁用"] || [alertTitle containsString:@"锁定"])){
		for (id act in alertActions){
			NSString *actTitle = [act title];
			if ([@"好" isEqualToString: actTitle]){
				NSLog(@"#cargo clicked 未能登陆-好");
				[self nkcg_click:act];
				break;
			}
		}
		[[StoreTaskManager sharedManager] appStore_markTaskFail:ACCOUNT_ERR_LOCKED];
		return;
	}

	//无法下载应用
	if ([alertTitle containsString:@"无法下载应用"]){
		for (id act in alertActions){
			NSString *actTitle = [act title];
			if ([@"完成" isEqualToString: actTitle]){
				NSLog(@"#cargo clicked 无法下载应用-完成");
				[self nkcg_click:act];
				break;
			}
		}
		return;
	}

	if ([alertTitle containsString:@"无法购买"]){
		for (id act in alertActions){
			NSString *actTitle = [act title];
			if ([@"好" isEqualToString: actTitle]){
				NSLog(@"#cargo clicked 无法购买-好");
				[self nkcg_click:act];
				break;
			}
		}
		return;
	}

	if ([alertTitle containsString:@"软件更新"]){
		for (id act in alertActions){
			NSString *actTitle = [act title];
			if ([@"关闭" isEqualToString: actTitle]){
				NSLog(@"#cargo clicked 软件更新-关闭");
				[self nkcg_click:act];
				break;
			}
		}
		return;
	}

	if ([alertTitle containsString:@"验证 Apple ID"]){
		for (id act in alertActions){
			NSString *actTitle = [act title];
			if ([@"以后" isEqualToString: actTitle]){
				NSLog(@"#cargo clicked 验证 Apple ID-以后，账号被锁定了..");
				[self nkcg_click:act];
				break;
			}
		}
		return;
	}

	// // dismiss by default
	// BOOL autoDismiss = YES;
	// while (1){
	// 	if ([@"未安装 SIM 卡" isEqualToString:alertTitle]){
	// 		autoDismiss = YES;
	// 		break;
	// 	}

	// 	if ([@"验证失败" isEqualToString:alertTitle]){
	// 		autoDismiss = YES;
	// 		break;
	// 	}

	// 	if ([@"iTunes Store" isEqualToString:alertTitle]){
	// 		autoDismiss = YES;
	// 		break;
	// 	}

	// 	if ([@"无法下载应用" isEqualToString:alertTitle]){
	// 		autoDismiss = YES;
	// 		break;
	// 	}

	// 	if ([@"要信任此电脑吗？" isEqualToString:alertTitle]){
	// 		autoDismiss = YES;
	// 		break;
	// 	}

	// 	if ([@"验证 Apple ID" isEqualToString:alertTitle]){
	// 		autoDismiss = YES;
	// 		break;
	// 	}

	// 	// exit infinite loop (!!! IMPORTANT !!!)
	// 	break;
	// }

	// if (autoDismiss){
	// 	if ([alertActions count] > 0){
	// 		[self nkcg_click:alertActions[0]];
	// 		NSLog(@"#cargo dismiss alert by click first action, cancel action is nil");
	// 	}else{
	// 		NSLog(@"#cargo has ZERO alert actions? .... ");
	// 	}
	// }
}


%new
- (void)nkcg_click:(id)act {

	if ([self respondsToSelector:@selector(_dismissWithAction:)]){
		// ios 10
		[self _dismissWithAction:act];
		return;
	}

	NSLog(@"nkcg_click ... IMPLEMENTATION IS NOT FOUND");
}

%end
