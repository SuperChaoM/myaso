//
//  ASRectangle.m
//  oc_learn
//
//  Created by mymac on 16/7/18.
//  Copyright © 2016年 mymac. All rights reserved.
//

#import "ASRectangle1.h"

@implementation ASRectangle1
-(void) setWidth:(double)aWidth heigh:(double)aHeigh {
    self.width = aWidth;
    heigh = aHeigh;
}

-(double) area {
    return self.width*heigh;
}

-(double) len {
    return (_width + heigh) * 2;
}

-(void)dealloc {
    NSLog(@"ASRectangle dealloc");
    [super dealloc];
}

@synthesize width=_width,heigh;

@end
