//
//  people.m
//  oc_learn
//
//  Created by mymac on 16/7/26.
//  Copyright © 2016年 mymac. All rights reserved.
//

#import "NSPeople.h"

@implementation NSPeople

-(id)initWithName:(NSString *)aName WithAge:(unsigned int)aAge WithHeight:(unsigned int)aHeight {
    if (self = [super init]) {
        name = aName;
        age = aAge;
        height = aHeight;
    }
    return self;
}

-(void) dealloc{
    self.name = nil;
    [super dealloc];
}

-(void) print{
    NSLog(@"people's name:%@ , age:%d, height:%d", name, age, height);
}

-(NSString *) description {
    NSString *str = [NSString stringWithFormat:@"people's name:%@ , age:%d, height:%d", name, age, height ];
    return str;
}

@synthesize name,age,height;
@end
