//
//  ASRectangle.h
//  oc_learn
//
//  Created by mymac on 16/7/18.
//  Copyright © 2016年 mymac. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface ASRectangle1 : NSObject
//{
//    double width;
//    double heigh;
//}

-(void) setWidth:(double)aWidth heigh:(double)aHeigh;
-(double) area;
-(double) len;
-(void) dealloc;
@property(nonatomic, readwrite, assign) double width;
@property(atomic,readonly,assign)double heigh;//assign 和 retain\copy 一组，retain(计数加1) \copy(拷贝个副本)应用于对象赋值，避免入参释放后，相应值变空，默认 readwrite，assign，可省略

@end
