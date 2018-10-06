//
//  ASPerson.m
//  oc_learn
//
//  Created by mymac on 16/8/6.
//  Copyright © 2016年 mymac. All rights reserved.
//

#import "ASPerson.h"
#import "ASShape.h"
#import "ASRectrangle.h"
#import "ASTriangle.h"
#import "ASCircle.h"
@implementation ASPerson
-(void) paint:(ASShape * )aShape{
    [aShape draw];
}
@end
