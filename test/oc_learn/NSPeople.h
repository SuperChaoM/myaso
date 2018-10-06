//
//  people.h
//  oc_learn
//
//  Created by mymac on 16/7/26.
//  Copyright © 2016年 mymac. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface NSPeople : NSObject {
    NSString * name;
    unsigned int age;
    unsigned int height;
}
-(id) initWithName:(NSString *)aName WithAge:(unsigned int)aAge WithHeight:(unsigned int) aHeight;
-(void) dealloc;
-(void) print;

@property(nonatomic, readwrite, retain) NSString * name;
@property(nonatomic, readwrite, assign) unsigned int age;
@property(nonatomic, readwrite, assign) unsigned int height;
@end
