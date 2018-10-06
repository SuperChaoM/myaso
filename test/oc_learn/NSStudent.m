//
//  NSStudent.m
//  oc_learn
//
//  Created by mymac on 16/7/18.
//  Copyright © 2016年 mymac. All rights reserved.
//

#import "NSStudent.h"

@implementation NSStudent
//-(void) setName:(NSString *)aName{
//    if(name != aName){
//        [name release];
//        name = [aName retain];  //相当于 属性设置的 retain ，此时需重写 dealloc 增加：self.name = nil
//    }
//}


-(void) print{
    NSLog(@"student's name:%@, sid:%@, age:%d, height:%d", name, sid, age, height);
}

-(id) initWithName:(NSString*)aName {
    return [self initWithName:aName WithAge:18 WithHeight:180];
}



//-(void)dealloc {
//    self.name = nil;
//    //等价于 [name release]; name = nil;
//    
//    [super dealloc];
//}
-(void) dealloc{
    self.sid = nil;
    [super dealloc];
}

@synthesize sid;
@end
